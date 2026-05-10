const { Product, Category } = require('../models');

// Barcha mahsulotlarni olish
const getProducts = async (req, res) => {
    try {
        const products = await Product.findAll({
            include: [{ model: Category, attributes: ['id', 'name'] }]
        });
        res.json(products);
    } catch (error) {
        res.status(500).json({ message: 'Xatolik yuz berdi', error: error.message });
    }
};

// Bitta mahsulotni olish
const getProductById = async (req, res) => {
    try {
        const { Review, User } = require('../models');
        const product = await Product.findByPk(req.params.id, {
            include: [
                { model: Review, include: [{ model: User, attributes: ['fullname'] }] }
            ]
        });
        if (product) {
            res.json(product);
        } else {
            res.status(404).json({ message: 'Mahsulot topilmadi' });
        }
    } catch (error) {
        res.status(500).json({ message: 'Xatolik yuz berdi', error: error.message });
    }
};

// Yangi mahsulot qo'shish (Faqat admin uchun)
const createProduct = async (req, res) => {
    try {
        const { name, description, price, stock, categoryId } = req.body;
        const images = req.file ? `http://localhost:5000/uploads/${req.file.filename}` : req.body.images;
        const product = await Product.create({ name, description, price, stock, categoryId, images });
        res.status(201).json({ message: 'Mahsulot qo\'shildi', product });
    } catch (error) {
        res.status(500).json({ message: 'Xatolik yuz berdi', error: error.message });
    }
};

// Mahsulotni tahrirlash (Faqat admin uchun)
const updateProduct = async (req, res) => {
    try {
        const { name, description, price, stock, categoryId } = req.body;
        const product = await Product.findByPk(req.params.id);
        
        if (!product) return res.status(404).json({ message: 'Mahsulot topilmadi' });

        const images = req.file ? `http://localhost:5000/uploads/${req.file.filename}` : product.images;

        await product.update({ name, description, price, stock, categoryId, images });
        res.json({ message: 'Mahsulot yangilandi', product });
    } catch (error) {
        res.status(500).json({ message: 'Xatolik yuz berdi', error: error.message });
    }
};

// Mahsulotni o'chirish (Faqat admin)
const deleteProduct = async (req, res) => {
    try {
        const product = await Product.findByPk(req.params.id);
        if (!product) return res.status(404).json({ message: 'Mahsulot topilmadi' });

        await product.destroy();
        res.json({ message: 'Mahsulot o\'chirildi' });
    } catch (error) {
        res.status(500).json({ message: 'Xatolik yuz berdi', error: error.message });
    }
};

module.exports = { getProducts, getProductById, createProduct, updateProduct, deleteProduct };
