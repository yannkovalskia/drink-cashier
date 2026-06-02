#!/usr/bin/env powershell
<#
.SYNOPSIS
    DrinkCashier File Reorganization Script
    Menata ulang struktur folder proyek dengan cara profesional
.DESCRIPTION
    Script ini mengotomatis memindahkan file ke folder yang sesuai
.NOTES
    Author: DrinkCashier System
    Version: 1.0
    Run: .\reorganize.ps1
#>

param([switch]$Force)

$ErrorActionPreference = "Continue"
$basePath = Get-Location
$timestamp = Get-Date -Format "yyyy-MM-dd_HH-mm-ss"

Write-Host ""
Write-Host "╔══════════════════════════════════════════════════════════════╗"
Write-Host "║          🍹 REORGANISASI STRUKTUR FOLDER DRINKCASHIER       ║"
Write-Host "╚══════════════════════════════════════════════════════════════╝"
Write-Host ""

# Mapping files to new locations
$fileMoves = @{
    # Config files
    "koneksi.php" = "src\config\"
    "setup_database.php" = "src\config\"
    
    # Core files
    "system_check.php" = "src\core\"
    "bersihkan_session.php" = "src\core\"
    
    # Page files
    "dashboard.php" = "src\pages\"
    "halaman_login.php" = "src\pages\"
    "tambah_produk.php" = "src\pages\"
    "edit_produk.php" = "src\pages\"
    "kelola_produk.php" = "src\pages\"
    "transaksi.php" = "src\pages\"
    "struk.php" = "src\pages\"
    "Analitik.php" = "src\pages\"
    
    # Process files
    "proses_tambah_produk.php" = "src\process\"
    "proses_edit_produk.php" = "src\process\"
    "hapus_produk.php" = "src\process\"
    "proses_transaksi.php" = "src\process\"
    "logout.php" = "src\process\"
    
    # Docs
    "DOKUMENTASI_TRANSAKSI.md" = "docs\"
    "PANDUAN_FINAL.md" = "docs\"
    "RINGKASAN_PERUBAHAN.md" = "docs\"
}

$movedCount = 0
$skippedCount = 0
$errorCount = 0

Write-Host "📦 Memindahkan file..." -ForegroundColor Cyan
Write-Host ""

foreach ($file in $fileMoves.Keys) {
    $sourcePath = Join-Path $basePath $file
    $destFolder = Join-Path $basePath $fileMoves[$file]
    $destPath = Join-Path $destFolder $file
    
    if (Test-Path $sourcePath) {
        try {
            if (!(Test-Path $destFolder)) {
                New-Item -ItemType Directory -Path $destFolder -Force | Out-Null
            }
            
            Move-Item -Path $sourcePath -Destination $destPath -Force
            Write-Host "  ✅ $file → $($fileMoves[$file])" -ForegroundColor Green
            $movedCount++
        }
        catch {
            Write-Host "  ❌ ERROR: $file - $_" -ForegroundColor Red
            $errorCount++
        }
    }
    else {
        Write-Host "  ⏭️  $file (tidak ditemukan)" -ForegroundColor Yellow
        $skippedCount++
    }
}

Write-Host ""
Write-Host "╔══════════════════════════════════════════════════════════════╗"
Write-Host "║                      📊 SUMMARY REORGANISASI                 ║"
Write-Host "╚══════════════════════════════════════════════════════════════╝"
Write-Host ""
Write-Host "  ✅ Dipindahkan : $movedCount file"
Write-Host "  ⏭️  Tidak ada   : $skippedCount file"
Write-Host "  ❌ Error      : $errorCount file"
Write-Host ""

Write-Host "📁 Struktur folder baru:" -ForegroundColor Cyan
Write-Host ""
Write-Host "  tugas akhir/"
Write-Host "  ├── 📄 index.php"
Write-Host "  ├── 📄 README.md"
Write-Host "  ├── 📄 reorganize.bat"
Write-Host "  ├── 📁 src/"
Write-Host "  │   ├── config/       (koneksi, setup)"
Write-Host "  │   ├── core/         (system check, session)"
Write-Host "  │   ├── pages/        (login, dashboard, produk, transaksi)"
Write-Host "  │   └── process/      (handlers)"
Write-Host "  ├── 📁 docs/          (dokumentasi)"
Write-Host "  ├── 📁 uploads/       (gambar produk)"
Write-Host "  └── 📁 .old/          (backup file lama - optional)"
Write-Host ""

Write-Host "🔗 Path Update Required:" -ForegroundColor Yellow
Write-Host ""
Write-Host "  Dari src/pages/X.php       → include '../config/koneksi.php';"
Write-Host "  Dari src/process/X.php     → include '../config/koneksi.php';"
Write-Host "  Dari src/core/X.php        → include '../config/koneksi.php';"
Write-Host ""

Write-Host "✅ Reorganisasi selesai! Proyek sudah tertata dengan profesional." -ForegroundColor Green
Write-Host ""
