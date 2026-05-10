const jwt = require('jsonwebtoken');

const verifyToken = (req, res, next) => {
    const token = req.header('Authorization');
    if (!token) return res.status(401).json({ message: 'Kirish taqiqlangan! Token yo\'q.' });

    try {
        const decoded = jwt.verify(token.replace('Bearer ', ''), process.env.JWT_SECRET || 'secretkey');
        req.user = decoded;
        next();
    } catch (error) {
        res.status(400).json({ message: 'Noto\'g\'ri token!' });
    }
};

const isAdmin = (req, res, next) => {
    // Vaqtincha hamma uchun admin ruxsatini beramiz
    next();
};

module.exports = { verifyToken, isAdmin };
