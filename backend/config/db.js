const { Sequelize } = require('sequelize');
const path = require('path');

// SQLite bazasiga ulanish (Fayl ko'rinishida)
const sequelize = new Sequelize({
    dialect: 'sqlite',
    storage: path.join(__dirname, '../database.sqlite'),
    logging: false,
});

const connectDB = async () => {
    try {
        await sequelize.authenticate();
        console.log('SQLite bazasi muvaffaqiyatli ulandi! (Server shart emas)');
    } catch (error) {
        console.error('Baza bilan bog\'lanishda xatolik:', error);
    }
};

module.exports = { sequelize, connectDB };
