<?php
ob_start();
session_start();
include 'db.php';

// Bazaga ulanishni tekshirish
if (isset($conn)) { $db = $conn; }
if (isset($con)) { $db = $con; }

// Adminlikni tekshirish (ixtiyoriy, agar faqat admin ko'rsin desangiz)
if (!isset($_SESSION['admin_log']) || $_SESSION['admin_log'] !== true) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title>Smart Dokon - Mahsulotlar</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f7f6; margin: 0; padding: 20px; }
        h1 { text-align: center; color: #333; margin-bottom: 30px; }
        .product-container { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); 
            gap: 25px; 
            max-width: 1200px;
            margin: 0 auto;
        }
        .product-card { 
            background: white; 
            padding: 20px; 
            border-radius: 15px; 
            box-shadow: 0 10px 20px rgba(0,0,0,0.05); 
            text-align: center;
            transition: 0.3s;
        }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
        .product-card img { 
            width: 100%; 
            height: 200px; 
            object-fit: contain; 
            border-radius: 10px; 
            margin-bottom: 15px;
        }
        .product-card h3 { margin: 10px 0; color: #444; font-size: 1.4em; }
        .price { color: #27ae60; font-weight: bold; font-size: 1.3em; margin: 10px 0; }
        .buy-btn {
            display: inline-block;
            background: #27ae60;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: 0.3s;
        }
        .buy-btn:hover { background: #2ecc71; }
    </style>
</head>
<body>

    <h1>Bizning Mahsulotlar</h1>

    <div class="product-container">
        <?php
        $res = mysqli_query($db, "SELECT * FROM mahsulotlar");
        // SIKL BOSHLANISHI
        while ($row = mysqli_fetch_assoc($res)) {
            $image_path = $row['rasm'];
            // Rasm yo'lini tekshirish (agar img/ bo'lmasa qo'shish)
            if (strpos($image_path, 'img/') === false) {
                $image_path = 'img/' . $image_path;
            }
        ?>
            <div class="product-card">
                <img src="<?php echo $image_path; ?>" alt="Mahsulot">
                <h3><?php echo $row['nomi']; ?></h3>
                <p class="price"><?php echo number_format($row['narxi'], 0, ',', ' '); ?> so'm</p>
                <a href="product_details.php?id=<?php echo $row['id']; ?>" class="buy-btn">Sotib olish</a>
            </div>
        <?php 
        } // SIKL SHU YERDA YOPILADI (Sizda balki shu tushib qolgan)
        ?>
    </div>

</body>
</html>
<?php
ob_end_flush();
?>