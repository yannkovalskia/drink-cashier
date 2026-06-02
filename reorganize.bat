@echo off
REM ====================================================================
REM Script Reorganisasi Folder DrinkCashier
REM Jalankan file ini di Command Prompt untuk membuat struktur folder
REM ====================================================================

setlocal enabledelayedexpansion
cd /d "%~dp0"

echo.
echo ========================================
echo  REORGANISASI STRUKTUR FOLDER PROYEK
echo ========================================
echo.

REM Membuat direktori
echo [1/11] Membuat folder: src\config
mkdir "src\config" 2>nul
if exist "src\config" (echo     ✅ Berhasil) else (echo     ❌ Gagal)

echo [2/11] Membuat folder: src\core
mkdir "src\core" 2>nul
if exist "src\core" (echo     ✅ Berhasil) else (echo     ❌ Gagal)

echo [3/11] Membuat folder: src\pages
mkdir "src\pages" 2>nul
if exist "src\pages" (echo     ✅ Berhasil) else (echo     ❌ Gagal)

echo [4/11] Membuat folder: src\process
mkdir "src\process" 2>nul
if exist "src\process" (echo     ✅ Berhasil) else (echo     ❌ Gagal)

echo [5/11] Membuat folder: docs
mkdir "docs" 2>nul
if exist "docs" (echo     ✅ Berhasil) else (echo     ❌ Gagal)

echo [6/11] Membuat folder: uploads (jika belum ada)
mkdir "uploads" 2>nul
if exist "uploads" (echo     ✅ Berhasil) else (echo     ❌ Gagal)

REM Copy config files
echo.
echo [7/11] Memindahkan config files ke src\config
if exist "koneksi.php" move /Y "koneksi.php" "src\config\" >nul 2>&1 & echo     ✅ koneksi.php
if exist "setup_database.php" move /Y "setup_database.php" "src\config\" >nul 2>&1 & echo     ✅ setup_database.php

REM Copy core files
echo [8/11] Memindahkan core files ke src\core
if exist "system_check.php" move /Y "system_check.php" "src\core\" >nul 2>&1 & echo     ✅ system_check.php
if exist "bersihkan_session.php" move /Y "bersihkan_session.php" "src\core\" >nul 2>&1 & echo     ✅ bersihkan_session.php

REM Copy page files
echo [9/11] Memindahkan page files ke src\pages
if exist "dashboard.php" move /Y "dashboard.php" "src\pages\" >nul 2>&1 & echo     ✅ dashboard.php
if exist "halaman_login.php" move /Y "halaman_login.php" "src\pages\" >nul 2>&1 & echo     ✅ halaman_login.php
if exist "tambah_produk.php" move /Y "tambah_produk.php" "src\pages\" >nul 2>&1 & echo     ✅ tambah_produk.php
if exist "edit_produk.php" move /Y "edit_produk.php" "src\pages\" >nul 2>&1 & echo     ✅ edit_produk.php
if exist "kelola_produk.php" move /Y "kelola_produk.php" "src\pages\" >nul 2>&1 & echo     ✅ kelola_produk.php
if exist "transaksi.php" move /Y "transaksi.php" "src\pages\" >nul 2>&1 & echo     ✅ transaksi.php
if exist "struk.php" move /Y "struk.php" "src\pages\" >nul 2>&1 & echo     ✅ struk.php
if exist "Analitik.php" move /Y "Analitik.php" "src\pages\" >nul 2>&1 & echo     ✅ Analitik.php

REM Copy process files
echo [10/11] Memindahkan process files ke src\process
if exist "proses_edit_produk.php" move /Y "proses_edit_produk.php" "src\process\" >nul 2>&1 & echo     ✅ proses_edit_produk.php
if exist "proses_tambah_produk.php" move /Y "proses_tambah_produk.php" "src\process\" >nul 2>&1 & echo     ✅ proses_tambah_produk.php
if exist "proses_transaksi.php" move /Y "proses_transaksi.php" "src\process\" >nul 2>&1 & echo     ✅ proses_transaksi.php
if exist "hapus_produk.php" move /Y "hapus_produk.php" "src\process\" >nul 2>&1 & echo     ✅ hapus_produk.php
if exist "logout.php" move /Y "logout.php" "src\process\" >nul 2>&1 & echo     ✅ logout.php

REM Copy docs
echo [11/11] Memindahkan dokumentasi ke docs
if exist "DOKUMENTASI_TRANSAKSI.md" move /Y "DOKUMENTASI_TRANSAKSI.md" "docs\" >nul 2>&1 & echo     ✅ DOKUMENTASI_TRANSAKSI.md
if exist "PANDUAN_FINAL.md" move /Y "PANDUAN_FINAL.md" "docs\" >nul 2>&1 & echo     ✅ PANDUAN_FINAL.md
if exist "RINGKASAN_PERUBAHAN.md" move /Y "RINGKASAN_PERUBAHAN.md" "docs\" >nul 2>&1 & echo     ✅ RINGKASAN_PERUBAHAN.md
if exist "STRUKTUR_FOLDER_BARU.md" move /Y "STRUKTUR_FOLDER_BARU.md" "docs\" >nul 2>&1 & echo     ✅ STRUKTUR_FOLDER_BARU.md

echo.
echo ========================================
echo  REORGANISASI SELESAI!
echo ========================================
echo.
echo Struktur folder baru:
echo   📁 src/
echo      ├── config/      (Database & Setup)
echo      ├── core/        (System files)
echo      ├── pages/       (UI Pages)
echo      └── process/     (Handlers)
echo   📁 docs/            (Documentation)
echo   📁 uploads/         (Product images)
echo   📄 README.md        (Main documentation)
echo   📄 index.php        (Entry point)
echo.
pause
