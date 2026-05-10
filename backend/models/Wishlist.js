const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/db');

const Wishlist = sequelize.define('Wishlist', {
    // userId va productId avtomatik bog'lanadi
});

module.exports = Wishlist;
