# 🍹 DrinkCashier - Quick Start Guide

Sistem kasir minuman berbasis web yang sudah tertata dengan profesional!

---

## ⚡ Quick Setup (5 menit)

### 1. **Akses Aplikasi**
```
http://localhost/tugas%20akhir/
```

### 2. **Login Pertama Kali**
```
Username: admin
Password: admin123
```

### 3. **Verifikasi Sistem**
```
http://localhost/tugas%20akhir/src/core/system_check.php
```

---

## 📁 Struktur Folder

```
tugas akhir/
├── index.php                 ← Buka dari sini
├── README.md                 ← Dokumentasi lengkap
├── COMPLETION_REPORT.md      ← Laporan reorganisasi
│
├── src/
│   ├── config/              ← Database & Setup
│   ├── core/                ← System check & utilities
│   ├── pages/               ← UI (login, dashboard, produk, transaksi)
│   └── process/             ← Handlers & business logic
│
├── docs/                    ← Dokumentasi tambahan
└── uploads/                 ← Gambar produk
```

---

## 🔧 Fitur-Fitur Utama

### ✅ **Dashboard**
- Statistik produk, stok, kategori
- Navigasi ke semua halaman

### ✅ **Kelola Produk**
- Tambah produk baru ➕
- Edit produk ✏️
- Hapus produk ❌
- Upload gambar produk
- Lihat daftar lengkap

### ✅ **Transaksi**
- Pilih produk
- Tambah ke keranjang
- Hitung total otomatis
- Proses pembayaran
- Lihat struk receipt

### ✅ **Sistem Login**
- Username/Password validation
- Session management
- Logout

---

## 📊 Database

**Database:** `my_kasir`

**Tabel:**
1. **users** - User login
2. **kategori** - Kategori produk
3. **produk** - Daftar produk (FK → kategori)
4. **transaksi** - Riwayat transaksi (FK → users)

---

## 🐛 Troubleshooting

| Problem | Solution |
|---------|----------|
| Halaman blank | Pastikan MySQL running, cek `src/core/system_check.php` |
| Login error | Username/password salah atau belum di database |
| Upload gambar error | Cek permission `uploads/` folder (chmod 777) |
| Path error | Pastikan file sudah pindah ke folder baru |

---

## 📚 Dokumentasi Lengkap

- `README.md` - Dokumentasi utama
- `COMPLETION_REPORT.md` - Laporan reorganisasi
- `docs/README_STRUKTUR.md` - Panduan struktur folder
- `docs/DOKUMENTASI_TRANSAKSI.md` - Detail transaksi
- `docs/PANDUAN_FINAL.md` - User guide
- `docs/RINGKASAN_PERUBAHAN.md` - Change summary

---

## 🚀 Next Steps

1. Setup database (SQL schema di README.md)
2. Login dengan admin/admin123
3. Tambah kategori & produk
4. Coba transaksi
5. Customize sesuai kebutuhan

---

**Version:** 2.0  
**Status:** ✅ Production Ready  
**Last Updated:** 2 Juni 2026

Selamat menggunakan! 🎉
