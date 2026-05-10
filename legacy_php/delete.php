<?php
session_start();
include 'db.php';

// 1. Foydalanuvchi admin ekanligini tekshiramiz
if (!isset($_SESSION['admin_log']) || $_SESSION['admin_log'] !== true) {
    header("Location: login.php");
    exit();
}

// 2. ID kelganini tekshiramiz
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($db, $_GET['id']);

    // 3. Avval rasmni papkadan o'chirib tashlash uchun rasm nomini olamiz
    $query = mysqli_query($db, "SELECT rasm FROM mahsulotlar WHERE id = '$id'");
    $row = mysqli_fetch_assoc($query);
    
    if ($row) {
        $rasm_nomi = $row['rasm'];
        $path = "img/" . $rasm_nomi;

        // Agar fayl papkada bo'lsa, uni o'chiramiz
        if (file_exists($path)) {
            unlink($path);
        }

        // 4. Ma'lumotlar bazasidan qatorni o'chiramiz
        $delete_sql = "DELETE FROM mahsulotlar WHERE id = '$id'";
        if (mysqli_query($db, $delete_sql)) {
            // Muvaffaqiyatli o'chirilgach, admin panelga qaytamiz
            header("Location: admin.php?status=deleted");
            exit();
        } else {
            echo "Xatolik yuz berdi: " . mysqli_error($db);
        }
    }
} else {
    // Agar ID bo'lmasa, shunchaki qaytarib yuboramiz
    header("Location: admin.php");
    exit();
}
?>