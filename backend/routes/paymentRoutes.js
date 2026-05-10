const express = require('express');
const router = express.Router();
const { verifyToken } = require('../middlewares/authMiddleware');

// Click to'lovini boshlash
router.post('/click', verifyToken, async (req, res) => {
    const { amount, orderId } = req.body;
    // Click API uchun redirect URL tayyorlash
    const clickUrl = `https://my.click.uz/services/pay?service_id=YOUR_SERVICE_ID&merchant_id=YOUR_MERCHANT_ID&amount=${amount}&transaction_param=${orderId}`;
    res.json({ url: clickUrl });
});

// Payme to'lovini boshlash
router.post('/payme', verifyToken, async (req, res) => {
    const { amount, orderId } = req.body;
    // Payme uchun base64 formatida URL yaratish
    const params = `m=YOUR_MERCHANT_ID;ac.order_id=${orderId};a=${amount * 100}`;
    const encoded = Buffer.from(params).toString('base64');
    const paymeUrl = `https://checkout.paycom.uz/${encoded}`;
    res.json({ url: paymeUrl });
});

module.exports = router;
