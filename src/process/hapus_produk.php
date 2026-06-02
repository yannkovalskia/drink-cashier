<?php
session_start();
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: halaman_login.php");
    exit;
}

include '../config/koneksi.php';

if (isset($_GET['kode'])) {
    $kode_produk = trim($_GET['kode']);
    
    try {
        // Ambil data gambar terlebih dahulu
        $stmt = $pdo->prepare("SELECT gambar_produk FROM produk WHERE kode_produk = :kode_produk");
        $stmt->execute(['kode_produk' => $kode_produk]);
        $produk = $stmt->fetch();
        
        if ($produk && $produk['gambar_produk']) {
            $file_path = '../../uploads/' . $produk['gambar_produk'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        
        // Hapus data produk
        $stmt = $pdo->prepare("DELETE FROM produk WHERE kode_produk = :kode_produk");
        $stmt->execute(['kode_produk' => $kode_produk]);
        
        $_SESSION['success_message'] = 'Produk berhasil dihapus!';
    } catch (PDOException $e) {
        $_SESSION['error_message'] = 'Gagal menghapus produk: ' . $e->getMessage();
    }
}

header("Location: ../pages/kelola_produk.php");
exit;
?>
