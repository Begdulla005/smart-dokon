<?php
ob_start();
session_start();

// 1. Bazaga ulanish faylini chaqiramiz
include 'db.php'; 

// Xatolarni ko'rsatish (muammoni aniqlash uchun)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 2. Bazaga ulanish o'zgaruvchisini tekshirish
// Agar db.php ichida ulanish nomi $connect bo'lsa, uni $db ga tenglab olamiz
if (!isset($db) && isset($connect)) {
    $db = $connect;
}

// 3. Savat ma'lumotlarini olish (28-qator atrofida)
$query_text = "SELECT * FROM savat ORDER BY id DESC";
$res = mysqli_query($db, $query_text);

?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title>Savat holati</title>
    <style>
        body { font-family: sans-serif; background: #f4f7f6; padding: 20px; }
        .cart-container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .back-link { text-decoration: none; color: #3498db; margin-bottom: 20px; display: inline-block; }
    </style>
</head>
<body>

<div class="cart-container">
    <a href="index.php" class="back-link">← Do'konga qaytish</a>
    <h2>🛒 Savat holati va Tahrirlash</h2>

    <?php
    // 60-qator atrofidagi tekshiruv
    if ($res && mysqli_num_rows($res) > 0) {
        echo "<table border='1' width='100%' style='border-collapse: collapse;'>";
        echo "<tr><th>Nomi</th><th>Narxi</th><th>Amal</th></tr>";
        while($row = mysqli_fetch_assoc($res)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['nomi']) . "</td>";
            echo "<td>" . $row['narxi'] . " so'm</td>";
            echo "<td><a href='delete_cart.php?id=".$row['id']."'>O'chirish</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='text-align: center; margin-top: 50px;'>Savatda mahsulotlar yo'q.</p>";
    }
    ?>
</div>

</body>
</html>