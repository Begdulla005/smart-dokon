<?php
include 'db.php';
if (isset($conn)) { $db = $conn; }

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = mysqli_real_escape_string($db, $_POST['fullname']);
    $username = mysqli_real_escape_string($db, $_POST['username']);
    // Eskicha usul (password_hash o'rniga md5 ishlatish)
$password = md5($_POST['password']);

    // Username bandligini tekshirish
    $check = mysqli_query($db, "SELECT id FROM users WHERE username = '$username'");
    if (mysqli_num_rows($check) > 0) {
        $message = "<p style='color:red;'>Bu username band, boshqasini tanlang!</p>";
    } else {
        $query = "INSERT INTO users (fullname, username, password) VALUES ('$fullname', '$username', '$password')";
        if (mysqli_query($db, $query)) {
            $message = "<p style='color:green;'>Ro'yxatdan muvaffaqiyatli o'tdingiz! <a href='login.php'>Kirish</a></p>";
        } else {
            $message = "Xatolik: " . mysqli_error($db);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title>Ro'yxatdan o'tish</title>
    <style>
        body { font-family: sans-serif; background: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .reg-box { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 350px; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #3498db; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; }
        button:hover { background: #2980b9; }
    </style>
</head>
<body>
    <div class="reg-box">
        <h2>Ro'yxatdan o'tish</h2>
        <?php echo $message; ?>
        <form method="POST">
            <input type="text" name="fullname" placeholder="To'liq ismingiz" required>
            <input type="text" name="username" placeholder="Username (login)" required>
            <input type="password" name="password" placeholder="Parol" required>
            <button type="submit">Ro'yxatdan o'tish</button>
        </form>
        <p style="text-align:center;">Akkauntingiz bormi? <a href="login.php">Kirish</a></p>
    </div>
</body>
</html>