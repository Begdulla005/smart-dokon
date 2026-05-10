<?php
session_start();
include 'db.php';

// Admin tekshiruvi
if (!isset($_SESSION['admin_log'])) {
    header("Location: login.php");
    exit();
}

$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_settings'])) {
    // Bu yerda ma'lumotlarni bazaga saqlash kodini yozishingiz mumkin
    $message = "✅ Sozlamalar muvaffaqiyatli saqlandi!";
}
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title>Tizim Sozlamalari</title>
    <style>
        :root { --primary: #3498db; --bg: #f4f7f6; --text: #2c3e50; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: var(--bg); color: var(--text); padding: 20px; }
        .main-card { max-width: 850px; margin: auto; background: white; padding: 40px; border-radius: 15px; box-shadow: 0 5px 25px rgba(0,0,0,0.05); }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; }
        .section { background: #fff; border: 1px solid #edf2f7; padding: 20px; border-radius: 12px; margin-bottom: 20px; }
        .section h3 { margin-top: 0; font-size: 18px; color: var(--primary); border-bottom: 2px solid #f0f0f0; padding-bottom: 10px; margin-bottom: 15px; }
        label { display: block; margin: 10px 0 5px; font-weight: 600; font-size: 14px; color: #4a5568; }
        input, select { width: 100%; padding: 12px; border: 1.5px solid #e2e8f0; border-radius: 8px; box-sizing: border-box; font-size: 15px; }
        input:focus { border-color: var(--primary); outline: none; }
        .btn-submit { background: var(--primary); color: white; border: none; padding: 16px; width: 100%; border-radius: 10px; font-size: 16px; font-weight: bold; cursor: pointer; margin-top: 10px; transition: 0.3s; }
        .btn-submit:hover { background: #2980b9; transform: translateY(-1px); }
        .alert { background: #c6f6d5; color: #22543d; padding: 15px; border-radius: 8px; margin-bottom: 25px; border-left: 5px solid #38a169; }
        .back-link { text-decoration: none; color: #718096; font-size: 14px; margin-bottom: 20px; display: inline-block; }
    </style>
</head>
<body>

<div class="main-card">
    <a href="admin_orders.php" class="back-link">← Boshqaruv paneliga qaytish</a>
    <h2 style="margin-top: 0;">⚙️ Tizim Sozlamalari</h2>

    <?php if($message != "") echo "<div class='alert'>$message</div>"; ?>

    <form method="POST">
        <div class="grid">
            <!-- 1. Do'kon ish tartibi -->
            <div class="section">
                <h3>🕒 Ish vaqti va Holat</h3>
                <label>Do'kon holati:</label>
                <select name="shop_status">
                    <option value="open">✅ Ochiq (Mijozlar buyurtma bera oladi)</option>
                    <option value="closed">❌ Yopiq (Vaqtincha xizmat ko'rsatilmaydi)</option>
                </select>
                
                <label>Ish tartibi (masalan: 09:00 - 22:00):</label>
                <input type="text" name="work_time" value="09:00 - 22:00">
            </div>

            <!-- 2. Do'kon ma'lumotlari -->
            <div class="section">
                <h3>🏪 Do'kon ma'lumotlari</h3>
                <label>Do'kon nomi:</label>
                <input type="text" name="shop_name" value="Smart Dokon">
                
                <label>Telegram kanal yoki guruh (link):</label>
                <input type="text" name="tg_link" value="@smart_dokon_uz">
            </div>
        </div>

        <div class="grid">
            <!-- 3. Yetkazib berish -->
            <div class="section">
                <h3>🚚 Yetkazib berish</h3>
                <label>Shahar ichida yetkazib berish (so'm):</label>
                <input type="number" name="delivery_city" value="20000">
                
                <label>Viloyatlararo yetkazib berish (so'm):</label>
                <input type="number" name="delivery_region" value="45000">
            </div>

            <!-- 4. Xavfsizlik -->
            <div class="section">
                <h3>🔒 Xavfsizlik</h3>
                <label>Eski parol:</label>
                <input type="password" name="old_pass" placeholder="Amaldagi parolni kiriting">
                
                <label>Yangi parol:</label>
                <input type="password" name="new_pass" placeholder="Yangi parolni kiriting">
            </div>
        </div>

        <div class="section">
            <h3>📞 Aloqa uchun ma'lumotlar</h3>
            <div class="grid">
                <div>
                    <label>Asosiy telefon raqami:</label>
                    <input type="text" name="phone_1" value="+998 90 123 45 67">
                </div>
                <div>
                    <label>Qo'shimcha raqam:</label>
                    <input type="text" name="phone_2" value="+998 93 765 43 21">
                </div>
            </div>
        </div>

        <button type="submit" name="save_settings" class="btn-submit">💾 O'zgarishlarni saqlash</button>
    </form>
</div>

</body>
</html>