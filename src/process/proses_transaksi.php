<?php
session_start();
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Content-Type: application/json");
    echo json_encode(['success' => false, 'message' => 'Anda harus login terlebih dahulu']);
    exit;
}

include '../config/koneksi.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['cart']) || !isset($input['subtotal'])) {
    header("Content-Type: application/json");
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
    exit;
}

try {
    // Generate invoice ID
    $invoiceId = 'INV-' . date('YmdHis') . '-' . rand(1000, 9999);
    
    // Simpan data transaksi ke session
    $_SESSION['transaction'] = [
        'invoice_id' => $invoiceId,
        'tanggal' => date('Y-m-d H:i:s'),
        'cart' => $input['cart'],
        'subtotal' => $input['subtotal'],
        'diskon' => 0, // Bisa ditambahkan fitur diskon
        'uang_bayar' => $input['uangBayar'],
        'kembalian' => $input['kembalian'],
        'kasir' => $_SESSION['username'] ?? 'Admin'
    ];
    
    // Simpan juga ke database untuk riwayat
    $cartJson = json_encode($input['cart']);
    $query = $pdo->prepare("
        INSERT INTO transaksi (
            kode_transaksi, 
            tanggal_transaksi, 
            subtotal, 
            diskon, 
            total_bayar, 
            uang_bayar, 
            kembalian, 
            detail_produk
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $query->execute([
        $invoiceId,
        date('Y-m-d H:i:s'),
        $input['subtotal'],
        0,
        $input['subtotal'],
        $input['uangBayar'],
        $input['kembalian'],
        $cartJson
    ]);
    
    header("Content-Type: application/json");
    echo json_encode([
        'success' => true,
        'invoice_id' => $invoiceId,
        'message' => 'Pembayaran berhasil diproses'
    ]);
    
} catch (Exception $e) {
    header("Content-Type: application/json");
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
