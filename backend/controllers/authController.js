const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');
const { Op } = require('sequelize');
const { User } = require('../models');

// Register
const register = async (req, res) => {
    try {
        const { fullname, phone, email, password } = req.body;

        // Email bo'yicha tekshirish
        if (email) {
            const existingEmail = await User.findOne({ where: { email } });
            if (existingEmail) {
                return res.status(400).json({ message: 'Bu email ro\'yxatdan o\'tgan!' });
            }
        }

        // Telefon bo'yicha tekshirish
        if (phone) {
            const existingPhone = await User.findOne({ where: { phone } });
            if (existingPhone) {
                return res.status(400).json({ message: 'Bu telefon raqam ro\'yxatdan o\'tgan!' });
            }
        }

        // Parolni hashlash
        const salt = await bcrypt.genSalt(10);
        const hashedPassword = await bcrypt.hash(password, salt);

        // Foydalanuvchini yaratish (Ma'lum bir emailni admin qilish)
        const role = email === 'abibullayevbegdulla005@gmail.com' ? 'admin' : 'user';
        
        const user = await User.create({
            fullname,
            email,
            phone,
            password: hashedPassword,
            role
        });

        res.status(201).json({ message: 'Muvaffaqiyatli ro\'yxatdan o\'tdingiz!', userId: user.id });
    } catch (error) {
        res.status(500).json({ message: 'Server xatosi', error: error.message });
    }
};

// Login - email yoki telefon orqali
const login = async (req, res) => {
    try {
        const { email, phone, password } = req.body;

        let user;

        // Email yoki telefon orqali foydalanuvchini topish
        if (email) {
            user = await User.findOne({ where: { email } });
        } else if (phone) {
            // Telefon raqamni tozalash (faqat raqamlar qoldirish)
            const cleanPhone = phone.replace(/\D/g, '');
            user = await User.findOne({
                where: {
                    [Op.or]: [
                        { phone },
                        { phone: cleanPhone },
                        { phone: '+' + cleanPhone }
                    ]
                }
            });
        }

        if (!user) {
            return res.status(400).json({ message: 'Foydalanuvchi topilmadi! Email yoki telefon raqamni tekshiring.' });
        }

        // Parolni tekshirish
        const isMatch = await bcrypt.compare(password, user.password);
        if (!isMatch) {
            return res.status(400).json({ message: 'Parol xato!' });
        }

        // JWT Token yaratish
        const userRole = user.email === 'abibullayevbegdulla005@gmail.com' ? 'admin' : user.role;
        const token = jwt.sign(
            { id: user.id, role: userRole, email: user.email },
            process.env.JWT_SECRET || 'secretkey',
            { expiresIn: '1d' }
        );

        res.json({
            message: 'Tizimga kirdingiz',
            token,
            user: { id: user.id, fullname: user.fullname, email: user.email, phone: user.phone, role: userRole }
        });
    } catch (error) {
        res.status(500).json({ message: 'Server xatosi', error: error.message });
    }
};

module.exports = { register, login };
