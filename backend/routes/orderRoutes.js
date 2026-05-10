const express = require('express');
const { Order, OrderItem, Cart, Product, User } = require('../models');
const { verifyToken } = require('../middlewares/authMiddleware');

const router = express.Router();

// Buyurtma yaratish (checkout)
router.post('/', verifyToken, async (req, res) => {
    try {
        const { address, paymentMethod } = req.body;
        console.log('Yangi buyurtma keldi:', { userId: req.user.id, address });

        // Savatdagi mahsulotlarni olish
        const cartItems = await Cart.findAll({
            where: { userId: req.user.id },
            include: [{ model: Product }]
        });

        if (!cartItems || cartItems.length === 0) {
            return res.status(400).json({ message: 'Savatingiz bo\'sh!' });
        }

        // Umumiy narxni hisoblash
        let totalPrice = 0;
        for (const item of cartItems) {
            if (item.Product) {
                totalPrice += Number(item.Product.price) * item.quantity;
            }
        }

        // Buyurtma yaratish
        const order = await Order.create({
            userId: req.user.id,
            totalPrice: totalPrice,
            address: address || 'Manzil ko\'rsatilmagan',
            paymentMethod: paymentMethod || 'cash',
            orderStatus: 'Pending',
            paymentStatus: 'Unpaid'
        });

        // OrderItem'larni yaratish
        for (const item of cartItems) {
            if (item.Product) {
                await OrderItem.create({
                    orderId: order.id,
                    productId: item.productId,
                    quantity: item.quantity,
                    price: item.Product.price
                });

                // Stokni kamaytirish
                const product = await Product.findByPk(item.productId);
                if (product) {
                    product.stock = Math.max(0, (product.stock || 0) - item.quantity);
                    await product.save();
                }
            }
        }

        // Savatni tozalash
        await Cart.destroy({ where: { userId: req.user.id } });

        res.status(201).json({ message: 'Buyurtma muvaffaqiyatli yaratildi!', order });
    } catch (error) {
        console.error('BUYURTMA XATOSI:', error);
        res.status(500).json({ message: 'Server xatosi', error: error.message });
    }
});

// Foydalanuvchining buyurtmalarini ko'rish
router.get('/my', verifyToken, async (req, res) => {
    try {
        const orders = await Order.findAll({
            where: { userId: req.user.id },
            include: [{ model: OrderItem, include: [Product] }],
            order: [['createdAt', 'DESC']]
        });
        res.json(orders);
    } catch (error) {
        res.status(500).json({ message: 'Xatolik', error: error.message });
    }
});

// Barcha buyurtmalarni ko'rish (Faqat admin uchun)
router.get('/all', verifyToken, async (req, res) => {
    try {
        const orders = await Order.findAll({
            include: [
                { model: User, attributes: ['fullname', 'phone', 'email'] },
                { model: OrderItem, include: [Product] }
            ],
            order: [['createdAt', 'DESC']]
        });
        res.json(orders);
    } catch (error) {
        res.status(500).json({ message: 'Xatolik', error: error.message });
    }
});

module.exports = router;
