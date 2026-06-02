<?php
session_start();
// Bersihkan data transaksi dari session
unset($_SESSION['transaction']);
echo json_encode(['success' => true]);
?>
