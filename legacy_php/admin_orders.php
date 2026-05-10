<?php
session_start();
include 'db.php';
if (isset($conn)) { $db = $conn; }

// Xavfsizlik: Admin kirmagan bo'lsa, loginga haydaymiz
if (!isset($_SESSION['admin_log'])) {
    header("Location: login.php");
    exit();
}

// Bazadan ma'lumotlarni olish
$mahsulotlar = mysqli_query($db, "SELECT * FROM mahsulotlar");
$buyurtmalar = mysqli_query($db, "SELECT * FROM users"); // Test uchun foydalanuvchilar
?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title>Admin Boshqaruv Paneli</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f2f5; margin: 0; display: flex; }
        .sidebar { width: 250px; background: #2c3e50; height: 100vh; color: white; padding: 20px; position: fixed; }
        .main-content { margin-left: 290px; padding: 20px; width: 100%; }
        .card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #3498db; color: white; }
        img { width: 50px; height: 50px; border-radius: 5px; object-fit: cover; }
        .btn-add { background: #27ae60; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-bottom: 15px; }
        .logout { color: #e74c3c; text-decoration: none; font-weight: bold; display: block; margin-top: 50px; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Smart Dokon</h2>
    <p>Xush kelibsiz, <b><?php echo $_SESSION['admin_name']; ?></b></p>
    <hr>
    <nav style="display: flex; flex-direction: column; gap: 10px; margin-top: 20px;">
    <a href="admin_orders.php" style="color: white; text-decoration: none; padding: 10px; background: rgba(255,255,255,0.1); border-radius: 5px;">Bosh sahifa</a>
    <a href="admin_products.php" style="color: white; text-decoration: none; padding: 10px; background: rgba(255,255,255,0.1); border-radius: 5px;">Mahsulotlar</a>
    <a href="admin_orders_list.php" style="color: white; text-decoration: none; padding: 10px; background: rgba(255,255,255,0.1); border-radius: 5px;">Buyurtmalar</a>
    <a href="admin_cart.php" style="color: white; text-decoration: none; padding: 10px; background: rgba(255,255,255,0.1); border-radius: 5px;">Savat (Mijozlar)</a>
    <a href="settings.php" style="color: white; text-decoration: none; padding: 10px; background: rgba(255,255,255,0.1); border-radius: 5px;">Sozlamalar</a>
</nav>
    <a href="logout.php" class="logout">🚪 Chiqish</a>
</div>

<div class="main-content">
    <h1>Boshqaruv Paneli</h1>

    <!-- Mahsulotlar bo'limi -->
    <div class="card">
        <h3>📦 Mahsulotlar ro'yxati</h3>
        <a href="add_product.php" class="btn-add">+ Yangi mahsulot qo'shish</a>
        <table>
            <tr>
                <th>Rasm</th>
                <th>Nomi</th>
                <th>Narxi</th>
                <th>Amal</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($mahsulotlar)): ?>
            <tr>
                <td><img src="img/<?php echo $row['rasm']; ?>" alt=""></td>
                <td><?php echo $row['nomi']; ?></td>
                <td><?php echo number_format($row['narxi'], 0, '.', ' '); ?> so'm</td>
                <td><a href="#" style="color:red">O'chirish</a></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <!-- Buyurtmalar bo'limi (pastda chiqishi kerak degandingiz) -->
    <div class="card">
        <h3>🛒 Oxirgi Buyurtmalar</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Mijoz</th>
                <th>Holat</th>
            </tr>
            <tr>
                <td>#101</td>
                <td>Diyorbek</td>
                <td><span style="color:orange">Kutilmoqda</span></td>
            </tr>
        </table>
    </div>
</div>

</body>
</html>