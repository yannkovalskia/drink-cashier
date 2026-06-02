<?php
// File ini digunakan untuk membuat/memastikan tabel transaksi ada di database
include 'koneksi.php';

try {
    // Cek apakah tabel transaksi sudah ada
    $result = $pdo->query("SHOW TABLES LIKE 'transaksi'");
    
    if ($result->rowCount() === 0) {
        // Buat tabel transaksi
        $sql = "CREATE TABLE transaksi (
            id_transaksi INT PRIMARY KEY AUTO_INCREMENT,
            kode_transaksi VARCHAR(50) UNIQUE NOT NULL,
            tanggal_transaksi DATETIME DEFAULT CURRENT_TIMESTAMP,
            subtotal DECIMAL(12,2) NOT NULL,
            diskon DECIMAL(12,2) DEFAULT 0,
            total_bayar DECIMAL(12,2) NOT NULL,
            uang_bayar DECIMAL(12,2) NOT NULL,
            kembalian DECIMAL(12,2) NOT NULL,
            detail_produk LONGTEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        $pdo->exec($sql);
        echo "Tabel transaksi berhasil dibuat!";
    } else {
        echo "Tabel transaksi sudah ada!";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
