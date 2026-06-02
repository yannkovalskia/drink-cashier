<?php
session_start();
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: halaman_login.php");
    exit;
}

// Ambil data transaksi dari session
$transaction = $_SESSION['transaction'] ?? null;

// Jika tidak ada data transaksi di session, redirect ke transaksi
if (!$transaction) {
    header("Location: transaksi.php");
    exit;
}

// Format data untuk ditampilkan
$invoiceId = $transaction['invoice_id'];
$tanggalTransaksi = $transaction['tanggal'];
$cart = $transaction['cart'];
$subtotal = $transaction['subtotal'];
$diskon = $transaction['diskon'] ?? 0;
$totalBayar = $subtotal - $diskon;
$uangBayar = $transaction['uang_bayar'];
$kembalian = $transaction['kembalian'];
$kasir = $transaction['kasir'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Struk Pembayaran - DrinkCashier</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">

  <style>

    *{
      margin:0;
      padding:0;
      box-sizing:border-box;
      font-family:'Poppins',sans-serif;
    }

    body{
      background:#0f172a;
      min-height:100vh;
      display:flex;
      justify-content:center;
      align-items:center;
      padding:40px 20px;
      overflow-x:hidden;
      color:white;
    }

    body::before{
      content:'';
      position:fixed;
      inset:0;
      background:
      radial-gradient(circle at top left,#ff7b00 0%,transparent 25%),
      radial-gradient(circle at bottom right,#ff006e 0%,transparent 25%),
      radial-gradient(circle at center,#00d4ff 0%,transparent 30%);
      opacity:0.18;
      animation:bgMove 10s infinite alternate ease-in-out;
      z-index:-2;
    }

    @keyframes bgMove{
      from{
        transform:scale(1) rotate(0deg);
      }
      to{
        transform:scale(1.08) rotate(4deg);
      }
    }

    .invoice-container{
      width:100%;
      max-width:850px;
      background:rgba(255,255,255,0.08);
      border:1px solid rgba(255,255,255,0.08);
      backdrop-filter:blur(20px);
      border-radius:35px;
      padding:40px;
      position:relative;
      overflow:hidden;
    }

    .invoice-container::before{
      content:'';
      position:absolute;
      width:250px;
      height:250px;
      border-radius:50%;
      background:rgba(255,255,255,0.05);
      top:-120px;
      right:-120px;
    }

    /* HEADER */
    .invoice-header{
      display:flex;
      justify-content:space-between;
      align-items:center;
      flex-wrap:wrap;
      gap:20px;
      margin-bottom:35px;
    }

    .store-info{
      display:flex;
      align-items:center;
      gap:18px;
    }

    .logo{
      width:80px;
      height:80px;
      border-radius:22px;
      background:linear-gradient(135deg,#ff8a00,#ff0058);
      display:flex;
      justify-content:center;
      align-items:center;
      font-size:38px;
      box-shadow:0 10px 30px rgba(255,100,0,0.35);
    }

    .store-text h1{
      font-size:32px;
      margin-bottom:8px;
    }

    .store-text p{
      color:rgba(255,255,255,0.6);
      line-height:1.7;
      font-size:14px;
    }

    .invoice-code{
      text-align:right;
    }

    .invoice-code h2{
      font-size:28px;
      margin-bottom:10px;
      color:#ffb347;
    }

    .invoice-code p{
      color:rgba(255,255,255,0.6);
      line-height:1.8;
      font-size:14px;
    }

    /* CUSTOMER */
    .customer-box{
      background:rgba(255,255,255,0.05);
      border-radius:25px;
      padding:25px;
      margin-bottom:35px;
      border:1px solid rgba(255,255,255,0.06);
    }

    .customer-box h3{
      margin-bottom:18px;
      color:#ffb347;
      font-size:20px;
    }

    .customer-info{
      display:grid;
      grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
      gap:18px;
    }

    .customer-item p{
      color:rgba(255,255,255,0.5);
      font-size:13px;
      margin-bottom:8px;
    }

    .customer-item h4{
      font-size:16px;
    }

    /* TABLE */
    .table-box{
      overflow:auto;
      margin-bottom:35px;
    }

    table{
      width:100%;
      border-collapse:collapse;
      min-width:650px;
    }

    table th{
      text-align:left;
      padding:18px 15px;
      color:#ffb347;
      border-bottom:1px solid rgba(255,255,255,0.08);
      font-size:14px;
    }

    table td{
      padding:18px 15px;
      border-bottom:1px solid rgba(255,255,255,0.05);
      color:rgba(255,255,255,0.82);
      font-size:14px;
    }

    /* SUMMARY */
    .summary-box{
      width:100%;
      max-width:350px;
      margin-left:auto;
      background:rgba(255,255,255,0.05);
      border-radius:25px;
      padding:25px;
      border:1px solid rgba(255,255,255,0.06);
      margin-bottom:35px;
    }

    .summary-row{
      display:flex;
      justify-content:space-between;
      margin-bottom:18px;
      color:rgba(255,255,255,0.75);
    }

    .summary-row.total{
      margin-top:20px;
      padding-top:20px;
      border-top:1px solid rgba(255,255,255,0.08);
      font-size:22px;
      font-weight:700;
      color:white;
    }

    /* FOOTER */
    .invoice-footer{
      text-align:center;
      margin-bottom:35px;
    }

    .invoice-footer h3{
      margin-bottom:12px;
      font-size:24px;
    }

    .invoice-footer p{
      color:rgba(255,255,255,0.6);
      line-height:1.8;
    }

    /* BUTTON */
    .button-group{
      display:flex;
      justify-content:center;
      gap:18px;
      flex-wrap:wrap;
    }

    .print-btn,
    .download-btn{
      border:none;
      padding:16px 28px;
      border-radius:18px;
      font-size:15px;
      font-weight:600;
      cursor:pointer;
      transition:0.3s;
      display:flex;
      align-items:center;
      gap:12px;
      color:white;
    }

    .print-btn{
      background:linear-gradient(135deg,#ff8a00,#ff0058);
      box-shadow:0 10px 25px rgba(255,100,0,0.25);
    }

    .download-btn{
      background:rgba(255,255,255,0.08);
    }

    .print-btn:hover,
    .download-btn:hover{
      transform:translateY(-4px);
    }

    /* RESPONSIVE */
    @media(max-width:700px){

      .invoice-container{
        padding:25px;
      }

      .invoice-header{
        flex-direction:column;
        align-items:flex-start;
      }

      .invoice-code{
        text-align:left;
      }

      .store-text h1{
        font-size:26px;
      }

      .button-group{
        flex-direction:column;
      }

      .print-btn,
      .download-btn{
        width:100%;
        justify-content:center;
      }

    }

  </style>
</head>
<body>

  <div class="invoice-container">

    <!-- HEADER -->
    <div class="invoice-header">

      <div class="store-info">

        <div class="logo">
          <i class="ri-cup-line"></i>
        </div>

        <div class="store-text">
          <h1>DrinkCashier</h1>
          <p>
            Sistem Kasir Modern Berbasis Web <br>
            Surabaya, Indonesia
          </p>
        </div>

      </div>

      <div class="invoice-code">
        <h2>INVOICE</h2>
        <p>
          Kode : <?php echo htmlspecialchars($invoiceId); ?> <br>
          Tanggal : <?php echo date('d M Y H:i', strtotime($tanggalTransaksi)); ?>
        </p>
      </div>

    </div>

    <!-- CUSTOMER -->
    <div class="customer-box">

      <h3>Informasi Transaksi</h3>

      <div class="customer-info">

        <div class="customer-item">
          <p>Kasir</p>
          <h4><?php echo htmlspecialchars($kasir); ?></h4>
        </div>

        <div class="customer-item">
          <p>Tanggal Transaksi</p>
          <h4><?php echo date('d M Y', strtotime($tanggalTransaksi)); ?></h4>
        </div>

        <div class="customer-item">
          <p>Status Pembayaran</p>
          <h4>Lunas</h4>
        </div>

      </div>

    </div>

    <!-- TABLE -->
    <div class="table-box">

      <table>

        <thead>
          <tr>
            <th>Produk</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
          </tr>
        </thead>

        <tbody>
          <?php foreach ($cart as $item): ?>
            <tr>
              <td><?php echo htmlspecialchars($item['nama_produk']); ?></td>
              <td>Rp <?php echo number_format($item['harga_produk'], 0, ',', '.'); ?></td>
              <td><?php echo $item['qty']; ?></td>
              <td>Rp <?php echo number_format($item['harga_produk'] * $item['qty'], 0, ',', '.'); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>

      </table>

    </div>

    <!-- SUMMARY -->
    <div class="summary-box">

      <div class="summary-row">
        <span>Subtotal</span>
        <span>Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></span>
      </div>

      <div class="summary-row">
        <span>Diskon</span>
        <span><?php echo $diskon; ?>%</span>
      </div>

      <div class="summary-row">
        <span>Total Bayar</span>
        <span>Rp <?php echo number_format($totalBayar, 0, ',', '.'); ?></span>
      </div>

      <div class="summary-row">
        <span>Uang Bayar</span>
        <span>Rp <?php echo number_format($uangBayar, 0, ',', '.'); ?></span>
      </div>

      <div class="summary-row">
        <span>Kembalian</span>
        <span>Rp <?php echo number_format($kembalian, 0, ',', '.'); ?></span>
      </div>

    </div>

    <!-- FOOTER -->
    <div class="invoice-footer">

      <h3>Terima Kasih ✨</h3>

      <p>
        Terima kasih telah melakukan transaksi pada sistem kasir DrinkCashier.
        Struk pembayaran ini dibuat secara otomatis oleh sistem.
      </p>

    </div>

    <!-- BUTTON -->
    <div class="button-group">

      <button class="print-btn" onclick="cetakStruk()">
        <i class="ri-printer-line"></i>
        Cetak Struk
      </button>

      <button class="download-btn" onclick="kembaliKeTransaksi()">
        <i class="ri-arrow-left-line"></i>
        Transaksi Baru
      </button>

    </div>

  </div>

</body>
<script>
function cetakStruk() {
  window.print();
}

function kembaliKeTransaksi() {
  // Bersihkan localStorage dan session
  localStorage.removeItem('cart');
  fetch('bersihkan_session.php', {
    method: 'POST'
  }).then(() => {
    window.location.href = 'transaksi.php';
  });
}
</script>
</html>