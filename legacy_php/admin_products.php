<?php
session_start();
include 'db.php';
if (isset($conn)) { $db = $conn; }

// Admin tekshiruvi
if (!isset($_SESSION['admin_log'])) {
    header("Location: login.php");
    exit();
}

// Mahsulotni o'chirish kodi
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($db, "DELETE FROM mahsulotlar WHERE id = $id");
    header("Location: admin_products.php");
}

$mahsulotlar = mysqli_query($db, "SELECT * FROM mahsulotlar");
?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title>Mahsulotlar Boshqaruvi</title>
    <style>
        body { font-family: sans-serif; background: #f4f7f6; margin: 0; padding: 20px; }
        .container { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #3498db; color: white; }
        img { width: 60px; height: 60px; object-fit: cover; border-radius: 5px; }
        .btn-add { background: #27ae60; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin-bottom: 20px; display: inline-block; }
        .btn-del { color: #e74c3c; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <h1>📦 Barcha mahsulotlar</h1>
    <a href="add_product.php" class="btn-add">+ Yangi mahsulot qo'shish</a>
    <a href="admin_orders.php" style="margin-left: 10px;">← Orqaga (Panelga)</a>

    <table>
        <tr>
            <th>Rasm</th>
            <th>Nomi</th>
            <th>Narxi</th>
            <th>Amal</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($mahsulotlar)): ?>
        <tr>
            <td><img src="img/<?php echo $row['rasm']; ?>" alt="foto"></td>
            <td><?php echo $row['nomi']; ?></td>
            <td><?php echo number_format($row['narxi'], 0, '.', ' '); ?> so'm</td>
            <td>
                <a href="admin_products.php?delete=<?php echo $row['id']; ?>" 
                   class="btn-del" onclick="return confirm('O\'chirasizmi?')">O'chirish</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>