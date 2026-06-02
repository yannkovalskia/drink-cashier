<?php
session_start();
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: halaman_login.php");
    exit;
}

include '../config/koneksi.php';

// Ambil data produk dari database
try {
    $query = $pdo->query("SELECT p.*, k.nama_kategori FROM produk p LEFT JOIN kategori k ON p.id_kategori = k.id_kategori ORDER BY p.kode_produk DESC");
    $daftar_produk = $query->fetchAll();
} catch (PDOException $e) {
    die("Gagal mengambil data produk: " . $e->getMessage());
}

// Hitung statistik
try {
    $total_produk = $pdo->query("SELECT COUNT(*) as total FROM produk")->fetch()['total'];
    $stok_habis = $pdo->query("SELECT COUNT(*) as total FROM produk WHERE jumlah_stok = 0 OR status_produk = 'Habis'")->fetch()['total'];
    $total_kategori = $pdo->query("SELECT COUNT(*) as total FROM kategori")->fetch()['total'];
} catch (PDOException $e) {
    $total_produk = 0;
    $stok_habis = 0;
    $total_kategori = 0;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Halaman Produk - DrinkCashier</title>

  <!-- GOOGLE FONT -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- REMIX ICON -->
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
      color:white;
      display:flex;
      overflow-x:hidden;
    }

    /* BACKGROUND */
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
        transform:scale(1.1) rotate(5deg);
      }
    }

    /* SIDEBAR */
    .sidebar{
      width:280px;
      height:100vh;
      position:fixed;
      left:0;
      top:0;
      background:rgba(255,255,255,0.06);
      backdrop-filter:blur(20px);
      border-right:1px solid rgba(255,255,255,0.08);
      padding:25px;
      display:flex;
      flex-direction:column;
      justify-content:space-between;
    }

    .logo{
      display:flex;
      align-items:center;
      gap:15px;
      margin-bottom:40px;
    }

    .logo-icon{
      width:60px;
      height:60px;
      border-radius:18px;
      background:linear-gradient(135deg,#ff8a00,#ff0058);
      display:flex;
      justify-content:center;
      align-items:center;
      font-size:28px;
      box-shadow:0 10px 30px rgba(255,100,0,0.35);
    }

    .logo h2{
      font-size:24px;
      font-weight:700;
    }

    .logo span{
      color:#ffb347;
    }

    .menu{
      display:flex;
      flex-direction:column;
      gap:12px;
    }

    .menu a{
      text-decoration:none;
      color:white;
      padding:16px 18px;
      border-radius:18px;
      display:flex;
      align-items:center;
      gap:15px;
      transition:0.3s;
      font-size:15px;
      font-weight:500;
    }

    .menu a:hover,
    .menu .active{
      background:linear-gradient(135deg,#ff8a00,#ff0058);
      transform:translateX(5px);
      box-shadow:0 10px 25px rgba(255,100,0,0.25);
    }

    .menu a i{
      font-size:22px;
    }

    .logout a{
      background:rgba(255,255,255,0.07);
    }

    /* MAIN */
    .main{
      margin-left:280px;
      width:calc(100% - 280px);
      padding:30px;
    }

    /* TOPBAR */
    .topbar{
      display:flex;
      justify-content:space-between;
      align-items:center;
      gap:20px;
      flex-wrap:wrap;
      margin-bottom:35px;
    }

    .page-title h1{
      font-size:34px;
      margin-bottom:8px;
    }

    .page-title p{
      color:rgba(255,255,255,0.65);
    }

    .top-actions{
      display:flex;
      align-items:center;
      gap:15px;
      flex-wrap:wrap;
    }

    .search-box{
      width:280px;
      background:rgba(255,255,255,0.08);
      border:1px solid rgba(255,255,255,0.08);
      border-radius:18px;
      padding:14px 18px;
      display:flex;
      align-items:center;
      gap:12px;
    }

    .search-box input{
      width:100%;
      background:none;
      border:none;
      outline:none;
      color:white;
      font-size:14px;
    }

    .search-box input::placeholder{
      color:rgba(255,255,255,0.5);
    }

    .add-btn{
      border:none;
      padding:15px 22px;
      border-radius:18px;
      background:linear-gradient(135deg,#ff8a00,#ff0058);
      color:white;
      font-size:14px;
      font-weight:600;
      cursor:pointer;
      display:flex;
      align-items:center;
      gap:10px;
      transition:0.3s;
      box-shadow:0 10px 25px rgba(255,100,0,0.25);
    }

    .add-btn:hover{
      transform:translateY(-3px);
    }

    /* STATISTIC */
    .stats{
      display:grid;
      grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
      gap:25px;
      margin-bottom:35px;
    }

    .stat-card{
      background:rgba(255,255,255,0.08);
      border:1px solid rgba(255,255,255,0.08);
      backdrop-filter:blur(20px);
      border-radius:28px;
      padding:28px;
      position:relative;
      overflow:hidden;
      transition:0.3s;
    }

    .stat-card:hover{
      transform:translateY(-6px);
    }

    .stat-card::before{
      content:'';
      position:absolute;
      width:150px;
      height:150px;
      border-radius:50%;
      background:rgba(255,255,255,0.08);
      top:-70px;
      right:-70px;
    }

    .stat-top{
      display:flex;
      justify-content:space-between;
      align-items:center;
      margin-bottom:18px;
    }

    .stat-top i{
      font-size:32px;
    }

    .stat-card h2{
      font-size:34px;
      margin-bottom:8px;
    }

    .stat-card p{
      color:rgba(255,255,255,0.65);
      font-size:14px;
    }

    /* TABLE BOX */
    .table-box{
      background:rgba(255,255,255,0.08);
      border:1px solid rgba(255,255,255,0.08);
      backdrop-filter:blur(20px);
      border-radius:30px;
      padding:25px;
      overflow:hidden;
    }

    .table-header{
      display:flex;
      justify-content:space-between;
      align-items:center;
      margin-bottom:25px;
      flex-wrap:wrap;
      gap:15px;
    }

    .table-header h3{
      font-size:24px;
    }

    .filter-box{
      display:flex;
      gap:12px;
      flex-wrap:wrap;
    }

    .filter-box button{
      border:none;
      padding:12px 18px;
      border-radius:14px;
      background:rgba(255,255,255,0.08);
      color:white;
      cursor:pointer;
      transition:0.3s;
    }

    .filter-box button:hover{
      background:linear-gradient(135deg,#ff8a00,#ff0058);
    }

    table{
      width:100%;
      border-collapse:collapse;
    }

    table th{
      text-align:left;
      padding:18px 15px;
      color:#ffb347;
      font-size:14px;
      font-weight:600;
      border-bottom:1px solid rgba(255,255,255,0.08);
    }

    table td{
      padding:18px 15px;
      color:rgba(255,255,255,0.85);
      border-bottom:1px solid rgba(255,255,255,0.06);
      font-size:14px;
    }

    .empty-state{
      height:350px;
      display:flex;
      flex-direction:column;
      justify-content:center;
      align-items:center;
      text-align:center;
      color:rgba(255,255,255,0.5);
      gap:15px;
    }

    .empty-state i{
      font-size:90px;
      opacity:0.5;
    }

    .empty-state h2{
      font-size:28px;
      color:white;
    }

    .empty-state p{
      line-height:1.8;
      max-width:450px;
    }

    /* RESPONSIVE */
    @media(max-width:850px){

      .sidebar{
        width:90px;
        padding:20px 12px;
      }

      .logo h2,
      .menu a span,
      .logout a span{
        display:none;
      }

      .menu a{
        justify-content:center;
      }

      .main{
        margin-left:90px;
        width:calc(100% - 90px);
      }

    }

    @media(max-width:650px){

      .main{
        padding:20px;
      }

      .topbar{
        flex-direction:column;
        align-items:flex-start;
      }

      .top-actions{
        width:100%;
        flex-direction:column;
      }

      .search-box{
        width:100%;
      }

      .add-btn{
        width:100%;
        justify-content:center;
      }

      .table-box{
        overflow:auto;
      }

      .page-title h1{
        font-size:28px;
      }

    }

    /* NOTIFICATION ALERT */
    .notification {
      position: fixed;
      top: 20px;
      right: 20px;
      padding: 16px 24px;
      border-radius: 18px;
      display: flex;
      align-items: center;
      gap: 12px;
      font-weight: 500;
      animation: slideIn 0.3s ease-out;
      z-index: 999;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .notification.success {
      background: linear-gradient(135deg, #10b981, #059669);
      color: white;
    }

    .notification.error {
      background: linear-gradient(135deg, #ef4444, #dc2626);
      color: white;
    }

    .notification i {
      font-size: 20px;
    }

    @keyframes slideIn {
      from {
        transform: translateX(400px);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    @keyframes slideOut {
      from {
        transform: translateX(0);
        opacity: 1;
      }
      to {
        transform: translateX(400px);
        opacity: 0;
      }
    }

  </style>
</head>
<body>

  <!-- SIDEBAR -->
  <aside class="sidebar">

    <div>

      <div class="logo">

        <div class="logo-icon">
          <i class="ri-cup-line"></i>
        </div>

        <h2>Drink<span>Cashier</span></h2>

      </div>

      <div class="menu">

        <a href="dashboard.php">
          <i class="ri-dashboard-3-line"></i>
          <span>Beranda</span>
        </a>

        <a href="kelola_produk.php" class="active">
          <i class="ri-shopping-bag-3-line"></i>
          <span>Kelola Data</span>
        </a>

        <a href="transaksi.php">
          <i class="ri-shopping-cart-2-line"></i>
          <span>Transaksi</span>
        </a>

        <a href="Analitik.php">
          <i class="ri-bar-chart-box-line"></i>
          <span>Analitik</span>
        </a>

        <a href="#">
          <i class="ri-settings-3-line"></i>
          <span>Pengaturan</span>
        </a>

      </div>

    </div>

    <div class="logout">
      <a href="logout.php">
        <i class="ri-logout-box-r-line"></i>
        <span>Logout</span>
      </a>
    </div>

  </aside>

  <!-- MAIN -->
  <main class="main">

    <!-- NOTIFIKASI SUCCESS -->
    <?php if (isset($_SESSION['success_message'])): ?>
      <div class="notification success" id="successNotif">
        <i class="ri-check-circle-line"></i>
        <span><?= htmlspecialchars($_SESSION['success_message']); ?></span>
      </div>
      <script>
        setTimeout(() => {
          const notif = document.getElementById('successNotif');
          if (notif) {
            notif.style.animation = 'slideOut 0.3s ease-out forwards';
            setTimeout(() => notif.remove(), 300);
          }
        }, 3000);
      </script>
      <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <!-- TOPBAR -->
    <div class="topbar">

      <div class="page-title">
        <h1>Kelola Produk </h1>
      </div>

      <div class="top-actions">

        <div class="search-box">
          <i class="ri-search-line"></i>
          <input type="text" placeholder="Cari produk...">
        </div>

        <button class="add-btn" onclick="window.location.href='tambah_produk.php'">
          <i class="ri-add-circle-line"></i>
          Tambah Produk
        </button>

      </div>

    </div>

    <!-- STATISTIC -->
    <div class="stats">

      <div class="stat-card">
        <div class="stat-top">
          <p>Total Produk</p>
          <i class="ri-cup-line"></i>
        </div>

        <h2><?= $total_produk ?></h2>
        <p><?= $total_produk > 0 ? $total_produk . ' produk tersimpan' : 'Belum ada data produk' ?></p>
      </div>

      <div class="stat-card">
        <div class="stat-top">
          <p>Stok Tersedia</p>
          <i class="ri-archive-drawer-line"></i>
        </div>

        <h2><?= $total_produk - $stok_habis ?></h2>
        <p><?= ($total_produk - $stok_habis) > 0 ? ($total_produk - $stok_habis) . ' produk tersedia' : 'Belum ada stok tersedia' ?></p>
      </div>

      <div class="stat-card">
        <div class="stat-top">
          <p>Stok Habis</p>
          <i class="ri-error-warning-line"></i>
        </div>

        <h2><?= $stok_habis ?></h2>
        <p><?= $stok_habis > 0 ? $stok_habis . ' produk habis' : 'Belum ada produk habis' ?></p>
      </div>

      <div class="stat-card">
        <div class="stat-top">
          <p>Kategori</p>
          <i class="ri-layout-grid-line"></i>
        </div>

        <h2><?= $total_kategori ?></h2>
        <p><?= $total_kategori > 0 ? $total_kategori . ' kategori' : 'Belum ada kategori produk' ?></p>
      </div>

    </div>

    <!-- TABLE -->
    <div class="table-box">

      <div class="table-header">

        <h3>Data Produk</h3>

        <div class="filter-box">
          <button>Semua</button>
          <button>Tersedia</button>
          <button>Habis</button>
        </div>

      </div>

      <table>

        <thead>
          <tr>
            <th>Kode</th>
            <th>Nama Produk</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>

        <tbody>
          <?php if (count($daftar_produk) > 0): ?>
            <?php foreach ($daftar_produk as $produk): ?>
              <tr>
                <td><?= htmlspecialchars($produk['kode_produk']) ?></td>
                <td><?= htmlspecialchars($produk['nama_produk']) ?></td>
                <td><?= $produk['nama_kategori'] ? htmlspecialchars($produk['nama_kategori']) : '-' ?></td>
                <td>Rp <?= number_format($produk['harga_produk'], 0, ',', '.') ?></td>
                <td><?= $produk['jumlah_stok'] ?></td>
                <td>
                  <span style="padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600;
                    <?php 
                      if ($produk['status_produk'] == 'Tersedia') {
                        echo "background: rgba(16, 185, 129, 0.2); color: #10b981;";
                      } else {
                        echo "background: rgba(239, 68, 68, 0.2); color: #ef4444;";
                      }
                    ?>">
                    <?= htmlspecialchars($produk['status_produk']) ?>
                  </span>
                </td>
                <td>
                  <div style="display: flex; gap: 8px;">
                    <button type="button" style="padding: 8px 12px; border: none; border-radius: 8px; background: rgba(59, 130, 246, 0.2); color: #3b82f6; cursor: pointer; font-size: 12px; font-weight: 600;" onclick="window.location.href='edit_produk.php?kode=<?= urlencode($produk['kode_produk']) ?>'">Edit</button>
                    <button type="button" style="padding: 8px 12px; border: none; border-radius: 8px; background: rgba(239, 68, 68, 0.2); color: #ef4444; cursor: pointer; font-size: 12px; font-weight: 600;" onclick="if(confirm('Yakin hapus produk ini?')) { window.location.href='hapus_produk.php?kode=' + '<?= $produk['kode_produk'] ?>'; }">Hapus</button>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>

      </table>

      <!-- EMPTY STATE -->
      <?php if (count($daftar_produk) == 0): ?>
      <div class="empty-state">

        <i class="ri-inbox-archive-line"></i>

        <h2>Belum Ada Data Produk</h2>

        <p>
          Sistem belum memiliki data produk yang ditampilkan.
          Silakan tambahkan produk baru untuk memulai.
        </p>

      </div>
      <?php endif; ?>

    </div>

  </main>

</body>
</html>