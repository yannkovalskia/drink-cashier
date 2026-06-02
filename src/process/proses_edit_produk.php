<?php
session_start();
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: halaman_login.php");
    exit;
}

include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_produk      = trim($_POST['kode_produk']);
    $nama_produk      = trim($_POST['nama_produk']);
    $id_kategori      = !empty($_POST['id_kategori']) ? $_POST['id_kategori'] : null;
    $harga_produk     = intval($_POST['harga_produk']);
    $jumlah_stok      = intval($_POST['jumlah_stok']);
    $status_produk    = $_POST['status_produk'];
    $deskripsi_produk = trim($_POST['deskripsi_produk']);
    
    $nama_gambar_baru = null;
    
    // Handle gambar upload jika ada
    if (isset($_FILES['gambar_produk']) && $_FILES['gambar_produk']['error'] === 0) {
        $file_tmp  = $_FILES['gambar_produk']['tmp_name'];
        $file_name = $_FILES['gambar_produk']['name'];
        $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
        
        if (in_array($file_ext, $allowed_ext)) {
            $nama_gambar_baru = time() . '_' . uniqid() . '.' . $file_ext;
            
            $folder_tujuan = '../../uploads/' . $nama_gambar_baru;
            
            if (!move_uploaded_file($file_tmp, $folder_tujuan)) {
                echo "<script>alert('Gagal mengupload gambar ke server.'); window.history.back();</script>";
                exit;
            }
            
            // Hapus gambar lama jika ada
            try {
                $stmt = $pdo->prepare("SELECT gambar_produk FROM produk WHERE kode_produk = :kode_produk");
                $stmt->execute(['kode_produk' => $kode_produk]);
                $produk_lama = $stmt->fetch();
                
                if ($produk_lama && $produk_lama['gambar_produk']) {
                    $file_lama = '../../uploads/' . $produk_lama['gambar_produk'];
                    if (file_exists($file_lama)) {
                        unlink($file_lama);
                    }
                }
            } catch (PDOException $e) {
                // Ignore error saat hapus gambar lama
            }
        } else {
            echo "<script>alert('Format gambar tidak valid!'); window.history.back();</script>";
            exit;
        }
    }

    // Update Data di Database
    try {
        if ($nama_gambar_baru) {
            // Update dengan gambar baru
            $sql = "UPDATE produk SET 
                    nama_produk = :nama_produk, 
                    id_kategori = :id_kategori, 
                    harga_produk = :harga_produk, 
                    jumlah_stok = :jumlah_stok, 
                    status_produk = :status_produk, 
                    deskripsi_produk = :deskripsi_produk, 
                    gambar_produk = :gambar_produk
                    WHERE kode_produk = :kode_produk";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'nama_produk'      => $nama_produk,
                'id_kategori'      => $id_kategori,
                'harga_produk'     => $harga_produk,
                'jumlah_stok'      => $jumlah_stok,
                'status_produk'    => $status_produk,
                'deskripsi_produk' => $deskripsi_produk,
                'gambar_produk'    => $nama_gambar_baru,
                'kode_produk'      => $kode_produk
            ]);
        } else {
            // Update tanpa gambar
            $sql = "UPDATE produk SET 
                    nama_produk = :nama_produk, 
                    id_kategori = :id_kategori, 
                    harga_produk = :harga_produk, 
                    jumlah_stok = :jumlah_stok, 
                    status_produk = :status_produk, 
                    deskripsi_produk = :deskripsi_produk
                    WHERE kode_produk = :kode_produk";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'nama_produk'      => $nama_produk,
                'id_kategori'      => $id_kategori,
                'harga_produk'     => $harga_produk,
                'jumlah_stok'      => $jumlah_stok,
                'status_produk'    => $status_produk,
                'deskripsi_produk' => $deskripsi_produk,
                'kode_produk'      => $kode_produk
            ]);
        }

        $_SESSION['success_message'] = 'Produk berhasil diperbarui!';
        header("Location: ../pages/kelola_produk.php");
        exit;

    } catch (PDOException $e) {
        die("Gagal memperbarui database: " . $e->getMessage());
    }
}
?>
