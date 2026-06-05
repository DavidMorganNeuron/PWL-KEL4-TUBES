# 🍽️ Pod's — Multi-Branch Point of Sale System

Sistem POS berbasis web untuk bisnis F&B yang punya lebih dari satu cabang. Dibangun dengan pendekatan operasional nyata: stok terkunci saat checkout, pesanan terlacak dari dapur ke meja, dan semua laporan terpusat di satu dashboard.

---

## 🧩 Latar Belakang

Bisnis F&B multi-cabang punya masalah yang tidak bisa diselesaikan dengan kasir biasa — stok di satu cabang tidak sinkron dengan cabang lain, promo berjalan tanpa koordinasi pusat, dan tidak ada visibilitas real-time soal dapur. Pod's dibangun untuk menyelesaikan persis masalah itu.

---

## 🚀 Tech Stack

| Layer | Teknologi | Alasan |
|---|---|---|
| Backend | Laravel 12 (PHP 8.2) | Framework PHP yang mature dengan ekosistem lengkap — routing, ORM, middleware, semuanya sudah ada |
| Database | MySQL | Relational database yang cocok untuk sistem transaksi dengan banyak relasi antar tabel |
| Frontend | Tailwind CSS v4 | Utility-first CSS yang bikin styling cepat tanpa perlu keluar dari HTML |
| View Engine | Blade Templates | Template engine bawaan Laravel, mudah diintegrasikan dengan data dari controller |
| Build Tool | Vite 6 | Bundler modern yang jauh lebih cepat dari Webpack untuk development |
| JS | Axios | HTTP client untuk request AJAX di frontend |

---

## 👥 Aktor Sistem

### 👑 Admin Pusat
Admin tidak terikat ke cabang manapun, sehingga bisa melihat dan mengelola keseluruhan sistem.

**📊 Dashboard Global**
- Total pendapatan seluruh cabang
- Jumlah transaksi dan outlet aktif
- Ringkasan stok seluruh cabang dan monitoring stok kritis

**🏪 Manajemen Cabang**
- Tambah, edit, dan hapus cabang
- Atur alamat dan jam operasional (termasuk opsi buka 24 jam)
- Aktifkan, nonaktifkan, atau tutup cabang (soft delete)
- Saat membuat cabang baru, admin langsung bisa membuat akun manager-nya sekaligus — sistem otomatis menghubungkan keduanya

**🍱 Manajemen Katalog**
- CRUD kategori dan produk
- Upload dan ganti gambar produk
- Atur harga dan ketersediaan menu (`is_available`) — jika dimatikan, menu hilang dari semua cabang

**🎁 Manajemen Promo**
- Promo **nasional** berlaku untuk semua cabang (`scope = national`)
- Promo **lokal** hanya berlaku di cabang tertentu (`scope = local`)
- Tipe diskon: persentase atau nominal
- Satu promo bisa mencakup banyak produk sekaligus
- Promo hanya bisa dibuat oleh admin untuk menjaga konsistensi laporan keuangan

**✅ Validasi Request Restock**
- Approve atau reject pengajuan restock dari manager cabang
- Status: `pending` → `approved` / `rejected`
- Jika disetujui, stok fisik cabang otomatis bertambah

**📈 Laporan Global**
- Pendapatan per cabang dan total keseluruhan
- Produk terlaris
- Nilai aset stok (global dan per cabang)
- Performa tiap cabang

---

### 🧑‍💼 Manager Cabang
Manager hanya bisa mengakses data cabangnya sendiri, sesuai `branch_id` yang terikat ke akunnya.

**📊 Dashboard Cabang**
- Penjualan hari ini, total order, dan order yang sedang aktif
- Notifikasi stok kritis cabang
- Status buka/tutup cabang

**🍳 Kitchen Display System (KDS)**
- Tampilan antrian pesanan secara real-time
- Menampilkan semua order berstatus `paid` yang menunggu diproses
- Update status: `paid → cooking → completed`
- Pembatalan darurat (`cooking → cancelled`) — wajib mengisi alasan, semua aktivitas tercatat di `stock_log`

**📦 Monitoring Stok**
- Melihat `physical_qty` (stok nyata di dapur) dan `reserved_qty` (stok yang sedang di-hold oleh order aktif) secara real-time

**📋 Request Restock**
- Ajukan permintaan penambahan stok ke admin pusat
- Permintaan masuk ke `request_log` dan bisa dilacak statusnya

**📉 Laporan Cabang**
- Pendapatan dan total transaksi cabang
- Best seller produk
- Riwayat pesanan lengkap

---

### 🧑‍🤝‍🧑 Customer
Customer wajib punya akun untuk bisa melakukan transaksi.

**🏠 Landing Page**
- Informasi cabang yang tersedia
- Promo aktif (nasional dan lokal)
- Katalog menu lengkap

**🛒 Alur Pemesanan**
1. Pilih cabang — cabang yang tutup, tidak aktif, atau sedang closing tidak bisa dipilih
2. Browse menu berdasarkan kategori, lihat harga dan promo yang sedang berlaku
3. Tambah produk ke cart, ubah jumlah, atau hapus item
4. Checkout — pilih metode pembayaran (QRIS atau E-Wallet), lihat ringkasan pesanan, dan terapkan promo aktif
5. Bayar dan pantau status pesanan secara real-time

**📡 Live Order Tracking**

Setelah bayar, customer bisa memantau statusnya langsung:

`pending_payment` → `paid` → `cooking` → `completed` (atau `cancelled` jika ada masalah di dapur)

**⚠️ Validasi Cart**
- Jika stok tidak mencukupi: customer tetap di halaman cart, sistem menampilkan jumlah stok yang tersedia
- Jika semua item habis: customer diarahkan kembali ke katalog

**👤 Fitur Akun**
- Riwayat transaksi lengkap beserta detail tiap pesanan
- Edit profil akun

---

## 🔄 Alur Order & Mekanisme Stok

```
Customer checkout
   └─ Status: pending_payment
   └─ Stok: reserved_qty += qty (stok di-hold)

Pembayaran berhasil
   └─ Status: paid
   └─ Stok: physical_qty -= qty, reserved_qty -= qty

Manager proses di KDS
   └─ paid → cooking → completed

Jika dibatalkan
   └─ cooking → cancelled
   └─ Stok: reserved_qty dikembalikan
```

**🔒 Abandoned Payment Handling:** Jika customer meninggalkan halaman pembayaran tanpa menyelesaikannya, sistem otomatis membatalkan order dan mengembalikan reserved stock — mencegah stok terkunci permanen.

**🚨 Critical Stock Monitoring:** Sistem mendeteksi dan menandai produk dengan stok rendah (contoh: `physical_qty < 10`) sebagai `critical_stock`.

**🏪 Branch Availability:** Jika seluruh cabang sedang tutup, customer diarahkan ke halaman `/closed` dan tidak bisa melakukan pemesanan sama sekali.

---

## 🗄️ Struktur Database

```
Master Data         Transaksi           Stok & Request
─────────────       ─────────────       ──────────────────
users               orders              stocks (per cabang)
branches            order_items         stock_log
categories          payments            request_log
products
promos
promo_products
```

**🧩 Enum yang digunakan:**

| Konteks | Nilai |
|---|---|
| Order Status | `pending_payment`, `paid`, `cooking`, `completed`, `cancelled` |
| Payment Status | `pending`, `success`, `failed` |
| Payment Method | `QRIS`, `E_Wallet` |
| Request Status | `pending`, `approved`, `rejected` |
| Stock Activity | `sale`, `restock_approved`, `adjustment`, `waste` |

---

## ⚙️ Cara Menjalankan Lokal

### 1. Clone repository

```bash
git clone https://github.com/DavidMorganNeuron/PWL-KEL4-TUBES.git
cd PWL-KEL4-TUBES
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Setup environment

```bash
cp .env.example .env
php artisan key:generate
```

Buka file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pods_db
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Jalankan migrasi dan seeder

```bash
php artisan migrate
php artisan db:seed
```

Seeder akan otomatis membuat:
- Data awal: roles, cabang, akun manager, kategori, dan 2 produk
- Data testing: produk lengkap, stok, orders, request log, dan stock log

### 5. Jalankan server

```bash
php artisan serve
npm run dev
```

Buka `http://localhost:8000` di browser.

---

## 🔑 Akun Default

| Role | Email | Password |
|---|---|---|
| Admin Pusat | admin@gmail.com | admin123 |
| Manager Cabang | managermansyur@gmail.com | mansyur123 |

---

## 📁 Struktur Folder Penting

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AdminController.php
│   │   ├── ManagerController.php
│   │   ├── CustomerController.php
│   │   ├── OrderFlowController.php
│   │   ├── PaymentController.php
│   │   ├── ProductController.php
│   │   ├── PromoController.php
│   │   └── BranchController.php
│   └── Middleware/
├── Models/
resources/views/
├── admin/
├── manager/
└── customer/
database/
├── migrations/
└── seeders/
```

---

## 📝 Catatan Desain

- **Tidak ada role Kitchen** — fungsinya digantikan oleh Manager Cabang yang mengelola KDS langsung
- **Tidak ada fitur delivery** — sistem fokus pada order, pembayaran, dan manajemen stok
- **Promo hanya dari Admin Pusat** — untuk menghindari inkonsistensi laporan keuangan antar cabang
- **Stok per cabang terpisah** — tiap cabang punya tabel stok sendiri untuk mencegah konflik data

---

## 👨‍💻 Tim Pengembang

**Kelompok 4 — Pemrograman Web Lanjutan**  
Fakultas Ilmu Komputer dan Teknologi Informasi  
Universitas Sumatera Utara

---

## 📄 Lisensi

MIT
