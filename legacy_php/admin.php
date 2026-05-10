<?php
ob_start(); 
session_start();

// 1. Kirishni tekshirish
if (!isset($_SESSION['admin_log']) || $_SESSION['admin_log'] !== true) {
    header("Location: login.php");
    exit();
}

// 2. Bazaga ulanish
include 'db.php';

// 3. Xatolarni ko'rsatish
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Smart Dokon</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; background: #f4f7f6; color: #333; }
        nav { background: #2c3e50; padding: 15px 50px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        nav .links a { color: white; margin-right: 20px; text-decoration: none; font-weight: bold; }
        nav .links a:hover { color: #3498db; }
        .logout { color: #e74c3c; text-decoration: none; font-weight: bold; border: 1px solid #e74c3c; padding: 5px 15px; border-radius: 4px; transition: 0.3s; }
        .logout:hover { background: #e74c3c; color: white; }
        
        .container { max-width: 1000px; margin: 30px auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        h2, h3 { color: #2c3e50; margin-top: 0; }
        
        form { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; background: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #eee; }
        form input, form button { padding: 12px; border-radius: 5px; border: 1px solid #ddd; font-size: 16px; }
        form input[type="file"] { grid-column: span 2; background: white; }
        form button { grid-column: span 2; background: #2ecc71; color: white; border: none; font-weight: bold; cursor: pointer; transition: 0.3s; }
        form button:hover { background: #27ae60; }

        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table th { background: #34495e; color: white; padding: 12px; text-align: left; }
        table td { padding: 12px; border-bottom: 1px solid #eee; }
        table tr:hover { background: #fcfcfc; }
        
        /* Tugmalar uchun maxsus uslublar */
        .del-btn { color: #e74c3c; text-decoration: none; font-size: 14px; border: 1px solid #e74c3c; padding: 3px 8px; border-radius: 4px; transition: 0.3s; }
        .del-btn:hover { background: #e74c3c; color: white; }
        
        .edit-btn { color: #3498db; text-decoration: none; font-size: 14px; border: 1px solid #3498db; padding: 3px 8px; border-radius: 4px; transition: 0.3s; margin-right: 5px; }
        .edit-btn:hover { background: #3498db; color: white; }

        .buy-btn { color: #2ecc71; text-decoration: none; font-size: 14px; border: 1px solid #2ecc71; padding: 3px 8px; border-radius: 4px; transition: 0.3s; margin-right: 5px; }
        .buy-btn:hover { background: #2ecc71; color: white; }
    </style>
</head>
<body>

<nav>
    <div class="links">
        <a href="admin.php?tab=mahsulotlar">🛒 Mahsulotlar</a>
        <a href="admin.php?tab=buyurtmalar">📦 Buyurtmalar</a>
    </div>
    <a href="logout.php" class="logout">Chiqish</a>
</nav>

<div class="container">
    <?php
    $tab = isset($_GET['tab']) ? $_GET['tab'] : 'mahsulotlar';

    if ($tab == 'buyurtmalar'): ?>
        <h2>📦 Buyurtmalar ro'yxati</h2>
        <p>Hozircha buyurtmalar mavjud emas.</p>

    <?php else: ?>
        <h2>🛠 Mahsulotlar boshqaruvi</h2>
        
        <div style="margin-bottom: 40px;">
            <h3>Yangi mahsulot qo'shish</h3>
            <form action="add_product.php" method="POST" enctype="multipart/form-data">
                <input type="text" name="nomi" placeholder="Mahsulot nomi" required>
                <input type="number" name="narxi" placeholder="Narxi (so'mda)" required>
                <input type="file" name="rasm" accept="image/*" required>
                <button type="submit">Qo'shish</button>
            </form>
        </div>

        <h3>Mavjud mahsulotlar</h3>
        <table>
            <thead>
                <tr>
                    <th>Rasm</th>
                    <th>Nomi</th>
                    <th>Narxi</th>
                    <th style="text-align: center;">Amal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = mysqli_query($db, "SELECT * FROM mahsulotlar ORDER BY id DESC");
                if(mysqli_num_rows($query) > 0){
                    while($row = mysqli_fetch_assoc($query)):
                ?>
                <tr>
                    <td><img src="img/<?php echo $row['rasm']; ?>" width="60" style="border-radius: 4px;"></td>
                    <td style="font-weight: 500;"><?php echo htmlspecialchars($row['nomi']); ?></td>
                    <td style="color: #27ae60; font-weight: bold;"><?php echo number_format($row['narxi'], 0, '', ' '); ?> so'm</td>
                    
                    <td style="text-align: center;">
                        <!-- SOTIB OLISH TUGMASI -->
                        <a href="add_to_cart.php?id=<?php echo $row['id']; ?>" class="buy-btn">Sotib olish</a>

                        <!-- TAHRIRLASH TUGMASI -->
                        <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="edit-btn">Tahrirlash</a>

                        <!-- O'CHIRISH TUGMASI -->
                        <a href="delete.php?id=<?php echo $row['id']; ?>" class="del-btn" onclick="return confirm('Rostdan ham o\'chirmoqchimisiz?')">O'chirish</a>
                    </td>
                </tr>
                <?php 
                    endwhile; 
                } else {
                    echo "<tr><td colspan='4' style='text-align:center;'>Mahsulotlar hali qo'shilmagan.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
<?php ob_end_flush(); ?>