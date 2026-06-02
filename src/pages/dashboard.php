<?php
session_start();

// Jika belum login, redirect ke halaman login
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: ../pages/halaman_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard DrinkCashier</title>

  <!-- GOOGLE FONT -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- REMIX ICON -->
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">

  <!-- CHART JS -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>

    *{
      margin:0;
      padding:0;
      box-sizing:border-box;
      font-family:'Poppins',sans-serif;
    }

    body{
      min-height:100vh;
      background:#0f172a;
      overflow-x:hidden;
      color:white;
      display:flex;
    }

    /* ANIMATED BACKGROUND */
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

    .logout{
      margin-top:30px;
    }

    .logout a{
      background:rgba(255,255,255,0.07);
    }

    /* MAIN CONTENT */
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
      margin-bottom:35px;
      gap:20px;
      flex-wrap:wrap;
    }

    .welcome h1{
      font-size:34px;
      margin-bottom:8px;
    }

    .welcome p{
      color:rgba(255,255,255,0.7);
    }

    .top-right{
      display:flex;
      align-items:center;
      gap:18px;
    }

    .search-box{
      background:rgba(255,255,255,0.08);
      border:1px solid rgba(255,255,255,0.08);
      border-radius:18px;
      padding:14px 18px;
      display:flex;
      align-items:center;
      gap:12px;
      width:280px;
    }

    .search-box input{
      background:none;
      border:none;
      outline:none;
      color:white;
      width:100%;
      font-size:14px;
    }

    .search-box input::placeholder{
      color:rgba(255,255,255,0.5);
    }

    .profile{
      display:flex;
      align-items:center;
      gap:12px;
      background:rgba(255,255,255,0.08);
      padding:10px 15px;
      border-radius:18px;
      border:1px solid rgba(255,255,255,0.08);
    }

    .profile img{
      width:45px;
      height:45px;
      border-radius:50%;
      object-fit:cover;
    }

    .profile h4{
      font-size:15px;
    }

    .profile span{
      font-size:12px;
      color:#ffb347;
    }

    /* CARDS */
    .cards{
      display:grid;
      grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
      gap:25px;
      margin-bottom:35px;
    }

    .card{
      position:relative;
      overflow:hidden;
      background:rgba(255,255,255,0.08);
      border:1px solid rgba(255,255,255,0.08);
      backdrop-filter:blur(20px);
      border-radius:28px;
      padding:28px;
      transition:0.4s;
    }

    .card:hover{
      transform:translateY(-8px);
      box-shadow:0 20px 35px rgba(0,0,0,0.25);
    }

    .card::before{
      content:'';
      position:absolute;
      width:150px;
      height:150px;
      background:rgba(255,255,255,0.08);
      border-radius:50%;
      top:-70px;
      right:-70px;
    }

    .card-top{
      display:flex;
      justify-content:space-between;
      align-items:center;
      margin-bottom:20px;
    }

    .card-top i{
      font-size:35px;
    }

    .card h2{
      font-size:34px;
      margin-bottom:8px;
    }

    .card p{
      color:rgba(255,255,255,0.7);
      font-size:14px;
    }

    .orange{
      background:linear-gradient(135deg,rgba(255,138,0,0.35),rgba(255,0,88,0.2));
    }

    .blue{
      background:linear-gradient(135deg,rgba(0,212,255,0.35),rgba(0,153,255,0.2));
    }

    .green{
      background:linear-gradient(135deg,rgba(0,255,163,0.3),rgba(0,180,120,0.2));
    }

    .purple{
      background:linear-gradient(135deg,rgba(191,90,242,0.3),rgba(128,0,255,0.2));
    }

    /* CONTENT GRID */
    .content-grid{
      display:grid;
      grid-template-columns:2fr 1fr;
      gap:25px;
    }

    .glass-box{
      background:rgba(255,255,255,0.08);
      border:1px solid rgba(255,255,255,0.08);
      backdrop-filter:blur(20px);
      border-radius:28px;
      padding:25px;
    }

    .box-title{
      display:flex;
      justify-content:space-between;
      align-items:center;
      margin-bottom:20px;
    }

    .box-title h3{
      font-size:22px;
    }

    /* TABLE */
    table{
      width:100%;
      border-collapse:collapse;
    }

    table th{
      text-align:left;
      padding:15px;
      color:#ffb347;
      font-size:14px;
      font-weight:600;
    }

    table td{
      padding:15px;
      border-top:1px solid rgba(255,255,255,0.08);
      font-size:14px;
      color:rgba(255,255,255,0.85);
    }

    .status{
      padding:7px 12px;
      border-radius:50px;
      font-size:12px;
      display:inline-block;
      font-weight:600;
    }

    .success{
      background:rgba(0,255,163,0.2);
      color:#00ffb3;
    }

    .pending{
      background:rgba(255,184,0,0.2);
      color:#ffcc00;
    }

    /* PRODUCT LIST */
    .product-item{
      display:flex;
      align-items:center;
      justify-content:space-between;
      padding:15px 0;
      border-bottom:1px solid rgba(255,255,255,0.08);
    }

    .product-left{
      display:flex;
      align-items:center;
      gap:15px;
    }

    .product-icon{
      width:55px;
      height:55px;
      border-radius:18px;
      background:linear-gradient(135deg,#ff8a00,#ff0058);
      display:flex;
      justify-content:center;
      align-items:center;
      font-size:24px;
    }

    .product-info h4{
      margin-bottom:5px;
    }

    .product-info p{
      font-size:13px;
      color:rgba(255,255,255,0.6);
    }

    .price{
      color:#ffb347;
      font-weight:600;
    }

    /* RESPONSIVE */
    @media(max-width:1100px){
      .content-grid{
        grid-template-columns:1fr;
      }
    }

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

      .main{
        margin-left:90px;
        width:calc(100% - 90px);
      }

      .menu a{
        justify-content:center;
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

      .top-right{
        width:100%;
        flex-direction:column;
      }

      .search-box{
        width:100%;
      }

      .profile{
        width:100%;
        justify-content:center;
      }

      .welcome h1{
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

        <a href="dhasbord.php" class="active">
          <i class="ri-dashboard-3-line"></i>
          <span>Beranda</span>
        </a>

        <a href="kelola_produk.php">
          <i class="ri-layout-grid-line"></i>
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

    <!-- TOPBAR -->
    <div class="topbar">

      <div class="welcome">
        <h1>Dashboard Modern </h1>
        <p>Tampilan modern untuk aplikasi kasir berbasis web.</p>
      </div>

      <div class="top-right">

        <div class="search-box">
          <i class="ri-search-line"></i>
          <input type="text" placeholder="Cari data...">
        </div>

        <div class="profile">
          <img src="https://i.pravatar.cc/100" alt="profile">
          <div>
            <h4>Admin Kasir</h4>
            <span>Administrator</span>
          </div>
        </div>

      </div>

    </div>

    <!-- CARDS -->
    <div class="cards">

      <div class="card orange">
        <div class="card-top">
          <p>Total Data</p>
          <i class="ri-database-2-line"></i>
        </div>

        <h2>0</h2>
        <p>Belum ada data tersedia</p>
      </div>

      <div class="card blue">
        <div class="card-top">
          <p>Total Transaksi</p>
          <i class="ri-exchange-dollar-line"></i>
        </div>

        <h2>0</h2>
        <p>Belum ada aktivitas transaksi</p>
      </div>

      <div class="card green">
        <div class="card-top">
          <p>Pendapatan</p>
          <i class="ri-line-chart-line"></i>
        </div>

        <h2>Rp 0</h2>
        <p>Data pendapatan belum tersedia</p>
      </div>

      <div class="card purple">
        <div class="card-top">
          <p>Pengguna</p>
          <i class="ri-team-line"></i>
        </div>

        <h2>0</h2>
        <p>Belum ada pengguna aktif</p>
      </div>

    </div>

    <!-- CONTENT GRID -->
    <div class="content-grid">

      <!-- CHART -->
      <div class="glass-box">

        <div class="box-title">
          <h3>Analitik Sistem</h3>
          <i class="ri-bar-chart-grouped-line"></i>
        </div>

        <canvas id="salesChart"></canvas>

      </div>

      <!-- PRODUCT -->
      <div class="glass-box">

        <div class="box-title">
          <h3>Produk Populer</h3>
          <i class="ri-fire-line"></i>
        </div>

        <div style="display:flex;justify-content:center;align-items:center;height:320px;color:rgba(255,255,255,0.5);font-size:15px;text-align:center;">
          Belum ada data produk tersedia.<br>
          Tim database akan menghubungkan data dinamis di sini.
        </div>

      </div>

    </div>

    <!-- TRANSACTION TABLE -->
    <div class="glass-box" style="margin-top:25px;">

      <div class="box-title">
        <h3>Aktivitas Terbaru</h3>
        <i class="ri-history-line"></i>
      </div>

      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Pengguna</th>
            <th>Aktivitas</th>
            <th>Total</th>
            <th>Status</th>
            <th>Tanggal</th>
          </tr>
        </thead>

        <tbody>
          <tr>
            <td colspan="6" style="text-align:center;padding:40px;color:rgba(255,255,255,0.5);">
              Belum ada data transaksi tersedia.<br>
              Menunggu integrasi database.
            </td>
          </tr>
        </tbody>
      </table>

    </div>

  </main>

  <script>

    const ctx = document.getElementById('salesChart');

    new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
        datasets: [{
          label: 'Data Sistem',
          data: [0, 0, 0, 0, 0, 0, 0],
          borderWidth: 4,
          tension: 0.4,
          fill: true,
          backgroundColor: 'rgba(255,255,255,0.05)',
          borderColor: '#ffb347',
          pointBackgroundColor: '#fff',
          pointRadius: 5
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            labels: {
              color: 'white'
            }
          }
        },
        scales: {
          x: {
            ticks: {
              color: 'white'
            },
            grid: {
              color: 'rgba(255,255,255,0.08)'
            }
          },
          y: {
            ticks: {
              color: 'white'
            },
            grid: {
              color: 'rgba(255,255,255,0.08)'
            }
          }
        }
      }
    });

  </script>

</body>
</html>
