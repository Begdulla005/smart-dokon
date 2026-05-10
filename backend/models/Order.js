const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/db');

const Order = sequelize.define('Order', {
    id: { type: DataTypes.INTEGER, primaryKey: true, autoIncrement: true },
    totalPrice: { type: DataTypes.DECIMAL(10, 2), allowNull: false },
    orderStatus: { type: DataTypes.ENUM('Pending', 'Processing', 'Delivered', 'Cancelled'), defaultValue: 'Pending' },
    paymentStatus: { type: DataTypes.ENUM('Unpaid', 'Paid', 'Failed'), defaultValue: 'Unpaid' },
    address: { type: DataTypes.STRING, allowNull: true },
    paymentMethod: { type: DataTypes.ENUM('cash', 'card'), defaultValue: 'cash' },
}, {
    timestamps: true
});

module.exports = Order;
