<?php
include 'db.php';
if (isset($conn)) { $db = $conn; }

// Tanlangan mahsulot ID sini olamiz
$id = $_GET['id'];
$res = mysqli_query($db, "SELECT * FROM mahsulotlar WHERE id = $id");
$product = mysqli_fetch_assoc($res);
?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title><?php echo $product['nomi']; ?> - Buyurtma berish</title>
    <style>
        body { font-family: sans-serif; background: #f4f7f6; padding: 40px; }
        .order-form { 
            background: white; 
            max-width: 500px; 
            margin: 0 auto; 
            padding: 30px; 
            border-radius: 15px; 
            box-shadow: 0 10px 20px rgba(0,0,0,0.1); 
        }
        input, button { 
            width: 100%; 
            padding: 12px; 
            margin: 10px 0; 
            border: 1px solid #ddd; 
            border-radius: 8px; 
            box-sizing: border-box; 
        }
        button { background: #27ae60; color: white; border: none; cursor: pointer; font-weight: bold; }
        button:hover { background: #2ecc71; }
        img { width: 100%; height: 250px; object-fit: contain; border-radius: 10px; }
    </style>
</head>
<body>

<div class="order-form">
    <img src="img/<?php echo $product['rasm']; ?>" alt="">
    <h2><?php echo $product['nomi']; ?></h2>
    <p>Narxi: <b><?php echo number_format($product['narxi'], 0, ',', ' '); ?> so'm</b></p>
    <hr>
    <h3>Buyurtma berish</h3>
    <form action="send_order.php" method="POST">
        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
        <input type="text" name="client_name" placeholder="Ism-familiyangiz" required>
        <input type="text" name="client_phone" placeholder="Telefon raqamingiz" required>
        <button type="submit">Buyurtmani tasdiqlash</button>
    </form>
</div>

</body>
</html>