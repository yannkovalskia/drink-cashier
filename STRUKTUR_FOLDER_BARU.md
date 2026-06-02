# 📁 Struktur Folder Baru - DrinkCashier

Proyek ini telah diorganisir dengan struktur folder yang lebih rapi dan profesional.

## Struktur Direktori

```
tugas akhir/
│
├── README.md                                    # Dokumentasi utama
├── uploads/                                     # Folder gambar produk
│
├── src/                                         # Source code PHP
│   ├── config/
│   │   ├── koneksi.php                         # Database connection
│   │   └── setup_database.php                  # Setup database/tables
│   │
│   ├── core/
│   │   ├── system_check.php                    # System verification
│   │   └── bersihkan_session.php               # Session cleanup
│   │
│   ├── pages/
│   │   ├── halaman_login.php                   # Login page
│   │   ├── dashboard.php                       # Main dashboard
│   │   ├── kelola_produk.php                   # Product list
│   │   ├── tambah_produk.php                   # Add product form
│   │   ├── edit_produk.php                     # Edit product form
│   │   ├── transaksi.php                       # Transaction page
│   │   ├── struk.php                           # Receipt page
│   │   └── Analitik.php                        # Analytics page
│   │
│   └── process/
│       ├── proses_tambah_produk.php            # Add product handler
│       ├── proses_edit_produk.php              # Edit product handler
│       ├── hapus_produk.php                    # Delete product handler
│       ├── proses_transaksi.php                # Process transaction
│       └── logout.php                          # Logout handler
│
└── docs/                                        # Documentation
    ├── README_STRUKTUR.md                      # This file
    ├── DOKUMENTASI_TRANSAKSI.md                # Transaction documentation
    ├── PANDUAN_FINAL.md                        # Final guide
    └── RINGKASAN_PERUBAHAN.md                  # Change summary
```

## Perubahan Path yang Diperlukan

Setelah folder diorganisir, pastikan update include paths di setiap file:

### Contoh Update untuk halaman_login.php
```php
// Lama:
include 'koneksi.php';

// Baru:
include '../config/koneksi.php';
```

### Contoh Update untuk dashboard.php
```php
// Lama:
include 'koneksi.php';

// Baru:
include '../config/koneksi.php';
```

### Path relatif untuk setiap folder:

- **Dari `src/pages/`** ke `src/config/`: `include '../config/koneksi.php';`
- **Dari `src/pages/`** ke `src/process/`: `include '../process/proses_transaksi.php';`
- **Dari `src/process/`** ke `src/config/`: `include '../config/koneksi.php';`
- **Dari `src/core/`** ke `src/config/`: `include '../config/koneksi.php';`

## Keuntungan Struktur Baru

✅ **Organisasi yang lebih baik** - File dikelompokkan berdasarkan fungsi
✅ **Maintenance lebih mudah** - Cari file lebih cepat
✅ **Skalabilitas** - Mudah menambah fitur baru
✅ **Standar industri** - Mengikuti best practices
✅ **Keamanan lebih baik** - Folder config terpisah

## File Redirect (Optional)

Untuk kompatibilitas, Anda bisa membuat file redirect di root:

```php
// koneksi.php (di root)
require_once 'src/config/koneksi.php';
```

Namun lebih baik update semua include path secara langsung.

---

**Catatan:** Struktur ini lebih mudah dikelola dan lebih profesional untuk proyek yang terus berkembang.
