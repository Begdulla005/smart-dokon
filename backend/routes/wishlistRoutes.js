const express = require('express');
const { Wishlist, Product } = require('../models');
const { verifyToken } = require('../middlewares/authMiddleware');

const router = express.Router();

// Wishlistga qo'shish/o'chirish (Toggle)
router.post('/toggle', verifyToken, async (req, res) => {
    try {
        const { productId } = req.body;
        const userId = req.user.id;

        const existing = await Wishlist.findOne({ where: { userId, productId } });

        if (existing) {
            await existing.destroy();
            return res.json({ message: 'O\'chirildi', added: false });
        } else {
            await Wishlist.create({ userId, productId });
            return res.json({ message: 'Qo\'shildi', added: true });
        }
    } catch (error) {
        res.status(500).json({ message: 'Xatolik', error: error.message });
    }
});

// Foydalanuvchi wishlistini olish
router.get('/', verifyToken, async (req, res) => {
    try {
        const items = await Wishlist.findAll({
            where: { userId: req.user.id },
            include: [{ model: Product }]
        });
        res.json(items);
    } catch (error) {
        res.status(500).json({ message: 'Xatolik', error: error.message });
    }
});

module.exports = router;
