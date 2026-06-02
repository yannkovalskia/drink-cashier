<?php
$host     = "localhost";
$username = "root";
$password = "";
$database = "my_kasir";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    
} catch (PDOException $e) {
    die("Koneksi ke database gagal: " . $e->getMessage());
}
?>