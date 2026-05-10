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

// 3. Mahsulot ma'lumotlarini olish
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($db, $_GET['id']);
    $res = mysqli_query($db, "SELECT * FROM mahsulotlar WHERE id = '$id'");
    $product = mysqli_fetch_assoc($res);

    if (!$product) {
        die("Mahsulot topilmadi!");
    }
} else {
    header("Location: admin.php");
    exit();
}

// 4. Ma'lumotlarni yangilash (Saqlash tugmasi bosilganda)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nomi = mysqli_real_escape_string($db, $_POST['nomi']);
    $narxi = mysqli_real_escape_string($db, $_POST['narxi']);
    
    // Rasm yuklash mantiqi
    if (!empty($_FILES['rasm']['name'])) {
        $rasm_nomi = time() . "_" . $_FILES['rasm']['name'];
        move_uploaded_file($_FILES['rasm']['tmp_name'], "img/" . $rasm_nomi);
        $update_query = "UPDATE mahsulotlar SET nomi='$nomi', narxi='$narxi', rasm='$rasm_nomi' WHERE id='$id'";
    } else {
        $update_query = "UPDATE mahsulotlar SET nomi='$nomi', narxi='$narxi' WHERE id='$id'";
    }

    if (mysqli_query($db, $update_query)) {
        header("Location: admin.php?status=updated");
        exit();
    } else {
        $error = "Xatolik yuz berdi: " . mysqli_error($db);
    }
}
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title>Mahsulotni tahrirlash</title>
    <style>
        body { font-family: sans-serif; background: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .edit-box { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); width: 400px; }
        h2 { margin-top: 0; color: #2c3e50; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        .save-btn { background: #3498db; color: white; border: none; cursor: pointer; font-weight: bold; }
        .save-btn:hover { background: #2980b9; }
        .cancel-link { display: block; text-align: center; margin-top: 15px; color: #7f8c8d; text-decoration: none; }
    </style>
</head>
<body>

<div class="edit-box">
    <h2>Tahrirlash</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <label>Mahsulot nomi:</label>
        <input type="text" name="nomi" value="<?php echo htmlspecialchars($product['nomi']); ?>" required>
        
        <label>Narxi (so'm):</label>
        <input type="number" name="narxi" value="<?php echo $product['narxi']; ?>" required>
        
        <label>Rasm (agar o'zgartirmoqchi bo'lsangiz):</label>
        <input type="file" name="rasm" accept="image/*">
        <p><small>Hozirgi rasm: <?php echo $product['rasm']; ?></small></p>
        
        <button type="submit" class="save-btn">O'zgarishlarni saqlash</button>
        <a href="admin.php" class="cancel-link">Bekor qilish</a>
    </form>
</div>

</body>
</html>