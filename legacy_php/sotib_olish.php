<?php
include 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$res = mysqli_query($connect, "SELECT * FROM mahsulotlar WHERE id = $id");
$m = mysqli_fetch_assoc($res);

if (!$m) { die("Mahsulot topilmadi!"); }

if (isset($_POST['order'])) {
    $ism = mysqli_real_escape_string($connect, $_POST['ism']);
    $tel = mysqli_real_escape_string($connect, $_POST['tel']);
    
    $sql = "INSERT INTO buyurtmalar (mahsulot_id, mijoz_ismi, telefon, holat) 
            VALUES ('$id', '$ism', '$tel', 'Yangi')";
    
    if (mysqli_query($connect, $sql)) {
        echo "<script>alert('Buyurtmangiz qabul qilindi!'); window.location.href='index.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title>Sotib olish</title>
    <style>
        body { font-family: sans-serif; background: #eef2f3; display: flex; justify-content: center; align-items: center; height: 100vh; margin:0; }
        .box { background: white; padding: 30px; border-radius: 15px; width: 350px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        button { background: #27ae60; color: white; border: none; padding: 15px; width: 100%; border-radius: 8px; cursor: pointer; font-size: 16px; }
    </style>
</head>
<body>
    <div class="box">
        <img src="<?php echo $m['rasm']; ?>" style="width: 120px; margin-bottom: 10px;">
        <h2><?php echo $m['nomi']; ?></h2>
        <form method="POST">
            <input type="text" name="ism" placeholder="Ismingiz" required>
            <input type="text" name="tel" placeholder="Telefoningiz" required>
            <button type="submit" name="order">Buyurtmani tasdiqlash</button>
        </form>
        <br><a href="index.php" style="color:#7f8c8d; text-decoration:none;">← Orqaga</a>
    </div>
</body>
</html>