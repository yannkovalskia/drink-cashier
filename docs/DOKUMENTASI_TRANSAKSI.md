# Dokumentasi Sistem Transaksi dan Struk

## Daftar File yang Dibuat/Dimodifikasi

### 1. **transaksi.php** (Dimodifikasi)
Halaman utama transaksi kasir dengan fitur:
- **Menampilkan Daftar Produk**: Mengambil semua produk dari database dengan status "Aktif"
- **Keranjang Belanja**: Menampilkan produk yang telah ditambahkan
- **Kontrol Keranjang**: Tambah, kurangi, hapus produk, dan ubah jumlah
- **Perhitungan Otomatis**: 
  - Subtotal per item (Harga × Jumlah)
  - Total pembelian (Total semua item)
  - Kembalian (Uang Bayar - Total)
- **Validasi Pembayaran**: Tombol bayar hanya aktif jika uang cukup
- **Data Persisten**: Keranjang disimpan di localStorage browser

**Alur Kerja:**
1. Produk ditampilkan dari database
2. User klik "Tambah ke Keranjang" untuk produk
3. Keranjang terupdate di sisi kanan
4. Sistem menghitung otomatis subtotal, total, dan kembalian
5. User input uang bayar di field "Uang Bayar"
6. Jika uang cukup, klik "Bayar Sekarang" untuk lanjut ke struk

---

### 2. **proses_transaksi.php** (Baru)
File API untuk memproses pembayaran:
- **Input**: Data keranjang, subtotal, uang bayar, kembalian dari transaksi.php
- **Proses**:
  - Generate Invoice ID unik (INV-YYYYMMDDHHmmss-XXXX)
  - Simpan data transaksi ke session PHP
  - Simpan data transaksi ke database tabel `transaksi`
- **Output**: JSON response dengan invoice_id
- **Keamanan**: Cek login terlebih dahulu sebelum memproses

---

### 3. **struk.php** (Dimodifikasi)
Halaman cetak struk pembayaran dengan fitur:
- **Menampilkan Data dari Session**: Mengambil data transaksi dari session
- **Detail Invoice**: 
  - Kode invoice unik
  - Tanggal dan waktu transaksi
  - Nama kasir
- **Tabel Produk**: Menampilkan semua produk yang dibeli dengan:
  - Nama produk
  - Harga satuan
  - Jumlah yang dibeli
  - Subtotal per item
- **Ringkasan Pembayaran**:
  - Subtotal
  - Diskon (default 0%, dapat dikembangkan)
  - Total bayar
  - Uang bayar
  - Kembalian
- **Fitur Cetak**: Button "Cetak Struk" untuk print via browser
- **Transaksi Baru**: Button "Transaksi Baru" untuk kembali ke halaman transaksi

---

### 4. **bersihkan_session.php** (Baru)
File untuk membersihkan session setelah transaksi selesai:
- Menghapus `$_SESSION['transaction']` dari memory
- Memungkinkan user untuk memulai transaksi baru

---

### 5. **setup_database.php** (Baru)
File setup untuk membuat tabel `transaksi` jika belum ada:
- Jalankan sekali: `http://localhost/tugas%20akhir/setup_database.php`
- Membuat tabel dengan struktur lengkap untuk menyimpan data transaksi
- **Kolom tabel**:
  - `id_transaksi`: Primary key auto increment
  - `kode_transaksi`: Invoice ID unik
  - `tanggal_transaksi`: Timestamp transaksi
  - `subtotal`: Total harga produk
  - `diskon`: Persentase diskon
  - `total_bayar`: Total yang harus dibayar
  - `uang_bayar`: Nominal uang yang dibayarkan
  - `kembalian`: Uang kembalian
  - `detail_produk`: JSON data keranjang
  - `created_at`: Timestamp pembuatan record

---

## Alur Sistem Lengkap

### User Flow:
```
1. User masuk ke halaman transaksi.php
   ↓
2. Melihat daftar produk dari database (sidebar kiri)
   ↓
3. Klik "Tambah ke Keranjang" untuk produk yang diinginkan
   ↓
4. Produk muncul di keranjang (sidebar kanan)
   ↓
5. User bisa:
   - Ubah jumlah dengan tombol +/-
   - Hapus produk dengan tombol Hapus
   - Keranjang terupdate otomatis
   ↓
6. Sistem menghitung:
   - Subtotal (peritem × jumlah)
   - Total (jumlah semua subtotal)
   - Kembalian (uang bayar - total)
   ↓
7. User input nominal uang di field "Uang Bayar"
   ↓
8. Jika uang ≥ total, tombol "Bayar Sekarang" aktif
   ↓
9. Klik "Bayar Sekarang"
   ↓
10. Data dikirim ke proses_transaksi.php via AJAX
   ↓
11. Server:
    - Generate Invoice ID
    - Simpan ke session
    - Simpan ke database
    - Return invoice_id
   ↓
12. Redirect ke struk.php dengan data dari session
   ↓
13. Tampilkan struk pembayaran lengkap
   ↓
14. User bisa:
    - Cetak struk (Ctrl+P)
    - Atau klik "Transaksi Baru" untuk melanjutkan
   ↓
15. Jika "Transaksi Baru":
    - Bersihkan session transaksi
    - Redirect ke transaksi.php
    - Keranjang kosong lagi, siap transaksi baru
```

---

## Teknologi yang Digunakan

### Frontend:
- **HTML5**: Struktur halaman
- **CSS3**: Styling dengan glassmorphism effect
- **JavaScript (Vanilla)**: 
  - Manajemen keranjang (localStorage)
  - Perhitungan real-time
  - AJAX fetch API untuk komunikasi server
  - Integrasi dengan Intl.NumberFormat untuk format currency

### Backend:
- **PHP 7+**: Server-side processing
- **PDO (PHP Data Objects)**: Database connection & queries
- **JSON**: Data interchange format
- **Session**: Menyimpan data transaksi

### Database:
- **MySQL**: Penyimpanan data produk dan transaksi
- **Tabel yang digunakan**:
  - `produk`: Data produk (sudah ada)
  - `transaksi`: Data transaksi (perlu dibuat via setup_database.php)

### Library/API:
- **Google Fonts**: Poppins font
- **Remix Icon**: Icon library
- **CSS Backdrop Filter**: Efek glassmorphism
- **CSS Grid**: Layout responsive

---

## Fitur Utama

### 1. Manajemen Keranjang
✅ Tambah produk ke keranjang
✅ Ubah jumlah produk
✅ Hapus produk dari keranjang
✅ Update otomatis perhitungan
✅ Persisten di localStorage

### 2. Perhitungan Pembayaran
✅ Hitung subtotal per item
✅ Hitung total semua item
✅ Hitung kembalian real-time
✅ Validasi uang cukup

### 3. Proses Pembayaran
✅ Simpan data ke session
✅ Simpan data ke database
✅ Generate invoice ID unik
✅ Secure dengan session check

### 4. Struk Pembayaran
✅ Tampilkan detail transaksi
✅ Tampilkan tabel produk
✅ Tampilkan ringkasan pembayaran
✅ Fitur cetak built-in
✅ Tombol transaksi baru

---

## Cara Menggunakan

### Persiapan Awal:
1. Pastikan file `koneksi.php` sudah benar dan terhubung ke database
2. Pastikan tabel `produk` sudah ada dan terisi data
3. Jalankan `setup_database.php` untuk membuat tabel `transaksi`

### Menggunakan Sistem:
1. Masuk ke halaman transaksi.php dengan login terlebih dahulu
2. Pilih produk dan tambahkan ke keranjang
3. Ubah jumlah sesuai kebutuhan
4. Input uang bayar
5. Klik "Bayar Sekarang"
6. Sistem akan menampilkan struk pembayaran
7. Cetak atau lanjutkan transaksi baru

---

## Keamanan

- ✅ Session check di setiap halaman (login required)
- ✅ Input validation menggunakan htmlspecialchars()
- ✅ JSON encode/decode aman
- ✅ Database prepared statements (PDO)
- ✅ Error handling dengan try-catch

---

## Pengembangan Selanjutnya

Beberapa fitur yang bisa dikembangkan:
1. ✨ Fitur diskon (persentase atau nominal)
2. ✨ Metode pembayaran (cash, debit, kartu kredit)
3. ✨ Catatan/keterangan transaksi
4. ✨ Export PDF struk
5. ✨ Email struk ke customer
6. ✨ Riwayat transaksi
7. ✨ Laporan penjualan harian
8. ✨ Filter/search produk real-time
9. ✨ Barcode scanner untuk input produk
10. ✨ Integrasi dengan printer thermal

---

## Troubleshooting

### Produk tidak muncul di halaman transaksi?
- Cek koneksi database di `koneksi.php`
- Pastikan tabel `produk` ada dan terisi data
- Pastikan ada produk dengan `status_produk = 'Aktif'`

### Keranjang kosong saat buka halaman transaksi?
- Normal, localStorage direset setiap kali browser restart
- Atau jika localStorage dihapus

### Error saat klik "Bayar Sekarang"?
- Cek console browser (F12) untuk error message
- Pastikan file `proses_transaksi.php` ada di folder yang sama
- Pastikan tabel `transaksi` sudah dibuat

### Struk tidak muncul setelah bayar?
- Pastikan session tidak di-clear
- Cek error di file `proses_transaksi.php`
- Buka file browser console untuk melihat error AJAX

---

**Dibuat dengan ❤️ untuk sistem kasir DrinkCashier**
