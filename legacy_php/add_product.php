<?php
session_start();
include 'db.php';
if (isset($conn)) { $db = $conn; }

// Admin tekshiruvi
if (!isset($_SESSION['admin_log'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomi = mysqli_real_escape_string($db, $_POST['nomi']);
    $narxi = $_POST['narxi'];
    
    // Rasm bilan ishlash
    $rasm_nomi = $_FILES['rasm']['name'];
    $tmp_nomi = $_FILES['rasm']['tmp_name'];
    $manzil = "img/" . $rasm_nomi;

    // Rasmni papkaga ko'chirish va bazaga yozish
    if (move_uploaded_file($tmp_nomi, $manzil)) {
        $query = "INSERT INTO mahsulotlar (nomi, narxi, rasm) VALUES ('$nomi', '$narxi', '$rasm_nomi')";
        if (mysqli_query($db, $query)) {
            header("Location: admin_orders.php"); // Orqaga qaytish
        }
    }
}
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title>Yangi mahsulot qo'shish</title>
    <style>
        body { font-family: sans-serif; background: #f4f7f6; display: flex; justify-content: center; padding-top: 50px; }
        .form-box { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); width: 400px; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #27ae60; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>Yangi mahsulot</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="nomi" placeholder="Mahsulot nomi" required>
            <input type="number" name="narxi" placeholder="Narxi (so'mda)" required>
            <label>Rasm tanlang:</label>
            <input type="file" name="rasm" accept="image/*" required>
            <button type="submit">Saqlash</button>
        </form>
        <br>
        <a href="admin_orders.php">← Orqaga qaytish</a>
    </div>
</body>
</html>