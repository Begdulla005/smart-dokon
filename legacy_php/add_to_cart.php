$u_id = $_SESSION['user_id'];
$p_id = $_GET['product_id'];

// Agar savatda bo'lsa sonini oshiramiz, bo'lmasa yangi qo'shamiz
mysqli_query($db, "INSERT INTO cart (user_id, product_id, quantity) 
                  VALUES ('$u_id', '$p_id', 1) 
                  ON DUPLICATE KEY UPDATE quantity = quantity + 1");
header("Location: index.php");