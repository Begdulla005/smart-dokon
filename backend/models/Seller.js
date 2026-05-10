const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/db');

const Seller = sequelize.define('Seller', {
    storeName: { type: DataTypes.STRING, allowNull: false },
    description: { type: DataTypes.TEXT },
    logo: { type: DataTypes.STRING },
    balance: { type: DataTypes.DECIMAL(15, 2), defaultValue: 0 },
    status: { 
        type: DataTypes.ENUM('Pending', 'Active', 'Suspended'), 
        defaultValue: 'Pending' 
    },
    isVerified: { type: DataTypes.BOOLEAN, defaultValue: false },
    address: { type: DataTypes.STRING },
    phone: { type: DataTypes.STRING, allowNull: false },
});

module.exports = Seller;
