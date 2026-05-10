<?php
include 'db.php';
if (isset($conn)) { $db = $conn; }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $name = mysqli_real_escape_string($db, $_POST['client_name']);
    $phone = mysqli_real_escape_string($db, $_POST['client_phone']);

    // Buyurtmani bazaga yozish
    $query = "INSERT INTO buyurtmalar (mahsulot_id, mijoz_ismi, mijoz_tel) 
              VALUES ('$product_id', '$name', '$phone')";

    if (mysqli_query($db, $query)) {
        echo "<div style='text-align:center; padding:50px; font-family:sans-serif;'>
                <h2 style='color: #27ae60;'>Rahmat! Buyurtmangiz qabul qilindi.</h2>
                <p>Tez orada siz bilan bog'lanamiz.</p>
                <a href='index.php' style='color:#3498db; text-decoration:none;'>Asosiy sahifaga qaytish</a>
              </div>";
    } else {
        echo "Xatolik yuz berdi: " . mysqli_error($db);
    }
}
?>