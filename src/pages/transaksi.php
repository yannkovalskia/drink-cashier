<?php
session_start();
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: halaman_login.php");
    exit;
}

include '../config/koneksi.php';

// Ambil data produk dari database
$daftar_produk = [];
try {
    $query = $pdo->query("SELECT p.*, k.nama_kategori FROM produk p LEFT JOIN kategori k ON p.id_kategori = k.id_kategori ORDER BY p.kode_produk DESC");
    $daftar_produk = $query->fetchAll();
} catch (PDOException $e) {
    die("Gagal mengambil data produk: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transaksi Kasir - DrinkCashier</title>

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
      margin-bottom:35px;
      flex-wrap:wrap;
    }

    .topbar h1{
      font-size:36px;
      margin-bottom:10px;
    }

    .topbar p{
      color:rgba(255,255,255,0.6);
    }

    .search-box{
      width:320px;
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
      color:rgba(255,255,255,0.45);
    }

    /* CONTAINER */
    .transaction-container{
      display:grid;
      grid-template-columns:1.5fr 0.9fr;
      gap:30px;
    }

    /* PRODUCT SECTION */
    .product-section,
    .cart-section{
      background:rgba(255,255,255,0.08);
      border:1px solid rgba(255,255,255,0.08);
      backdrop-filter:blur(20px);
      border-radius:30px;
      padding:25px;
    }

    .section-title{
      display:flex;
      justify-content:space-between;
      align-items:center;
      margin-bottom:25px;
    }

    .section-title h2{
      font-size:24px;
    }

    .section-title i{
      font-size:28px;
      color:#ffb347;
    }

    /* PRODUCT GRID */
    .product-grid{
      display:grid;
      grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
      gap:20px;
    }

    .product-card{
      background:rgba(255,255,255,0.05);
      border:1px solid rgba(255,255,255,0.08);
      border-radius:25px;
      padding:20px;
      transition:0.3s;
      overflow:hidden;
      position:relative;
    }

    .product-card:hover{
      transform:translateY(-5px);
      border-color:#ff8a00;
    }

    .product-image{
      width:100%;
      height:170px;
      border-radius:20px;
      background:rgba(255,255,255,0.06);
      display:flex;
      justify-content:center;
      align-items:center;
      margin-bottom:18px;
    }

    .product-image i{
      font-size:70px;
      color:rgba(255,255,255,0.35);
    }

    .product-card h3{
      margin-bottom:8px;
      font-size:20px;
    }

    .product-card p{
      color:rgba(255,255,255,0.6);
      font-size:14px;
      margin-bottom:18px;
    }

    .price{
      font-size:22px;
      font-weight:700;
      margin-bottom:18px;
      color:#ffb347;
    }

    .add-cart-btn{
      width:100%;
      border:none;
      padding:14px;
      border-radius:16px;
      background:linear-gradient(135deg,#ff8a00,#ff0058);
      color:white;
      font-weight:600;
      cursor:pointer;
      transition:0.3s;
    }

    .add-cart-btn:hover{
      transform:translateY(-3px);
    }

    /* CART */
    .cart-items{
      display:flex;
      flex-direction:column;
      gap:18px;
      margin-bottom:25px;
    }

    .cart-item{
      background:rgba(255,255,255,0.05);
      border-radius:20px;
      padding:18px;
      display:flex;
      justify-content:space-between;
      align-items:center;
      gap:15px;
    }

    .cart-left{
      display:flex;
      align-items:center;
      gap:15px;
    }

    .cart-icon{
      width:55px;
      height:55px;
      border-radius:16px;
      background:rgba(255,255,255,0.08);
      display:flex;
      justify-content:center;
      align-items:center;
      font-size:24px;
    }

    .cart-info h4{
      margin-bottom:5px;
    }

    .cart-info p{
      color:rgba(255,255,255,0.5);
      font-size:13px;
    }

    .cart-price{
      color:#ffb347;
      font-weight:700;
    }

    /* PAYMENT */
    .payment-box{
      background:rgba(255,255,255,0.05);
      border-radius:25px;
      padding:25px;
    }

    .payment-row{
      display:flex;
      justify-content:space-between;
      margin-bottom:18px;
      color:rgba(255,255,255,0.75);
    }

    .payment-row.total{
      font-size:22px;
      font-weight:700;
      color:white;
      margin-top:20px;
      padding-top:20px;
      border-top:1px solid rgba(255,255,255,0.08);
    }

    .payment-input{
      margin-top:25px;
    }

    .payment-input label{
      display:block;
      margin-bottom:12px;
      color:#ffb347;
      font-size:14px;
    }

    .payment-input input{
      width:100%;
      border:none;
      outline:none;
      padding:16px 18px;
      border-radius:18px;
      background:rgba(255,255,255,0.08);
      color:white;
      border:1px solid rgba(255,255,255,0.08);
    }

    .pay-btn{
      width:100%;
      margin-top:25px;
      border:none;
      padding:18px;
      border-radius:20px;
      background:linear-gradient(135deg,#ff8a00,#ff0058);
      color:white;
      font-size:16px;
      font-weight:700;
      cursor:pointer;
      transition:0.3s;
      box-shadow:0 10px 25px rgba(255,100,0,0.25);
    }

    .pay-btn:hover{
      transform:translateY(-4px);
    }

    /* RESPONSIVE */
    @media(max-width:1100px){

      .transaction-container{
        grid-template-columns:1fr;
      }

    }

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

      .search-box{
        width:100%;
      }

      .topbar h1{
        font-size:28px;
      }

    }

  </style>
</head>
<body>

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

        <a href="kelola_produk.php">
          <i class="ri-shopping-bag-3-line"></i>
          <span>Kelola Data</span>
        </a>

        <a href="transaksi.php" class="active">
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

  </aside>

  <main class="main">

    <div class="topbar">

      <div>
        <h1>Transaksi Kasir 💳</h1>
        <p>Halaman transaksi modern untuk sistem kasir berbasis web.</p>
      </div>

      <div class="search-box">
        <i class="ri-search-line"></i>
        <input type="text" id="searchInput" placeholder="Cari produk..." oninput="filterProducts()">
      </div>

    </div>

    <div class="transaction-container">

      <!-- PRODUK -->
      <div class="product-section">

        <div class="section-title">
          <h2>Daftar Produk</h2>
          <i class="ri-store-2-line"></i>
        </div>

        <div class="product-grid" id="productGrid">
          <?php foreach ($daftar_produk as $produk): ?>
            <div class="product-card">
              <div class="product-image">
                <?php if (!empty($produk['gambar_produk'])): ?>
                  <img src="uploads/<?php echo htmlspecialchars($produk['gambar_produk']); ?>" alt="<?php echo htmlspecialchars($produk['nama_produk']); ?>" style="width:100%; height:100%; object-fit:cover; border-radius:20px;">
                <?php else: ?>
                  <i class="ri-cup-line"></i>
                <?php endif; ?>
              </div>

              <h3><?php echo htmlspecialchars($produk['nama_produk']); ?></h3>
              <p><?php echo htmlspecialchars($produk['nama_kategori'] ?? 'Tanpa Kategori'); ?> | Stok: <?php echo htmlspecialchars($produk['jumlah_stok']); ?></p>

              <div class="price">Rp <?php echo number_format($produk['harga_produk'], 0, ',', '.'); ?></div>

              <button class="add-cart-btn" onclick="addToCart(<?php echo htmlspecialchars(json_encode($produk)); ?>)">
                Tambah ke Keranjang
              </button>
            </div>
          <?php endforeach; ?>
        </div>

      </div>

      <!-- CART -->
      <div class="cart-section">

        <div class="section-title">
          <h2>Keranjang</h2>
          <i class="ri-shopping-cart-line"></i>
        </div>

        <div class="cart-items" id="cartItems">
          <div style="text-align: center; color: rgba(255,255,255,0.6); padding: 40px 20px;">
            Keranjang kosong
          </div>
        </div>

        <!-- PAYMENT -->
        <div class="payment-box">

          <div class="payment-row">
            <span>Subtotal</span>
            <span id="subtotal">Rp 0</span>
          </div>

          <div class="payment-row">
            <span>Diskon</span>
            <span id="discount">0%</span>
          </div>

          <div class="payment-row">
            <span>Kembalian</span>
            <span id="change">Rp 0</span>
          </div>

          <div class="payment-row total">
            <span>Total Bayar</span>
            <span id="total">Rp 0</span>
          </div>

          <div class="payment-input">
            <label>Uang Bayar</label>
            <input type="number" id="uangBayar" placeholder="Masukkan uang bayar" oninput="calculateChange()">
          </div>

          <button class="pay-btn" id="payBtn" onclick="processPayment()">
            Bayar Sekarang
          </button>

        </div>

      </div>

    </div>

  </main>

</body>
<script>
// Keranjang disimpan di localStorage untuk setiap sesi
let cart = JSON.parse(localStorage.getItem('cart')) || [];

function addToCart(product) {
  const existingItem = cart.find(item => item.id_produk === product.id_produk);
  
  if (existingItem) {
    existingItem.qty += 1;
  } else {
    cart.push({
      id_produk: product.id_produk,
      kode_produk: product.kode_produk,
      nama_produk: product.nama_produk,
      harga_produk: product.harga_produk,
      qty: 1
    });
  }
  
  localStorage.setItem('cart', JSON.stringify(cart));
  updateCart();
  
  // Tampilkan notifikasi
  alert(product.nama_produk + ' berhasil ditambahkan ke keranjang');
}

function removeFromCart(index) {
  cart.splice(index, 1);
  localStorage.setItem('cart', JSON.stringify(cart));
  updateCart();
}

function updateQuantity(index, qty) {
  if (qty <= 0) {
    removeFromCart(index);
  } else {
    cart[index].qty = qty;
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCart();
  }
}

function updateCart() {
  const cartContainer = document.getElementById('cartItems');
  
  if (cart.length === 0) {
    cartContainer.innerHTML = '<div style="text-align: center; color: rgba(255,255,255,0.6); padding: 40px 20px;">Keranjang kosong</div>';
    updatePayment();
    return;
  }
  
  let html = '';
  cart.forEach((item, index) => {
    const subtotal = item.harga_produk * item.qty;
    html += `
      <div class="cart-item">
        <div class="cart-left">
          <div class="cart-icon">
            <i class="ri-cup-line"></i>
          </div>
          <div class="cart-info">
            <h4>${item.nama_produk}</h4>
            <p>Harga: Rp ${new Intl.NumberFormat('id-ID').format(item.harga_produk)}</p>
            <div style="margin-top: 8px; display: flex; gap: 8px;">
              <button onclick="updateQuantity(${index}, ${item.qty - 1})" style="width: 30px; height: 30px; border: none; background: rgba(255,255,255,0.1); border-radius: 8px; color: white; cursor: pointer;">-</button>
              <input type="number" value="${item.qty}" onchange="updateQuantity(${index}, parseInt(this.value))" style="width: 50px; background: rgba(255,255,255,0.1); border: none; border-radius: 8px; color: white; text-align: center;">
              <button onclick="updateQuantity(${index}, ${item.qty + 1})" style="width: 30px; height: 30px; border: none; background: rgba(255,255,255,0.1); border-radius: 8px; color: white; cursor: pointer;">+</button>
              <button onclick="removeFromCart(${index})" style="flex: 1; border: none; background: rgba(255, 100, 0, 0.3); border-radius: 8px; color: white; cursor: pointer; font-size: 12px;">Hapus</button>
            </div>
          </div>
        </div>
        <div class="cart-price">
          Rp ${new Intl.NumberFormat('id-ID').format(subtotal)}
        </div>
      </div>
    `;
  });
  
  cartContainer.innerHTML = html;
  updatePayment();
}

function updatePayment() {
  let subtotal = 0;
  cart.forEach(item => {
    subtotal += item.harga_produk * item.qty;
  });
  
  document.getElementById('subtotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
  document.getElementById('total').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
  
  calculateChange();
}

function calculateChange() {
  let subtotal = 0;
  cart.forEach(item => {
    subtotal += item.harga_produk * item.qty;
  });
  
  const uangBayar = parseInt(document.getElementById('uangBayar').value) || 0;
  const kembalian = uangBayar - subtotal;
  
  document.getElementById('change').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(kembalian >= 0 ? kembalian : 0);
  
  // Disable button jika uang tidak cukup atau keranjang kosong
  const payBtn = document.getElementById('payBtn');
  if (cart.length === 0 || uangBayar < subtotal) {
    payBtn.style.opacity = '0.5';
    payBtn.style.cursor = 'not-allowed';
    payBtn.disabled = true;
  } else {
    payBtn.style.opacity = '1';
    payBtn.style.cursor = 'pointer';
    payBtn.disabled = false;
  }
}

function processPayment() {
  if (cart.length === 0) {
    alert('Keranjang kosong! Silakan tambahkan produk.');
    return;
  }
  
  let subtotal = 0;
  cart.forEach(item => {
    subtotal += item.harga_produk * item.qty;
  });
  
  const uangBayar = parseInt(document.getElementById('uangBayar').value) || 0;
  
  if (uangBayar < subtotal) {
    alert('Uang bayar tidak cukup!');
    return;
  }
  
  // Kirim data ke server sebelum redirect
  fetch('proses_transaksi.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      cart: cart,
      subtotal: subtotal,
      uangBayar: uangBayar,
      kembalian: uangBayar - subtotal
    })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      // Redirect ke struk dengan invoice_id
      window.location.href = 'struk.php?invoice_id=' + data.invoice_id;
    } else {
      alert('Gagal memproses pembayaran: ' + data.message);
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Terjadi kesalahan saat memproses pembayaran');
  });
}

// Inisialisasi keranjang saat halaman dimuat
window.addEventListener('load', function() {
  updateCart();
});

// Filter produk berdasarkan search
function filterProducts() {
  const searchInput = document.getElementById('searchInput').value.toLowerCase();
  const productCards = document.querySelectorAll('.product-card');
  
  productCards.forEach(card => {
    const productName = card.querySelector('h3').textContent.toLowerCase();
    const productDesc = card.querySelector('p').textContent.toLowerCase();
    
    if (productName.includes(searchInput) || productDesc.includes(searchInput)) {
      card.style.display = 'block';
    } else {
      card.style.display = 'none';
    }
  });
}
</script>
</html>