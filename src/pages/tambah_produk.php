<?php
session_start();
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: halaman_login.php");
    exit;
}

include '../config/koneksi.php';

try {
    $query = $pdo->query("SELECT * FROM kategori");
    $daftar_kategori = $query->fetchAll();
} catch (PDOException $e) {
    die("Gagal mengambil data kategori: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Produk - DrinkCashier</title>

  <!-- GOOGLE FONT -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- REMIX ICON -->
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">

  <style>
    /* --- RESET & BASE --- */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    /* --- BODY & BACKGROUND --- */
    body {
      background: #0f172a;
      min-height: 100vh;
      color: white;
      display: flex;
      overflow-x: hidden;
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
      animation: bgMove 10s infinite alternate ease-in-out;
      z-index: -2;
    }

    @keyframes bgMove {
      from {
        transform: scale(1) rotate(0deg);
      }
      to {
        transform: scale(1.08) rotate(5deg);
      }
    }

    /* --- SIDEBAR --- */
    .sidebar {
      width: 280px;
      height: 100vh;
      position: fixed;
      left: 0;
      top: 0;
      background: rgba(255, 255, 255, 0.06);
      backdrop-filter: blur(20px);
      border-right: 1px solid rgba(255, 255, 255, 0.08);
      padding: 25px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 15px;
      margin-bottom: 40px;
    }

    .logo-icon {
      width: 60px;
      height: 60px;
      border-radius: 18px;
      background: linear-gradient(135deg, #ff8a00, #ff0058);
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 28px;
      box-shadow: 0 10px 30px rgba(255, 100, 0, 0.35);
    }

    .logo h2 {
      font-size: 24px;
      font-weight: 700;
    }

    .logo span {
      color: #ffb347;
    }

    .menu {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .menu a {
      text-decoration: none;
      color: white;
      padding: 16px 18px;
      border-radius: 18px;
      display: flex;
      align-items: center;
      gap: 15px;
      transition: 0.3s;
      font-size: 15px;
      font-weight: 500;
    }

    .menu a:hover,
    .menu .active {
      background: linear-gradient(135deg, #ff8a00, #ff0058);
      transform: translateX(5px);
      box-shadow: 0 10px 25px rgba(255, 100, 0, 0.25);
    }

    .menu a i {
      font-size: 22px;
    }

    /* --- MAIN CONTENT --- */
    .main {
      margin-left: 280px;
      width: calc(100% - 280px);
      padding: 35px;
    }

    .topbar {
      margin-bottom: 35px;
    }

    .topbar h1 {
      font-size: 36px;
      margin-bottom: 10px;
    }

    .topbar p {
      color: rgba(255, 255, 255, 0.65);
    }

    /* --- FORM CONTAINER --- */
    .form-container {
      display: grid;
      grid-template-columns: 1.2fr 0.8fr;
      gap: 30px;
    }

    .form-box,
    .preview-box {
      background: rgba(255, 255, 255, 0.08);
      border: 1px solid rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(20px);
      border-radius: 30px;
      padding: 30px;
    }

    .box-title {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
    }

    .box-title h2 {
      font-size: 26px;
    }

    .box-title i {
      font-size: 28px;
      color: #ffb347;
    }

    /* --- FORM GRID & INPUT --- */
    .form-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 25px;
    }

    .input-group {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .input-group label {
      font-size: 14px;
      font-weight: 500;
      color: #ffb347;
    }

    .input-group input,
    .input-group select,
    .input-group select option,
    .input-group textarea {
      width: 100%;
      border: none;
      outline: none;
      background: rgba(255, 255, 255, 0.08);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 18px;
      padding: 16px 18px;
      color: white;
      font-size: 14px;
      transition: 0.3s;
    }

    .input-group textarea {
      min-height: 140px;
      resize: none;
    }

    .input-group select option {
      background: #1e293b;
      color: white;
    }

    .input-group input:focus,
    .input-group select:focus,
    .input-group textarea:focus {
      border: 1px solid #ff8a00;
      box-shadow: 0 0 20px rgba(255, 138, 0, 0.2);
    }

    .input-group input::placeholder,
    .input-group textarea::placeholder {
      color: rgba(255, 255, 255, 0.45);
    }

    /* --- UPLOAD BOX --- */
    .upload-box {
      margin-top: 25px;
      border: 2px dashed rgba(255, 255, 255, 0.15);
      border-radius: 28px;
      padding: 45px 25px;
      text-align: center;
      transition: 0.3s;
      cursor: pointer;
      background: rgba(255, 255, 255, 0.03);
      position: relative;
    }

    .upload-box:hover {
      border-color: #ff8a00;
      background: rgba(255, 138, 0, 0.06);
    }

    .upload-box i {
      font-size: 70px;
      color: #ffb347;
      margin-bottom: 15px;
    }

    .upload-box h3 {
      margin-bottom: 10px;
      font-size: 22px;
    }

    .upload-box p {
      color: rgba(255, 255, 255, 0.55);
      line-height: 1.8;
      font-size: 14px;
    }

    /* --- BUTTON GROUP --- */
    .button-group {
      display: flex;
      gap: 18px;
      margin-top: 35px;
      flex-wrap: wrap;
    }

    .save-btn,
    .cancel-btn {
      border: none;
      padding: 16px 28px;
      border-radius: 18px;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
      transition: 0.3s;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .save-btn {
      background: linear-gradient(135deg, #ff8a00, #ff0058);
      color: white;
      box-shadow: 0 10px 25px rgba(255, 100, 0, 0.25);
    }

    .cancel-btn {
      background: rgba(255, 255, 255, 0.08);
      color: white;
    }

    .save-btn:hover,
    .cancel-btn:hover {
      transform: translateY(-3px);
    }

    /* --- PREVIEW CARD --- */
    .preview-card {
      background: rgba(255, 255, 255, 0.05);
      border-radius: 28px;
      padding: 25px;
      text-align: center;
      border: 1px solid rgba(255, 255, 255, 0.06);
    }

    .preview-image {
      width: 100%;
      height: 280px;
      border-radius: 24px;
      background: rgba(255, 255, 255, 0.06);
      display: flex;
      justify-content: center;
      align-items: center;
      margin-bottom: 25px;
      overflow: hidden;
    }

    .preview-image i {
      font-size: 100px;
      color: rgba(255, 255, 255, 0.35);
    }

    .preview-card h3 {
      font-size: 24px;
      margin-bottom: 10px;
    }

    .preview-card p {
      color: rgba(255, 255, 255, 0.6);
      line-height: 1.8;
      margin-bottom: 20px;
    }

    .preview-price {
      display: inline-block;
      padding: 12px 22px;
      border-radius: 16px;
      background: linear-gradient(135deg, #ff8a00, #ff0058);
      font-weight: 600;
    }

    /* --- RESPONSIVE --- */
    @media (max-width: 1050px) {
      .form-container {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 850px) {
      .sidebar {
        width: 90px;
        padding: 20px 12px;
      }

      .logo h2,
      .menu a span {
        display: none;
      }

      .menu a {
        justify-content: center;
      }

      .main {
        margin-left: 90px;
        width: calc(100% - 90px);
      }
    }

    @media (max-width: 650px) {
      .main {
        padding: 20px;
      }

      .topbar h1 {
        font-size: 28px;
      }

      .button-group {
        flex-direction: column;
      }

      .save-btn,
      .cancel-btn {
        width: 100%;
        justify-content: center;
      }
    }
  </style>
</head>
<body>

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div>
      <div class="logo">
        <div class="logo-icon"><i class="ri-cup-line"></i></div>
        <h2>Drink<span>Cashier</span></h2>
      </div>
      <div class="menu">
        <a href="dashboard.php"><i class="ri-dashboard-3-line"></i><span>Beranda</span></a>
        <a href="kelola_produk.php" class="active"><i class="ri-shopping-bag-3-line"></i><span>Kelola Data</span></a>
        <a href="transaksi.php"><i class="ri-shopping-cart-2-line"></i><span>Transaksi</span></a>
        <a href="Analitik.php"><i class="ri-bar-chart-box-line"></i><span>Analitik</span></a>
        <a href="#"><i class="ri-settings-3-line"></i><span>Pengaturan</span></a>
      </div>
    </div>
  </aside>

  <!-- MAIN -->
  <main class="main">
    <div class="topbar">
      <h1>Tambah Produk Baru</h1>
    </div>

    <!-- SEKARANG DIBUNGKUS SAMA TAG FORM PHP -->
    <form action="proses_tambah_produk.php" method="POST" enctype="multipart/form-data" class="form-container">
      
      <!-- FORM INPUT BOX -->
      <div class="form-box">
        <div class="box-title">
          <h2>Input Data Produk</h2>
          <i class="ri-file-add-line"></i>
        </div>

        <div class="form-grid">
          <div class="input-group">
            <label>Kode Produk</label>
            <input type="text" name="kode_produk" placeholder="Masukkan kode produk" required>
          </div>

          <div class="input-group">
            <label>Nama Produk</label>
            <input type="text" name="nama_produk" placeholder="Masukkan nama produk" required>
          </div>

          <div class="input-group">
            <label>Kategori</label>
            <select name="id_kategori" required>
              <option value="">Pilih kategori</option>
              <!-- LOOPING DATA KATEGORI DARI DATABASE -->
              <?php foreach ($daftar_kategori as $kat): ?>
                  <option value="<?= $kat['id_kategori']; ?>">
                      <?= htmlspecialchars($kat['nama_kategori']); ?>
                  </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="input-group">
            <label>Harga Produk</label>
            <input type="number" name="harga_produk" placeholder="Masukkan harga produk" required>
          </div>

          <div class="input-group">
            <label>Jumlah Stok</label>
            <input type="number" name="jumlah_stok" placeholder="Masukkan stok produk" required>
          </div>

          <div class="input-group">
            <label>Status Produk</label>
            <select name="status_produk" required>
              <option value="">Pilih status</option>
              <option value="Tersedia">Tersedia</option>
              <option value="Habis">Habis</option>
            </select>
          </div>
        </div>

        <div class="input-group" style="margin-top:25px;">
          <label>Deskripsi Produk</label>
          <textarea name="deskripsi_produk" placeholder="Masukkan deskripsi produk..."></textarea>
        </div>

        <!-- UPLOAD GAMBAR DENGAN INPUT FILE TERSEMBUNYI -->
        <div class="upload-box" onclick="document.getElementById('file-input').click();">
          <i class="ri-image-add-line"></i>
          <h3>Upload Gambar Produk</h3>
          <p>Klik di sini untuk upload foto produk (JPG, PNG, WEBP).</p>
          <!-- Input file asli yang disembunyikan secara visual -->
          <input type="file" id="file-input" name="gambar_produk" accept="image/*" style="display:none;">
        </div>

        <!-- BUTTON -->
        <div class="button-group">
          <!-- Menggunakan type="submit" agar men-trigger form -->
          <button type="submit" class="save-btn">
            <i class="ri-save-3-line"></i>Simpan Produk
          </button>
          <button type="button" class="cancel-btn" onclick="window.location='kelola_produk.php'">
            <i class="ri-close-circle-line"></i>Batal
          </button>
        </div>
      </div>

      <!-- PREVIEW CARD -->
      <div class="preview-box">
        <div class="box-title">
          <h2>Preview Produk</h2>
          <i class="ri-eye-line"></i>
        </div>
        <div class="preview-card">
          <div class="preview-image">
            <i class="ri-image-line"></i>
          </div>
          <h3>Nama Produk</h3>
          <div class="preview-price">Rp 0</div>
        </div>
      </div>

    </form>
  </main>

  <script>
    // Handle image preview
    const fileInput = document.getElementById('file-input');
    const previewImage = document.querySelector('.preview-image');
    const uploadBox = document.querySelector('.upload-box');

    fileInput.addEventListener('change', function(e) {
      const file = e.target.files[0];
      
      if (file) {
        const reader = new FileReader();
        
        reader.onload = function(event) {
          // Create image element
          const img = document.createElement('img');
          img.src = event.target.result;
          img.style.width = '100%';
          img.style.height = '100%';
          img.style.objectFit = 'cover';
          
          // Clear preview-image and add new image
          previewImage.innerHTML = '';
          previewImage.appendChild(img);
          
          // Hide upload box when image is selected
          uploadBox.style.opacity = '0.5';
        };
        
        reader.readAsDataURL(file);
      }
    });

    // Handle real-time preview update for product data
    const namaProdukInput = document.querySelector('input[name="nama_produk"]');
    const hargaProdukInput = document.querySelector('input[name="harga_produk"]');
    const previewName = document.querySelector('.preview-card h3');
    const previewPrice = document.querySelector('.preview-price');

    // Update nama produk
    namaProdukInput.addEventListener('input', function() {
      previewName.textContent = this.value || 'Nama Produk';
    });

    // Update harga produk
    hargaProdukInput.addEventListener('input', function() {
      const harga = this.value ? parseInt(this.value) : 0;
      previewPrice.textContent = 'Rp ' + harga.toLocaleString('id-ID');
    });
  </script>

</body>
</html>