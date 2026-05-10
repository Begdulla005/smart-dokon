<?php
// 1. Ma'lumotlar bazasiga ulanish (db.php dagi o'zgaruvchini tekshiring)
include 'db.php'; 

// Agar db.php ichida ulanish nomi $conn bo'lsa, pastdagi hamma $connect larni $conn ga almashtiring
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $res = mysqli_query($connect, "SELECT * FROM mahsulotlar WHERE id=$id");
    $product = mysqli_fetch_assoc($res);

    // Agar mahsulot topilmasa, admin panelga qaytarish
    if (!$product) {
        header("Location: admin.php");
        exit();
    }
}

// 2. MA'LUMOTLARNI YANGILASH (Saqlash tugmasi bosilganda)
if (isset($_POST['update_product'])) {
    $nomi = mysqli_real_escape_string($connect, $_POST['nomi']);
    $narxi = mysqli_real_escape_string($connect, $_POST['narxi']);
    $id = $_POST['id'];

    // Rasm bilan ishlash
    if (!empty($_FILES['rasm']['name'])) {
        $rasm_nomi = $_FILES['rasm']['name'];
        $rasm_vaqtincha = $_FILES['rasm']['tmp_name'];
        
        // Rasm nomini takrorlanmas qilish
        $yangi_nom = time() . "_" . $rasm_nomi;
        $rasm_yoli = "img/" . $yangi_nom;
        
        if (move_uploaded_file($rasm_vaqtincha, $rasm_yoli)) {
            // Yangi rasm yuklansa, eskisini o'chirish (agar u mavjud bo'lsa)
            if (!empty($product['rasm']) && file_exists($product['rasm'])) { 
                unlink($product['rasm']); 
            }
            $sql = "UPDATE mahsulotlar SET nomi='$nomi', narxi='$narxi', rasm='$rasm_yoli' WHERE id=$id";
        }
    } else {
        // Agar yangi rasm tanlanmasa, faqat nomi va narxini yangilash
        $sql = "UPDATE mahsulotlar SET nomi='$nomi', narxi='$narxi' WHERE id=$id";
    }

    if (mysqli_query($connect, $sql)) {
        // Muvaffaqiyatli yangilangach, admin panelga qaytish
        header("Location: admin.php");
        exit();
    } else {
        echo "Xatolik yuz berdi: " . mysqli_error($connect);
    }
}
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mahsulotni tahrirlash</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f7f6; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .edit-container { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 450px; }
        h2 { color: #2c3e50; text-align: center; margin-bottom: 20px; }
        label { display: block; margin-top: 15px; color: #7f8c8d; font-size: 14px; }
        input[type="text"], input[type="number"], input[type="file"] { width: 100%; padding: 12px; margin-top: 5px; border: 1px solid #dcdde1; border-radius: 8px; box-sizing: border-box; }
        .current-img { width: 80px; height: 80px; object-fit: cover; border-radius: 8px; margin: 10px 0; border: 2px solid #eee; }
        .btn-group { margin-top: 25px; }
        .btn-save { width: 100%; padding: 13px; background: #3498db; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: bold; transition: 0.3s; }
        .btn-save:hover { background: #2980b9; }
        .btn-back { display: block; text-align: center; margin-top: 15px; color: #95a5a6; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>

<div class="edit-container">
    <h2>📝 Tahrirlash</h2>
    
    <form action="edit.php?id=<?php echo $product['id']; ?>" method="POST" enctype="multipart/form-data">
        <!-- Yashirin ID -->
        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">

        <label>Mahsulot nomi:</label>
        <input type="text" name="nomi" value="<?php echo htmlspecialchars($product['nomi']); ?>" required>

        <label>Narxi (so'm):</label>
        <input type="number" name="narxi" value="<?php echo $product['narxi']; ?>" required>

        <label>Hozirgi rasm:</label>
        <?php if (!empty($product['rasm'])): ?>
            <img src="<?php echo $product['rasm']; ?>" class="current-img" alt="Mahsulot rasmi">
        <?php else: ?>
            <p style="font-size: 12px; color: red;">Rasm yuklanmagan</p>
        <?php endif; ?>

        <label>Yangi rasm yuklash (ixtiyoriy):</label>
        <input type="file" name="rasm" accept="image/*">

        <div class="btn-group">
            <button type="submit" name="update_product" class="btn-save">O'zgarishlarni saqlash</button>
            <a href="admin.php" class="btn-back">← Orqaga qaytish</a>
        </div>
    </form>
</div>

</body>
</html>