<?php
session_start();
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: halaman_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Analitik Penjualan - DrinkCashier</title>

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
      color:white;
      display:flex;
      overflow-x:hidden;
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

    /* MAIN */
    .main{
      margin-left:280px;
      width:calc(100% - 280px);
      padding:30px;
    }

    .topbar{
      display:flex;
      justify-content:space-between;
      align-items:center;
      gap:20px;
      flex-wrap:wrap;
      margin-bottom:35px;
    }

    .topbar h1{
      font-size:36px;
      margin-bottom:10px;
    }

    .topbar p{
      color:rgba(255,255,255,0.6);
    }

    .top-actions{
      display:flex;
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
    }

    .search-box input::placeholder{
      color:rgba(255,255,255,0.45);
    }

    .export-btn{
      border:none;
      padding:15px 22px;
      border-radius:18px;
      background:linear-gradient(135deg,#ff8a00,#ff0058);
      color:white;
      font-weight:600;
      cursor:pointer;
      transition:0.3s;
      box-shadow:0 10px 25px rgba(255,100,0,0.25);
    }

    .export-btn:hover{
      transform:translateY(-3px);
    }

    /* STATS */
    .stats{
      display:grid;
      grid-template-columns:repeat(auto-fit,minmax(230px,1fr));
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
      transform:translateY(-5px);
    }

    .stat-card::before{
      content:'';
      position:absolute;
      width:150px;
      height:150px;
      border-radius:50%;
      background:rgba(255,255,255,0.06);
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
      color:rgba(255,255,255,0.6);
      font-size:14px;
    }

    /* CHART */
    .chart-box{
      background:rgba(255,255,255,0.08);
      border:1px solid rgba(255,255,255,0.08);
      backdrop-filter:blur(20px);
      border-radius:30px;
      padding:30px;
      margin-bottom:35px;
    }

    .chart-title{
      display:flex;
      justify-content:space-between;
      align-items:center;
      margin-bottom:30px;
    }

    .chart-title h2{
      font-size:24px;
    }

    .chart-placeholder{
      height:300px;
      border-radius:24px;
      background:rgba(255,255,255,0.04);
      display:flex;
      flex-direction:column;
      justify-content:center;
      align-items:center;
      text-align:center;
      gap:15px;
      border:1px dashed rgba(255,255,255,0.08);
    }

    .chart-placeholder i{
      font-size:90px;
      color:rgba(255,255,255,0.3);
    }

    .chart-placeholder p{
      color:rgba(255,255,255,0.5);
      max-width:500px;
      line-height:1.8;
    }

    /* TABLE */
    .table-box{
      background:rgba(255,255,255,0.08);
      border:1px solid rgba(255,255,255,0.08);
      backdrop-filter:blur(20px);
      border-radius:30px;
      padding:25px;
      overflow:auto;
    }

    .table-header{
      display:flex;
      justify-content:space-between;
      align-items:center;
      margin-bottom:25px;
      gap:20px;
      flex-wrap:wrap;
    }

    .table-header h2{
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
      min-width:1000px;
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

    .status{
      display:inline-block;
      padding:8px 14px;
      border-radius:12px;
      background:rgba(0,255,140,0.15);
      color:#00ff9d;
      font-size:13px;
      font-weight:600;
    }

    .action-btn{
      border:none;
      width:38px;
      height:38px;
      border-radius:12px;
      cursor:pointer;
      color:white;
      margin-right:8px;
      transition:0.3s;
    }

    .edit{
      background:#ff9800;
    }

    .delete{
      background:#ff0058;
    }

    .action-btn:hover{
      transform:scale(1.08);
    }

    /* RESPONSIVE */
    @media(max-width:850px){

      .sidebar{
        width:90px;
        padding:20px 12px;
      }

      .logo h2,
      .menu a span{
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

      .search-box,
      .export-btn{
        width:100%;
      }

      .topbar h1{
        font-size:28px;
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

        <a href="dhasbord.php">
          <i class="ri-dashboard-3-line"></i>
          <span>Beranda</span>
        </a>

        <a href="kelola_produk.php">
          <i class="ri-shopping-bag-3-line"></i>
          <span>Kelola Data</span>
        </a>

        <a href="transaksi.php">
          <i class="ri-shopping-cart-2-line"></i>
          <span>Transaksi</span>
        </a>

        <a href="Analitik.php" class="active">
          <i class="ri-bar-chart-box-line"></i>
          <span>Analitik</span>
        </a>

        <a href="#">
          <i class="ri-settings-3-line"></i>
          <span>Pengaturan</span>
        </a>

      </div>

    </div>

  </aside>

  <!-- MAIN -->
  <main class="main">

    <div class="topbar">

      <div>
        <h1>Analitik Penjualan </h1>
        <p>Monitoring transaksi dan laporan penjualan secara realtime.</p>
      </div>

      <div class="top-actions">

        <div class="search-box">
          <i class="ri-search-line"></i>
          <input type="text" placeholder="Cari transaksi...">
        </div>

        <button class="export-btn">
          Export Laporan
        </button>

      </div>

    </div>

    <!-- STATS -->
    <div class="stats">

      <div class="stat-card">
        <div class="stat-top">
          <p>Total Transaksi</p>
          <i class="ri-shopping-bag-3-line"></i>
        </div>

        <h2>0</h2>
        <p>Belum ada transaksi tersimpan</p>
      </div>

      <div class="stat-card">
        <div class="stat-top">
          <p>Total Pendapatan</p>
          <i class="ri-money-dollar-circle-line"></i>
        </div>

        <h2>Rp 0</h2>
        <p>Belum ada pemasukan</p>
      </div>

      <div class="stat-card">
        <div class="stat-top">
          <p>Produk Terjual</p>
          <i class="ri-store-2-line"></i>
        </div>

        <h2>0</h2>
        <p>Belum ada produk terjual</p>
      </div>

      <div class="stat-card">
        <div class="stat-top">
          <p>Transaksi Hari Ini</p>
          <i class="ri-calendar-check-line"></i>
        </div>

        <h2>0</h2>
        <p>Belum ada transaksi hari ini</p>
      </div>

    </div>

    <!-- CHART -->
    <div class="chart-box">

      <div class="chart-title">
        <h2>Grafik Penjualan</h2>
        <i class="ri-line-chart-line"></i>
      </div>

      <div class="chart-placeholder">

        <i class="ri-bar-chart-grouped-line"></i>

        <h3>Grafik Belum Tersedia</h3>

        <p>
          Grafik penjualan akan tampil otomatis setelah sistem
          terhubung dengan database dan memiliki data transaksi.
        </p>

      </div>

    </div>

    <!-- TABLE -->
    <div class="table-box">

      <div class="table-header">

        <h2>Riwayat Transaksi</h2>

        <div class="filter-box">
          <button>Semua</button>
          <button>Hari Ini</button>
          <button>Mingguan</button>
          <button>Bulanan</button>
        </div>

      </div>

      <table>

        <thead>
          <tr>
            <th>Kode</th>
            <th>Produk</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
            <th>Diskon</th>
            <th>Total Bayar</th>
            <th>Uang Bayar</th>
            <th>Kembalian</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>

        <tbody>

          <tr>
            <td>TRX001</td>
            <td>Belum Ada Data</td>
            <td>0</td>
            <td>Rp 0</td>
            <td>0%</td>
            <td>Rp 0</td>
            <td>Rp 0</td>
            <td>Rp 0</td>
            <td>
              <span class="status">Selesai</span>
            </td>
            <td>
              <button class="action-btn edit">
                <i class="ri-edit-line"></i>
              </button>

              <button class="action-btn delete">
                <i class="ri-delete-bin-line"></i>
              </button>
            </td>
          </tr>

        </tbody>

      </table>

    </div>

  </main>

</body>
</html>