<?php
session_start();
include 'db.php';
if (isset($conn)) { $db = $conn; }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = $_POST['password'];

    // Faqat admin rolidagi foydalanuvchini izlash
    $res = mysqli_query($db, "SELECT * FROM users WHERE username = '$username' AND role = 'admin' LIMIT 1");
    $user = mysqli_fetch_assoc($res);

    // MD5 orqali tekshirish (OpenServer versiyangiz uchun)
    if ($user && md5($password) === $user['password']) {
        $_SESSION['admin_log'] = true;
        $_SESSION['admin_name'] = $user['username'];
        
        header("Location: admin_orders.php"); // To'g'ri admin panelga yuboramiz
        exit();
    } else {
        echo "<script>alert('Kirish taqiqlangan yoki ma'lumotlar xato!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title>Admin Kirish</title>
    <style>
        body { font-family: sans-serif; background: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 350px; text-align: center; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #27ae60; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Admin Panel</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Admin login" required>
            <input type="password" name="password" placeholder="Parol" required>
            <button type="submit">Kirish</button>
        </form>
    </div>
</body>
</html>