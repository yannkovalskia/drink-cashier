<?php
/**
 * DrinkCashier - Point of Sale System
 * Index/Router File
 * 
 * File ini mengarahkan request ke halaman login atau dashboard
 */

session_start();

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
    header("Location: src/pages/dashboard.php");
    exit;
}

// Jika belum login, redirect ke halaman login
header("Location: src/pages/halaman_login.php");
exit;
?>
