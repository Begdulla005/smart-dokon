const { sequelize, connectDB } = require('../config/db');

const User = require('./User');
const Product = require('./Product');
const Category = require('./Category');
const Order = require('./Order');
const OrderItem = require('./OrderItem');
const Cart = require('./Cart');
const Review = require('./Review');
const Wishlist = require('./Wishlist');
const Seller = require('./Seller');

// --- BOG'LANISHLAR (RELATIONS) ---

// User <-> Seller (Har bir sotuvchi - bu foydalanuvchi)
User.hasOne(Seller, { foreignKey: 'userId' });
Seller.belongsTo(User, { foreignKey: 'userId' });

// Seller <-> Product (Mahsulot sotuvchiga tegishli)
Seller.hasMany(Product, { foreignKey: 'sellerId' });
Product.belongsTo(Seller, { foreignKey: 'sellerId' });

// Category <-> Product
Category.hasMany(Product, { foreignKey: 'categoryId' });
Product.belongsTo(Category, { foreignKey: 'categoryId' });

// User <-> Order
User.hasMany(Order, { foreignKey: 'userId' });
Order.belongsTo(User, { foreignKey: 'userId' });

// Order <-> OrderItem
Order.hasMany(OrderItem, { foreignKey: 'orderId' });
OrderItem.belongsTo(Order, { foreignKey: 'orderId' });

// Product <-> OrderItem
Product.hasMany(OrderItem, { foreignKey: 'productId' });
OrderItem.belongsTo(Product, { foreignKey: 'productId' });

// User <-> Cart & Wishlist
User.hasMany(Cart, { foreignKey: 'userId' });
Cart.belongsTo(User, { foreignKey: 'userId' });
User.hasMany(Wishlist, { foreignKey: 'userId' });
Wishlist.belongsTo(User, { foreignKey: 'userId' });

// Product <-> Review & Wishlist
Product.hasMany(Review, { foreignKey: 'productId' });
Review.belongsTo(Product, { foreignKey: 'productId' });
Product.hasMany(Wishlist, { foreignKey: 'productId' });
Wishlist.belongsTo(Product, { foreignKey: 'productId' });

module.exports = {
    sequelize,
    connectDB,
    User,
    Product,
    Category,
    Order,
    OrderItem,
    Cart,
    Review,
    Wishlist,
    Seller
};
