<?php
session_start();
session_destroy();
header("Location: ../pages/halaman_login.php");
exit;
?>
