<?php
session_start();
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: halaman_login.php");
    exit;
}

include 'koneksi.php';

// Informasi sistem
$systemInfo = [
    'status' => '✅ SISTEM SIAP DIGUNAKAN',
    'checks' => []
];

// 1. Cek koneksi database
try {
    $result = $pdo->query("SELECT COUNT(*) as count FROM produk");
    $produkCount = $result->fetch()['count'];
    $systemInfo['checks']['Database'] = "✅ Terhubung ($produkCount produk)";
} catch (Exception $e) {
    $systemInfo['checks']['Database'] = "❌ Error: " . $e->getMessage();
}

// 2. Cek tabel produk
try {
    $result = $pdo->query("SHOW TABLES LIKE 'produk'");
    if ($result->rowCount() > 0) {
        $systemInfo['checks']['Tabel Produk'] = "✅ Ada";
    } else {
        $systemInfo['checks']['Tabel Produk'] = "❌ Tidak ada";
    }
} catch (Exception $e) {
    $systemInfo['checks']['Tabel Produk'] = "❌ Error";
}

// 3. Cek tabel transaksi
try {
    $result = $pdo->query("SHOW TABLES LIKE 'transaksi'");
    if ($result->rowCount() > 0) {
        $systemInfo['checks']['Tabel Transaksi'] = "✅ Ada";
        // Cek jumlah transaksi
        $transCount = $pdo->query("SELECT COUNT(*) as count FROM transaksi")->fetch()['count'];
        $systemInfo['checks']['Tabel Transaksi'] .= " ($transCount transaksi tercatat)";
    } else {
        $systemInfo['checks']['Tabel Transaksi'] = "⚠️ Belum dibuat - Jalankan: setup_database.php";
    }
} catch (Exception $e) {
    $systemInfo['checks']['Tabel Transaksi'] = "❌ Error";
}

// 4. Cek file penting
$requiredFiles = [
    'transaksi.php' => 'Halaman utama transaksi',
    'struk.php' => 'Halaman struk pembayaran',
    'proses_transaksi.php' => 'API proses pembayaran',
    'bersihkan_session.php' => 'Pembersih session',
    'setup_database.php' => 'Setup database'
];

foreach ($requiredFiles as $file => $desc) {
    if (file_exists($file)) {
        $systemInfo['checks']["File: $file"] = "✅ Ada ($desc)";
    } else {
        $systemInfo['checks']["File: $file"] = "❌ Tidak ada ($desc)";
    }
}

// 5. Cek produk dengan status aktif
try {
    $result = $pdo->query("SELECT COUNT(*) as count FROM produk WHERE status_produk = 'Aktif'");
    $activeCount = $result->fetch()['count'];
    if ($activeCount > 0) {
        $systemInfo['checks']['Produk Aktif'] = "✅ $activeCount produk siap dijual";
    } else {
        $systemInfo['checks']['Produk Aktif'] = "⚠️ Tidak ada produk aktif";
    }
} catch (Exception $e) {
    $systemInfo['checks']['Produk Aktif'] = "❌ Error";
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Check - DrinkCashier</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #0f172a;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            color: white;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(circle at top left, #ff7b00 0%, transparent 25%),
                radial-gradient(circle at bottom right, #ff006e 0%, transparent 25%),
                radial-gradient(circle at center, #00d4ff 0%, transparent 30%);
            opacity: 0.18;
            z-index: -2;
        }

        .container {
            width: 100%;
            max-width: 800px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border-radius: 35px;
            padding: 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            font-size: 36px;
            margin-bottom: 10px;
            color: #ffb347;
        }

        .header p {
            color: rgba(255, 255, 255, 0.6);
        }

        .status-badge {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 20px;
            background: linear-gradient(135deg, #ff8a00, #ff0058);
            margin: 20px 0;
            font-weight: 600;
            box-shadow: 0 10px 25px rgba(255, 100, 0, 0.25);
        }

        .checks-list {
            list-style: none;
        }

        .check-item {
            background: rgba(255, 255, 255, 0.05);
            border-left: 4px solid #ffb347;
            padding: 16px 20px;
            margin-bottom: 12px;
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .check-label {
            font-weight: 500;
            font-size: 14px;
        }

        .check-value {
            text-align: right;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
        }

        .info-box {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 24px;
            margin-top: 30px;
        }

        .info-box h3 {
            color: #ffb347;
            margin-bottom: 16px;
            font-size: 18px;
        }

        .info-box p, .info-box li {
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.8;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .info-box ul {
            margin-left: 20px;
        }

        .btn-group {
            display: flex;
            gap: 12px;
            margin-top: 30px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 18px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #ff8a00, #ff0058);
            color: white;
            box-shadow: 0 10px 25px rgba(255, 100, 0, 0.25);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .warning {
            background: rgba(255, 193, 7, 0.1);
            border-left-color: #ffc107;
        }

        .error {
            background: rgba(244, 67, 54, 0.1);
            border-left-color: #f44336;
        }

        .success {
            background: rgba(76, 175, 80, 0.1);
            border-left-color: #4caf50;
        }

        code {
            background: rgba(0, 0, 0, 0.3);
            padding: 4px 8px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
        }
    </style>
</head>
<body>

    <div class="container">

        <div class="header">
            <h1>🔍 System Check</h1>
            <p>Verifikasi konfigurasi dan status sistem</p>
        </div>

        <div style="text-align: center;">
            <div class="status-badge">
                <?php echo $systemInfo['status']; ?>
            </div>
        </div>

        <ul class="checks-list">
            <?php foreach ($systemInfo['checks'] as $label => $value): ?>
                <li class="check-item <?php echo (strpos($value, '❌') !== false) ? 'error' : (strpos($value, '⚠️') !== false) ? 'warning' : 'success'; ?>">
                    <span class="check-label"><?php echo $label; ?></span>
                    <span class="check-value"><?php echo $value; ?></span>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="info-box">
            <h3>📋 Panduan Setup</h3>
            <p><strong>Jika Anda melihat ⚠️ pada "Tabel Transaksi":</strong></p>
            <ul>
                <li>Buka browser dan akses: <code>http://localhost/xampp/htdocs/tugas%20akhir/setup_database.php</code></li>
                <li>Tunggu pesan konfirmasi "Tabel transaksi berhasil dibuat!"</li>
                <li>Kembali ke halaman ini untuk verifikasi ulang</li>
                <li>Setelah itu, sistem siap digunakan</li>
            </ul>
        </div>

        <div class="info-box">
            <h3>🚀 Quick Links</h3>
            <ul>
                <li><strong>Halaman Transaksi:</strong> Mulai membuat transaksi</li>
                <li><strong>Setup Database:</strong> Jalankan jika perlu membuat tabel</li>
                <li><strong>Dokumentasi:</strong> Baca panduan lengkap sistem</li>
            </ul>
        </div>

        <div class="btn-group">
            <a href="setup_database.php" class="btn btn-secondary">
                <i class="ri-database-2-line"></i>
                Setup Database
            </a>
            <a href="transaksi.php" class="btn btn-primary">
                <i class="ri-shopping-cart-2-line"></i>
                Ke Halaman Transaksi
            </a>
            <a href="DOKUMENTASI_TRANSAKSI.md" target="_blank" class="btn btn-secondary">
                <i class="ri-file-text-line"></i>
                Dokumentasi
            </a>
        </div>

    </div>

</body>
</html>
