const express = require('express');
const { Cart, Product, Category } = require('../models');
const { verifyToken } = require('../middlewares/authMiddleware');

const router = express.Router();

// Savatni ko'rish
router.get('/', verifyToken, async (req, res) => {
    try {
        const cartItems = await Cart.findAll({
            where: { userId: req.user.id },
            include: [{ model: Product, include: [{ model: Category, attributes: ['id', 'name'] }] }]
        });
        res.json(cartItems);
    } catch (error) {
        res.status(500).json({ message: 'Xatolik yuz berdi', error: error.message });
    }
});

// Savatga qo'shish
router.post('/', verifyToken, async (req, res) => {
    try {
        const { productId, quantity } = req.body;

        // Mahsulot mavjudligini tekshirish
        const product = await Product.findByPk(productId);
        if (!product) return res.status(404).json({ message: 'Mahsulot topilmadi' });

        // Savatda bor-yo'qligini tekshirish
        const existing = await Cart.findOne({ where: { userId: req.user.id, productId } });
        if (existing) {
            existing.quantity += (quantity || 1);
            await existing.save();
            return res.json({ message: 'Savatda yangilandi', cart: existing });
        }

        const cart = await Cart.create({
            userId: req.user.id,
            productId,
            quantity: quantity || 1
        });
        res.status(201).json({ message: 'Savatga qo\'shildi', cart });
    } catch (error) {
        res.status(500).json({ message: 'Xatolik yuz berdi', error: error.message });
    }
});

// Savat miqdorini yangilash
router.put('/:id', verifyToken, async (req, res) => {
    try {
        const { quantity } = req.body;
        const cartItem = await Cart.findOne({ where: { id: req.params.id, userId: req.user.id } });
        if (!cartItem) return res.status(404).json({ message: 'Savat elementi topilmadi' });

        cartItem.quantity = quantity;
        await cartItem.save();
        res.json({ message: 'Yangilandi', cart: cartItem });
    } catch (error) {
        res.status(500).json({ message: 'Xatolik yuz berdi', error: error.message });
    }
});

// Savatdan o'chirish
router.delete('/:id', verifyToken, async (req, res) => {
    try {
        const cartItem = await Cart.findOne({ where: { id: req.params.id, userId: req.user.id } });
        if (!cartItem) return res.status(404).json({ message: 'Savat elementi topilmadi' });

        await cartItem.destroy();
        res.json({ message: 'Savatdan o\'chirildi' });
    } catch (error) {
        res.status(500).json({ message: 'Xatolik yuz berdi', error: error.message });
    }
});

// Savatni tozalash
router.delete('/', verifyToken, async (req, res) => {
    try {
        await Cart.destroy({ where: { userId: req.user.id } });
        res.json({ message: 'Savat tozalandi' });
    } catch (error) {
        res.status(500).json({ message: 'Xatolik yuz berdi', error: error.message });
    }
});

module.exports = router;
