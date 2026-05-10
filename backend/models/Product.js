const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/db');

const Product = sequelize.define('Product', {
    id: { type: DataTypes.INTEGER, primaryKey: true, autoIncrement: true },
    name: { type: DataTypes.STRING, allowNull: false },
    description: { type: DataTypes.TEXT, allowNull: true },
    price: { type: DataTypes.DECIMAL(10, 2), allowNull: false },
    stock: { type: DataTypes.INTEGER, defaultValue: 0 },
    images: { type: DataTypes.TEXT, allowNull: true }, // Rasmlar ro'yxatini JSON (string) shaklida saqlash
    rating: { type: DataTypes.FLOAT, defaultValue: 0 },
}, {
    timestamps: true
});

module.exports = Product;
