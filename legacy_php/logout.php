<?php
session_start();
session_destroy(); // Sessiyani o'chiradi
header("Location: login.php");
exit();
?>