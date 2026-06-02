<?php
ob_start();
session_start();

include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error_msg = "Username dan password tidak boleh kosong!";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch();

            if ($user) {
                $stored_password = $user['password'];
                $password_matches = false;
                
                if (strpos($stored_password, '$2') === 0) {
                    $password_matches = password_verify($password, $stored_password);
                } else {
                    $password_matches = ($password === $stored_password);
                }
                
                if ($password_matches) {
                    $_SESSION['user_id']  = $user['id_user'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['is_logged_in'] = true;

                    ob_end_clean();
                    header("Location: dashboard.php");
                    exit;
                } else {
                    $error_msg = "Username atau password salah!";
                }
            } else {
                $error_msg = "Username atau password salah!";
            }

        } catch (PDOException $e) {
            $error_msg = "Terjadi kesalahan: " . $e->getMessage();
        }
    }
}
ob_end_clean();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Kasir Minuman</title>

  <!-- Google Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Remix Icon -->
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">

  <style>
    *{
      margin:0;
      padding:0;
      box-sizing:border-box;
      font-family:'Poppins',sans-serif;
    }

    body{
      width:100%;
      min-height:100vh;
      overflow:hidden;
      position:relative;
      display:flex;
      justify-content:center;
      align-items:center;
      background:#000;
    }

    /* VIDEO BACKGROUND */
    .bg-video{
      position:fixed;
      right:0;
      bottom:0;
      min-width:100%;
      min-height:100%;
      object-fit:cover;
      z-index:-3;
      filter:brightness(0.45);
    }

    /* DARK OVERLAY */
    .overlay{
      position:fixed;
      inset:0;
      background:linear-gradient(
        135deg,
        rgba(0,0,0,0.75),
        rgba(0,0,0,0.35)
      );
      z-index:-2;
    }

    /* FLOATING BLUR */
    .blur1,
    .blur2,
    .blur3{
      position:absolute;
      border-radius:50%;
      filter:blur(80px);
      animation:float 8s infinite ease-in-out;
    }

    .blur1{
      width:300px;
      height:300px;
      background:#ff8a00;
      top:-80px;
      left:-50px;
    }

    .blur2{
      width:250px;
      height:250px;
      background:#ff2e63;
      bottom:-50px;
      right:-40px;
      animation-delay:2s;
    }

    .blur3{
      width:220px;
      height:220px;
      background:#00d4ff;
      top:40%;
      right:15%;
      animation-delay:4s;
    }

    @keyframes float{
      0%,100%{
        transform:translateY(0px) translateX(0px);
      }
      50%{
        transform:translateY(-20px) translateX(15px);
      }
    }

    /* LOGIN CARD */
    .login-container{
      width:420px;
      padding:40px;
      border-radius:30px;
      backdrop-filter:blur(20px);
      background:rgba(255,255,255,0.08);
      border:1px solid rgba(255,255,255,0.15);
      box-shadow:
        0 10px 40px rgba(0,0,0,0.4),
        inset 0 0 10px rgba(255,255,255,0.05);
      position:relative;
      overflow:hidden;
      animation:fadeIn 1s ease;
    }

    @keyframes fadeIn{
      from{
        opacity:0;
        transform:translateY(30px);
      }
      to{
        opacity:1;
        transform:translateY(0);
      }
    }

    .shine{
      position:absolute;
      width:200px;
      height:600px;
      background:rgba(255,255,255,0.08);
      transform:rotate(25deg);
      top:-150px;
      left:-180px;
      animation:shine 6s infinite linear;
    }

    @keyframes shine{
      0%{
        left:-250px;
      }
      100%{
        left:600px;
      }
    }

    .logo{
      width:90px;
      height:90px;
      border-radius:50%;
      background:linear-gradient(135deg,#ff9f43,#ff6b6b);
      display:flex;
      justify-content:center;
      align-items:center;
      margin:0 auto 20px;
      font-size:40px;
      color:white;
      box-shadow:0 10px 30px rgba(255,150,50,0.4);
    }

    .title{
      text-align:center;
      color:white;
      font-size:30px;
      font-weight:700;
      margin-bottom:5px;
    }

    .subtitle{
      text-align:center;
      color:rgba(255,255,255,0.7);
      margin-bottom:35px;
      font-size:14px;
    }

    .input-group{
      margin-bottom:22px;
      position:relative;
    }

    .input-group i{
      position:absolute;
      top:50%;
      left:18px;
      transform:translateY(-50%);
      color:rgba(255,255,255,0.7);
      font-size:18px;
    }

    .input-group input{
      width:100%;
      height:58px;
      padding:0 20px 0 52px;
      border:none;
      outline:none;
      border-radius:18px;
      background:rgba(255,255,255,0.1);
      color:white;
      font-size:15px;
      border:1px solid rgba(255,255,255,0.1);
      transition:0.3s;
    }

    .input-group input::placeholder{
      color:rgba(255,255,255,0.6);
    }

    .input-group input:focus{
      border-color:#ff9f43;
      background:rgba(255,255,255,0.15);
      box-shadow:0 0 15px rgba(255,159,67,0.3);
    }

    .options{
      display:flex;
      justify-content:space-between;
      align-items:center;
      margin-bottom:25px;
      color:white;
      font-size:13px;
    }

    .options label{
      display:flex;
      align-items:center;
      gap:8px;
      cursor:pointer;
    }

    .options a{
      color:#ffb347;
      text-decoration:none;
    }

    .login-btn{
      width:100%;
      height:58px;
      border:none;
      border-radius:18px;
      background:linear-gradient(135deg,#ff9f43,#ff5e62);
      color:white;
      font-size:17px;
      font-weight:600;
      cursor:pointer;
      transition:0.4s;
      box-shadow:0 10px 25px rgba(255,120,50,0.35);
    }

    .login-btn:hover{
      transform:translateY(-3px);
      box-shadow:0 15px 35px rgba(255,120,50,0.5);
    }

    .divider{
      text-align:center;
      color:rgba(255,255,255,0.5);
      margin:28px 0 18px;
      position:relative;
    }

    .divider::before,
    .divider::after{
      content:'';
      position:absolute;
      width:35%;
      height:1px;
      background:rgba(255,255,255,0.15);
      top:50%;
    }

    .divider::before{
      left:0;
    }

    .divider::after{
      right:0;
    }

    .social-login{
      display:flex;
      justify-content:center;
      gap:15px;
    }

    .social-login button{
      width:52px;
      height:52px;
      border:none;
      border-radius:16px;
      background:rgba(255,255,255,0.1);
      color:white;
      font-size:20px;
      cursor:pointer;
      transition:0.3s;
      border:1px solid rgba(255,255,255,0.1);
    }

    .social-login button:hover{
      background:rgba(255,255,255,0.18);
      transform:translateY(-3px);
    }

    .footer-text{
      text-align:center;
      margin-top:25px;
      color:rgba(255,255,255,0.7);
      font-size:13px;
    }

    .footer-text a{
      color:#ffb347;
      text-decoration:none;
      font-weight:600;
    }

    /* CUSTOM WALLPAPER BUTTON */
    .custom-wallpaper{
      position:fixed;
      top:20px;
      right:20px;
      z-index:10;
    }

    .custom-wallpaper label{
      background:rgba(255,255,255,0.12);
      border:1px solid rgba(255,255,255,0.15);
      color:white;
      padding:12px 18px;
      border-radius:15px;
      cursor:pointer;
      backdrop-filter:blur(10px);
      transition:0.3s;
      display:flex;
      align-items:center;
      gap:10px;
      font-size:14px;
    }

    .custom-wallpaper label:hover{
      background:rgba(255,255,255,0.2);
    }

    .custom-wallpaper input{
      display:none;
    }

    @media(max-width:500px){
      .login-container{
        width:92%;
        padding:30px 25px;
      }

      .title{
        font-size:25px;
      }
    }
  </style>
</head>
<body>

  <!-- CUSTOM WALLPAPER -->
  <div class="custom-wallpaper">
    <label for="wallpaperInput">
      <i class="ri-image-edit-line"></i>
      Ganti Wallpaper
    </label>
    <input type="file" id="wallpaperInput" accept="video/*,image/*">
  </div>

  <!-- VIDEO BACKGROUND -->
  <video autoplay muted loop class="bg-video" id="bgVideo">
    <source src="https://cdn.coverr.co/videos/coverr-making-a-cocktail-1563615397609?download=1080p" type="video/mp4">
  </video>

  <div class="overlay"></div>

  <!-- BLUR EFFECT -->
  <div class="blur1"></div>
  <div class="blur2"></div>
  <div class="blur3"></div>

  <!-- LOGIN CARD -->
  <div class="login-container">

    <div class="shine"></div>

    <div class="logo">
      <i class="ri-cup-line"></i>
    </div>

    <h1 class="title">DrinkCashier</h1>
    <p class="subtitle">Sistem Penjualan Minuman Modern</p>

    <?php if (isset($error_msg)): ?>
      <div style="background:rgba(255,100,100,0.2); border:1px solid rgba(255,100,100,0.5); padding:12px 15px; border-radius:10px; margin-bottom:20px; color:#ff6b6b; font-size:14px; text-align:center;">
        <?php echo htmlspecialchars($error_msg); ?>
      </div>
    <?php endif; ?>

    <form method="POST">

      <div class="input-group">
        <i class="ri-user-3-line"></i>
        <input type="text" name="username" placeholder="Masukkan Username">
      </div>

      <div class="input-group">
        <i class="ri-lock-password-line"></i>
        <input type="password" name="password" placeholder="Masukkan Password">
      </div>

      <div class="options">
        <label>
          <input type="checkbox">
          Ingat Saya
        </label>

        <a href="#">Lupa Password?</a>
      </div>

      <button type="submit" class="login-btn">
        <i class="ri-login-circle-line"></i>
        Login Sekarang
      </button>

    </form>

    <div class="divider">atau login dengan</div>

    <div class="social-login">
      <button type="button"><i class="ri-google-fill"></i></button>
      <button type="button"><i class="ri-facebook-fill"></i></button>
      <button type="button"><i class="ri-github-fill"></i></button>
    </div>

    <div class="footer-text">
      © 2026 DrinkCashier | Designed By Ilzam Team
    </div>

  </div>

  <script>
    const wallpaperInput = document.getElementById('wallpaperInput');
    const bgVideo = document.getElementById('bgVideo');

    wallpaperInput.addEventListener('change', function(e){
      const file = e.target.files[0];
      if(!file) return;

      const fileURL = URL.createObjectURL(file);

      // jika video
      if(file.type.startsWith('video')){
        bgVideo.style.display = 'block';
        bgVideo.src = fileURL;
        bgVideo.play();
        document.body.style.backgroundImage = 'none';
      }

      // jika gambar
      else if(file.type.startsWith('image')){
        bgVideo.pause();
        bgVideo.style.display = 'none';
        document.body.style.backgroundImage = `url(${fileURL})`;
        document.body.style.backgroundSize = 'cover';
        document.body.style.backgroundPosition = 'center';
      }
    });
  </script>

</body>
</html>


