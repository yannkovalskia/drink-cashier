**DrinkCashier - Sistem Kasir Minuman Berbasis Web**

Sistem kasir modern untuk toko minuman dengan antarmuka yang elegan dan responsif. Dibangun menggunakan **PHP 7.4+, MySQL/MariaDB, dan HTML5** dengan desain **glassmorphism** yang menarik.

**Versi:** 2.0 | **Status:** Development | **Last Updated:** 2 Juni 2026

---
Fitur Sistem

### **Autentikasi & Keamanan**
- ✔️ Halaman login dengan validasi session
- ✔️ Logout dengan clear session
- ✔️ Proteksi halaman dengan session check
- ✔️ Password handling (plain + hash support)
- ✔️ Error message informatif

### **Manajemen Produk (CRUD Lengkap)**

#### **CREATE - Tambah Produk**
- Form input data produk lengkap dengan validasi
- Upload gambar produk (JPG, PNG, WEBP)
- Real-time preview gambar saat upload
- Auto-generate nama file gambar dengan timestamp
- Kategori produk opsional
- Status default Aktif/Habis/Draft

#### **READ - Daftar Produk**
- Tabel data produk dengan informasi lengkap
- JOIN dengan tabel kategori untuk nama kategori
- Kolom: Kode, Nama, Kategori, Harga, Stok, Status, Gambar, Aksi
- Format harga dengan separator ribuan (Rp X.XXX)
- Status product dengan color coding (Aktif=Hijau, Habis=Merah)
- Display gambar produk dalam tabel
- Responsive design untuk mobile

#### **UPDATE - Edit Produk**
- Form edit dengan data terisi dari database
- Edit semua field: nama, kategori, harga, stok, status, deskripsi
- Upload gambar baru (opsional)
- Auto-delete gambar lama saat upload gambar baru
- Kode produk read-only (tidak bisa diubah)
- Validasi input sebelum update

#### **DELETE - Hapus Produk**
- Tombol hapus dengan konfirmasi dialog
- Auto-delete file gambar dari server
- Notifikasi sukses setelah hapus

### **Dashboard & Statistik**
- Total produk tersimpan
- Total stok produk
- Jumlah produk habis/stok 0
- Jumlah kategori aktif
- Auto-update statistik dari database
- Card-based layout dengan icon

### **Halaman Transaksi** 
- Tampilkan daftar produk aktif dengan foto
- Keranjang belanja interaktif (add/remove/update qty)
- Perhitungan otomatis: subtotal, total, kembalian
- Validasi stok sebelum transaksi
- Input uang bayar dengan validasi cukup
- Simpan transaksi ke database
- Generate kode transaksi unik
- Detail produk disimpan dalam JSON

### **Halaman Struk/Receipt**
- Display struk pembayaran
- Format struk kasir A4-friendly
- Tombol print browser
- Informasi: tanggal, kode transaksi, produk, total

### **Antarmuka User (UI/UX)**
- Sidebar navigasi collapsible
- Menu: Dashboard, Produk, Transaksi, Analitik, Logout
- Responsive design (mobile-first)
- Tema dark dengan glassmorphism effect
- Gradient buttons dengan hover animation
- Notifikasi success/error toast messages
- Real-time preview card
- Loading indicator

### **Database Integration**
- Koneksi PDO ke MySQL dengan error handling
- Prepared statements untuk security
- Support NULL values di field kategori
- Transaction logging

### **Fitur In Progress / Planned**
- Analitik dengan grafik penjualan
- Search & filter produk
- Pagination daftar produk
- User management dengan role
- Backup database
- Export laporan (CSV/Excel)
- QR Code scanner
- Dark/Light theme toggle

---

##Struktur Proyek (Terorganisir)

```
tugas akhir/
│
├── 📄 index.php                     # Entry point / router utama
├── 📄 README.md                     # Dokumentasi ini
│
├── 📁 src/                          # Source code aplikasi
│   ├── 📁 config/
│   │   ├── koneksi.php              # Database PDO connection
│   │   └── setup_database.php       # Database initialization
│   │
│   ├── 📁 core/
│   │   ├── system_check.php         # System verification
│   │   └── bersihkan_session.php    # Session cleanup
│   │
│   ├── 📁 pages/                    # Halaman aplikasi
│   │   ├── halaman_login.php        # Login page
│   │   ├── dashboard.php            # Main dashboard
│   │   ├── kelola_produk.php        # Product list/table
│   │   ├── tambah_produk.php        # Add product form
│   │   ├── edit_produk.php          # Edit product form
│   │   ├── transaksi.php            # Transaction page ⭐
│   │   ├── struk.php                # Receipt page
│   │   └── Analitik.php             # Analytics page (planned)
│   │
│   └── 📁 process/                  # Backend handlers
│       ├── proses_tambah_produk.php # Add product handler
│       ├── proses_edit_produk.php   # Edit product handler
│       ├── hapus_produk.php         # Delete product handler
│       ├── proses_transaksi.php     # Transaction processor ⭐
│       └── logout.php               # Logout handler
│
├── 📁 docs/                         # Dokumentasi lengkap
│   ├── DOKUMENTASI_TRANSAKSI.md     # Transaction system docs
│   ├── PANDUAN_FINAL.md             # User guide
│   ├── RINGKASAN_PERUBAHAN.md       # Change log
│   └── README_STRUKTUR.md           # Struktur folder guide
│
├── 📁 uploads/                      # Product images storage
│   └── [product_images...]
│
├── 📄 setup_folders.php             # Folder structure creator (utility)
├── 📄 reorganize.bat                # Batch script untuk reorganisasi (Windows)
└── 📄 create_structure.bat          # Alternative batch script

```

---

## Struktur Database & Relasi

### Database: `my_kasir`

#### **Tabel 1: users** 
```sql
CREATE TABLE users (
    id_user INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,          -- Plain or bcrypt hash
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```
**Relasi:** 1 user bisa melakukan banyak transaksi

---

#### **Tabel 2: kategori** 
```sql
CREATE TABLE kategori (
    id_kategori INT PRIMARY KEY AUTO_INCREMENT,
    nama_kategori VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```
**Relasi:** 1 kategori bisa memiliki banyak produk (1:N)

---

#### **Tabel 3: produk** 
```sql
CREATE TABLE produk (
    kode_produk VARCHAR(20) PRIMARY KEY,
    nama_produk VARCHAR(100) NOT NULL,
    id_kategori INT,                        -- FK ke tabel kategori
    harga_produk INT NOT NULL,              -- Harga dalam rupiah
    jumlah_stok INT NOT NULL DEFAULT 0,
    status_produk ENUM('Aktif','Habis','Draft') DEFAULT 'Aktif',
    deskripsi_produk TEXT,
    gambar_produk VARCHAR(255),             -- Nama file gambar
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori) ON DELETE SET NULL
);
```
**Relasi:** 1 kategori → banyak produk (1:N)

---

#### **Tabel 4: transaksi** 
```sql
CREATE TABLE transaksi (
    id_transaksi INT PRIMARY KEY AUTO_INCREMENT,
    kode_transaksi VARCHAR(50) UNIQUE NOT NULL,   -- Invoice number
    id_user INT,                                   -- FK ke tabel users
    tanggal_transaksi DATETIME DEFAULT CURRENT_TIMESTAMP,
    subtotal DECIMAL(12,2) NOT NULL,              -- Total harga produk
    diskon DECIMAL(12,2) DEFAULT 0,               -- Potongan harga
    total_bayar DECIMAL(12,2) NOT NULL,           -- Total harus dibayar
    uang_bayar DECIMAL(12,2) NOT NULL,            -- Uang diterima
    kembalian DECIMAL(12,2) NOT NULL,             -- Uang kembalian
    detail_produk LONGTEXT NOT NULL,              -- JSON array detail item
    status_transaksi ENUM('Selesai','Pending','Batal') DEFAULT 'Selesai',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE SET NULL
);
```
**Relasi:** 
- 1 user → banyak transaksi (1:N)
- 1 transaksi memiliki banyak detail produk (disimpan dalam JSON)

---

### **Diagram Relasi Database**
```
┌─────────────┐
│   users     │
│─────────────│
│ id_user     │ (PK)
│ username    │
│ password    │
└──────┬──────┘
       │ (1:N)
       │
┌──────▼──────────────┐
│   transaksi         │
│─────────────────────│
│ id_transaksi   (PK) │
│ kode_transaksi      │
│ id_user       (FK)  │
│ total_bayar         │
│ detail_produk(JSON) │
└─────────────────────┘


┌─────────────┐
│  kategori   │
│─────────────│
│ id_kategori │ (PK)
│ nama_kat... │
└──────┬──────┘
       │ (1:N)
       │
┌──────▼─────────────┐
│   produk            │
│─────────────────────│
│ kode_produk    (PK) │
│ nama_produk         │
│ id_kategori   (FK)  │
│ harga_produk        │
│ jumlah_stok         │
│ gambar_produk       │
└─────────────────────┘
```

**Tipe Relasi:**
- **1:N (One-to-Many)**: Kategori → Produk, User → Transaksi
- **0:N (Zero-to-Many)**: Detail produk disimpan dalam JSON di tabel transaksi

---

## Cara Menjalankan Sistem

### **Prerequisites / Kebutuhan**
- XAMPP, WAMP, atau Web Server lainnya dengan PHP 7.4+
- MySQL/MariaDB database server
- Browser modern (Chrome, Firefox, Edge)
- Terminal/Command Prompt (untuk setup)

### **Langkah Setup:**

#### **1. Clone/Extract Proyek**
```bash
# Jika di XAMPP
cp -r tugas\ akhir C:/xampp/htdocs/
# atau drag-drop ke folder htdocs

# Navigasi ke folder
cd "C:\xampp\htdocs\tugas akhir"
```

#### **2. Reorganisasi Folder Struktur (Windows)**
```batch
# Jalankan script batch untuk auto-reorganize
reorganize.bat

# Atau manual - copy files sesuai struktur di atas
```

#### **3. Buat Database di MySQL**
```sql
-- Buka MySQL Console atau phpMyAdmin
-- Buat database
CREATE DATABASE my_kasir CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Gunakan database
USE my_kasir;

-- Tabel kategori
CREATE TABLE kategori (
    id_kategori INT PRIMARY KEY AUTO_INCREMENT,
    nama_kategori VARCHAR(100) NOT NULL
);

-- Tabel produk
CREATE TABLE produk (
    kode_produk VARCHAR(20) PRIMARY KEY,
    nama_produk VARCHAR(100) NOT NULL,
    id_kategori INT,
    harga_produk INT NOT NULL,
    jumlah_stok INT DEFAULT 0,
    status_produk ENUM('Aktif','Habis','Draft') DEFAULT 'Aktif',
    deskripsi_produk TEXT,
    gambar_produk VARCHAR(255),
    FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori)
);

-- Tabel users
CREATE TABLE users (
    id_user INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Tabel transaksi
CREATE TABLE transaksi (
    id_transaksi INT PRIMARY KEY AUTO_INCREMENT,
    kode_transaksi VARCHAR(50) UNIQUE NOT NULL,
    id_user INT,
    tanggal_transaksi DATETIME DEFAULT CURRENT_TIMESTAMP,
    subtotal DECIMAL(12,2),
    diskon DECIMAL(12,2) DEFAULT 0,
    total_bayar DECIMAL(12,2),
    uang_bayar DECIMAL(12,2),
    kembalian DECIMAL(12,2),
    detail_produk LONGTEXT,
    status_transaksi ENUM('Selesai','Pending','Batal') DEFAULT 'Selesai'
);

-- Insert user default
INSERT INTO users (username, password) VALUES ('admin', 'admin123');

-- Insert kategori sample
INSERT INTO kategori (nama_kategori) VALUES 
('Minuman Panas'),
('Minuman Dingin'),
('Jus Buah'),
('Smoothie');

-- Insert produk sample
INSERT INTO produk VALUES 
('KD001', 'Kopi Espresso', 1, 25000, 50, 'Aktif', 'Kopi premium espresso', NULL),
('KD002', 'Thai Tea', 2, 20000, 60, 'Aktif', 'Thai tea dingin', NULL),
('KD003', 'Jus Jeruk', 3, 15000, 40, 'Aktif', 'Jus jeruk fresh', NULL),
('KD004', 'Mango Smoothie', 4, 30000, 35, 'Aktif', 'Smoothie mangga segar', NULL);
```

#### **4. Konfigurasi Database (Optional)**
Edit file `src/config/koneksi.php` jika berbeda:
```php
$host     = "localhost";      // Sesuaikan host
$username = "root";            // Username MySQL
$password = "";                // Password MySQL (kosong untuk default)
$database = "my_kasir";        // Nama database
```

#### **5. Set Folder Permissions (Linux/Mac)**
```bash
chmod -R 755 src/
chmod -R 777 uploads/
```

#### **6. Akses Aplikasi**
```
http://localhost/xampp/tugas%20akhir/
# atau
http://localhost/tugas%20akhir/index.php
```

#### **7. Login**
```
Username: admin
Password: admin123
```

---

## 📊 Alur Sistem

### **Login Flow**
```
User akses http://localhost/tugas%20akhir/
    ↓
index.php cek session
    ↓
Belum login? → Redirect ke halaman_login.php
    ↓
Input username & password
    ↓
Validasi dari tabel users
    ↓
Sukses? Set $_SESSION → Redirect dashboard.php
```

### **Transaksi Flow**
```
Buka halaman transaksi.php
    ↓
Tampil daftar produk dari tabel produk
    ↓
Pilih produk → Tambah ke keranjang
    ↓
Ubah qty produk (keranjang di localStorage)
    ↓
Input uang bayar
    ↓
Klik "Proses Pembayaran"
    ↓
proses_transaksi.php:
  - Generate kode transaksi
  - Hitung total, kembalian
  - Simpan ke tabel transaksi
  - Kurangi stok produk
    ↓
Tampil struk
    ↓
User bisa print/close
```

---

## 🛠️ Troubleshooting

### **Error: "Koneksi ke database gagal"**
- Pastikan MySQL server running
- Cek kredensial di `src/config/koneksi.php`
- Database `my_kasir` sudah dibuat?
- Username & password benar?

### **Error: "Session tidak valid"**
- Hapus cache browser
- Buka halaman baru → login kembali
- Atau jalankan `src/core/bersihkan_session.php`

### **Gambar produk tidak tampil**
- Folder `uploads/` ada permission 777?
- Format gambar JPG/PNG/WEBP?
- File ada di folder `uploads/`?

### **Halaman blank/error 500**
- Cek error log PHP di `php_error.log`
- Pastikan path include benar (relatif ke folder file)
- Enable error reporting di `php.ini`

---

## 📞 Support & Kontribusi

- Lapor bug: Buat issue di repository
- Saran fitur: Diskusi di issue tracker
- Pull request: Welcome untuk improvement

---

## Changelog

### v2.0 (Current)
- Reorganisasi folder struktur profesional
- Update README dengan dokumentasi lengkap
- Struktur database relasional
- Implementasi transaksi lengkap
- Setup database SQL schema

### v1.0
- CRUD Produk (Tambah, Edit, Hapus)
- Login/Logout
- Dashboard dengan statistik
- Upload gambar produk

---

**Dikembangkan dengan ❤️ untuk memudahkan manajemen kasir minuman**

*Last Updated: 2 Juni 2026*

---

## Fitur UI/UX

### Selesai:
- Dark theme dengan gradient accent (orange-pink-cyan)
- Glassmorphism effect pada card
- Sidebar responsive (collapse on mobile)
- Form input dengan focus effect
- Upload box dengan drag-friendly styling
- Real-time preview card
- Notification toast messages
- Color-coded status badges

### Perlu Ditambah:
- Dark/Light theme toggle
- Loading spinner
- Confirmation modal yang lebih fancy
- Date picker
- Time picker
- Autocomplete search

---

## Cara Menggunakan

### Prerequisites
- PHP 7.4+
- MySQL/MariaDB
- XAMPP atau Web Server lainnya

### Setup
1. Clone atau extract project ke folder `htdocs`
2. Import database dari SQL file (jika ada)
3. Konfigurasi `koneksi.php` dengan kredensial database
4. Akses via `http://localhost/tugas%20akhir/`
5. Login dengan akun yang tersedia di database

### Default Akun
- Username: `admin`
- Password: `admin123` (sesuaikan dengan database)

---

## Changelog

### v1.0 (Current)
- Implementasi CRUD Produk
- Sistem Login/Logout
- Real-time Preview
- Upload Gambar dengan validasi
- Statistik Dashboard
- Responsive Design

### v1.1 (Planned)
- Sistem Transaksi
- Laporan Penjualan
- Struk/Receipt Print
- Search & Filter

---

## 📧 Kontak & Support

Untuk pertanyaan atau laporan bug, silakan hubungi tim development.

---

**Last Updated:** 24 Mei 2026
