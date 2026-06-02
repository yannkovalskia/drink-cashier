@echo off
REM Membuat struktur folder
mkdir "src\config" 2>nul
mkdir "src\core" 2>nul
mkdir "src\pages" 2>nul
mkdir "src\process" 2>nul
mkdir "docs" 2>nul

REM Move config files
if exist "koneksi.php" move "koneksi.php" "src\config\"
if exist "setup_database.php" move "setup_database.php" "src\config\"

REM Move core files
if exist "system_check.php" move "system_check.php" "src\core\"
if exist "bersihkan_session.php" move "bersihkan_session.php" "src\core\"

REM Move documentation
if exist "DOKUMENTASI_TRANSAKSI.md" move "DOKUMENTASI_TRANSAKSI.md" "docs\"
if exist "PANDUAN_FINAL.md" move "PANDUAN_FINAL.md" "docs\"
if exist "RINGKASAN_PERUBAHAN.md" move "RINGKASAN_PERUBAHAN.md" "docs\"

REM Move pages
if exist "dashboard.php" move "dashboard.php" "src\pages\"
if exist "halaman_login.php" move "halaman_login.php" "src\pages\"
if exist "tambah_produk.php" move "tambah_produk.php" "src\pages\"
if exist "edit_produk.php" move "edit_produk.php" "src\pages\"
if exist "kelola_produk.php" move "kelola_produk.php" "src\pages\"
if exist "transaksi.php" move "transaksi.php" "src\pages\"
if exist "struk.php" move "struk.php" "src\pages\"
if exist "Analitik.php" move "Analitik.php" "src\pages\"

REM Move process files
if exist "proses_edit_produk.php" move "proses_edit_produk.php" "src\process\"
if exist "proses_tambah_produk.php" move "proses_tambah_produk.php" "src\process\"
if exist "proses_transaksi.php" move "proses_transaksi.php" "src\process\"
if exist "hapus_produk.php" move "hapus_produk.php" "src\process\"
if exist "logout.php" move "logout.php" "src\process\"

echo Folder structure created successfully!
pause
