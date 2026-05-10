<?php
session_start();
include 'db.php';

// 1. MAHSULOTNI O'CHIRISH LOGIKASI
if (isset($_GET['delete'])) {
    // ID ni xavfsiz holatga keltiramiz (faqat raqamligini tekshiramiz)
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    
    // DIQQAT: Jadval nomini tekshiring! 
    // Agar bu admin paneli bo'lsa 'cart' emas, 'products' bo'lishi kerak.
    $delete_query = "DELETE FROM products WHERE id = '$id'"; 
    
    if (mysqli_query($conn, $delete_query)) {
        header("Location: products_list.php"); // O'z faylingiz nomini yozing
        exit();
    } else {
        echo "Xatolik: " . mysqli_error($conn);
    }
}

// 2. MIQDORNI TAHRIRLASH
if (isset($_POST['update_update_btn'])) {
    $update_value = mysqli_real_escape_string($conn, $_POST['update_quantity']);
    $update_id = mysqli_real_escape_string($conn, $_POST['update_quantity_id']);
    
    $update_query = "UPDATE products SET quantity = '$update_value' WHERE id = '$update_id'";
    if (mysqli_query($conn, $update_query)) {
        header("Location: products_list.php");
        exit();
    }
}

$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);
?>

<!-- HTML qismi o'sha-o'sha qoladi, faqat 'O'chirish' tugmasi hrefini tekshiring -->
<td>
    <a href="products_list.php?delete=<?php echo $row['id']; ?>" 
       onclick="return confirm('Haqiqatan ham o\'chirmoqchimisiz?')" 
       class="btn-delete">O'chirish</a>
</td>