# 📋 Panduan Reorganisasi Struktur Folder

Dokumen ini menjelaskan cara mengorganisir proyek DrinkCashier dengan struktur profesional.

## 🎯 Tujuan Reorganisasi

✅ Memisahkan concern: config, core, pages, process  
✅ Mudah di-maintain  
✅ Scalable untuk fitur baru  
✅ Mengikuti best practices industri  

---

## 📁 Struktur Target

```
tugas akhir/
├── index.php                        # Router utama
├── README.md                        # Dokumentasi
├── uploads/                         # Gambar produk
│
├── src/
│   ├── config/
│   │   ├── koneksi.php
│   │   └── setup_database.php
│   │
│   ├── core/
│   │   ├── system_check.php
│   │   └── bersihkan_session.php
│   │
│   ├── pages/
│   │   ├── halaman_login.php
│   │   ├── dashboard.php
│   │   ├── kelola_produk.php
│   │   ├── tambah_produk.php
│   │   ├── edit_produk.php
│   │   ├── transaksi.php
│   │   ├── struk.php
│   │   └── Analitik.php
│   │
│   └── process/
│       ├── proses_tambah_produk.php
│       ├── proses_edit_produk.php
│       ├── hapus_produk.php
│       ├── proses_transaksi.php
│       └── logout.php
│
├── docs/
│   ├── DOKUMENTASI_TRANSAKSI.md
│   ├── PANDUAN_FINAL.md
│   ├── RINGKASAN_PERUBAHAN.md
│   └── README_STRUKTUR.md (file ini)
│
└── setup_folders.php               # Utility untuk create folders
```

---

## 🚀 Langkah-Langkah Reorganisasi

### **Opsi 1: Menggunakan Batch Script (Windows) - REKOMENDASI**

1. Buka Command Prompt / Terminal
2. Navigasi ke folder proyek:
   ```cmd
   cd C:\xampp\htdocs\tugas\ akhir
   ```
3. Jalankan script batch:
   ```cmd
   reorganize.bat
   ```
4. Script akan membuat folder dan memindahkan semua file otomatis
5. Selesai! ✅

### **Opsi 2: Manual (Semua Platform)**

**Pindahkan file sesuai struktur di atas:**

**Config:**
- koneksi.php → src/config/
- setup_database.php → src/config/

**Core:**
- system_check.php → src/core/
- bersihkan_session.php → src/core/

**Pages:**
- dashboard.php → src/pages/
- halaman_login.php → src/pages/
- tambah_produk.php → src/pages/
- edit_produk.php → src/pages/
- kelola_produk.php → src/pages/
- transaksi.php → src/pages/
- struk.php → src/pages/
- Analitik.php → src/pages/

**Process:**
- proses_tambah_produk.php → src/process/
- proses_edit_produk.php → src/process/
- hapus_produk.php → src/process/
- proses_transaksi.php → src/process/
- logout.php → src/process/

**Docs:**
- DOKUMENTASI_TRANSAKSI.md → docs/
- PANDUAN_FINAL.md → docs/
- RINGKASAN_PERUBAHAN.md → docs/

---

## 🔗 Update Include Paths

Setelah reorganisasi, **UPDATE PATH INCLUDE** di setiap file!

### **Path Relatif Cheat Sheet:**

```
Dari src/pages/X.php       → include '../config/koneksi.php';
Dari src/process/X.php     → include '../config/koneksi.php';
Dari src/core/X.php        → include '../config/koneksi.php';
Dari src/pages/X.php       → header("Location: ../pages/Y.php");
Dari src/process/X.php     → header("Location: ../pages/Y.php");
Dari root/index.php        → header("Location: src/pages/dashboard.php");
```

---

## ✅ Verifikasi Reorganisasi

Setelah selesai, pastikan:

1. ✅ Folder `src/`, `docs/`, `uploads/` ada
2. ✅ Subfolder di dalam `src/` ada
3. ✅ Semua file sudah pindah ke folder yang sesuai
4. ✅ File `index.php` di root
5. ✅ File `README.md` di root
6. ✅ Semua path include sudah di-update
7. ✅ Coba akses: `http://localhost/tugas%20akhir/` → harus redirect ke login

---

## 🎉 Done!

Selamat! Proyek sekarang terlihat **profesional**, **tertata**, dan **mudah dimaintain**! 🚀
