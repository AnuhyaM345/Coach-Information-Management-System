<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['securitylevel'])) {
    header("Location: ../../login.php");
    exit();
}

$current_path = $_SERVER['PHP_SELF'];

if (strpos($current_path, 'ADMIN') !== false && $_SESSION['securitylevel'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

if (strpos($current_path, 'USER') !== false && $_SESSION['securitylevel'] != 'user') {
    header("Location: ../login.php");
    exit();
}
?>