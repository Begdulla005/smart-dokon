<?php
$host = "smartdokon.uz";
$user = "root";
$pass = "";
$dbname = "smart_dokon"; // Ma'lumotlar bazangiz nomi shu ekanligiga ishonch hosil qiling

// Mana shu $db o'zgaruvchisi hamma joyda ishlatiladi
$db = mysqli_connect($host, $user, $pass, $dbname);

if (!$db) {
    die("Bazaga ulanishda xato: " . mysqli_connect_error());
}

// O'zbek tili harflari (sh, ch, g') to'g'ri chiqishi uchun
mysqli_set_charset($db, "utf8mb4");
?>