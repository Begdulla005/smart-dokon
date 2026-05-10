<?php
session_start();
include 'db.php';
if (isset($conn)) { $db = $conn; }

// Admin tekshiruvi
if (!isset($_SESSION['admin_log'])) {
    header("Location: login.php");
    exit();
}

// Buyurtmalarni bazadan olish (mijoz ismi bilan)
$query = "SELECT orders.*, users.username FROM orders 
          JOIN users ON orders.user_id = users.id 
          ORDER BY orders.id DESC";
$buyurtmalar = mysqli_query($db, $query);
?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title>Buyurtmalar Ro'yxati</title>
    <style>
        body { font-family: sans-serif; background: #f4f7f6; padding: 20px; }
        .container { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #e67e22; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .status-new { color: #27ae60; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <h1>🛒 Kelib tushgan buyurtmalar</h1>
    <a href="admin_orders.php">← Asosiy panelga qaytish</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Mijoz</th>
            <th>Mahsulotlar</th>
            <th>Umumiy summa</th>
            <th>Sana</th>
            <th>Holat</th>
        </tr>
        <?php while($order = mysqli_fetch_assoc($buyurtmalar)): ?>
        <tr>
            <td>#<?php echo $order['id']; ?></td>
            <td><?php echo $order['username']; ?></td>
            <td><?php echo $order['items']; ?></td>
            <td><?php echo number_format($order['total_price'], 0, '.', ' '); ?> so'm</td>
            <td><?php echo $order['created_at']; ?></td>
            <td class="status-new">Yangi</td>
        </tr>
        <?php endwhile; ?>
        
        <?php if(mysqli_num_rows($buyurtmalar) == 0): ?>
        <tr>
            <td colspan="6" style="text-align:center;">Hozircha buyurtmalar yo'q.</td>
        </tr>
        <?php endif; ?>
    </table>
</div>

</body>
</html>