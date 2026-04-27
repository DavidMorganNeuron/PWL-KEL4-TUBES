# 🍽️ Multi-Branch Point of Sales System for Modern F&B - Pod's

Sistem Point of Sale (POS) berbasis web untuk bisnis Food & Beverage (F&B) yang mendukung multi-cabang, manajemen pesanan, stok, dan operasional secara real-time.

Project ini dikembangkan dengan pendekatan **real-world scenario**, sehingga alur sistem menyesuaikan kondisi operasional bisnis F&B sebenarnya, bukan sekadar implementasi teoritis.

---

## 🚀 Teknologi yang Digunakan

- **Laravel 12** → Backend framework
- **PHP** → Server-side language
- **MySQL** → Database
- **Tailwind CSS** → Styling
- **Blade Template** → View engine Laravel
- **JavaScript** → Interaksi frontend

---

## 👥 Aktor Sistem

### 1. Admin Pusat (role: admin)

**Deskripsi:**
Pemegang otoritas tertinggi yang mengelola seluruh cabang dan kebijakan sistem.

**Batasan Data:**
Tidak memiliki `branch_id` (NULL) → akses global.

**Fitur:**
- **Manajemen Outlet**
  - Edit alamat cabang
  - Mengubah status cabang (Open / Closed)
  - Jika Closed → tidak bisa dipilih oleh customer (readonly + label “Closed”)

- **Manajemen Katalog Menu**
  - CRUD produk dan kategori
  - Upload & update gambar produk
  - Kontrol ketersediaan menu (`is_available`)

- **Manajemen Promo (Terpusat)**
  - Membuat promo (persentase / nominal)
  - Mengatur periode aktif
  - Menentukan produk yang terkena promo  
  ⚠️ Promo hanya dapat dibuat oleh admin pusat untuk menjaga konsistensi laporan

- **Validasi Request**
  - Approve / Reject:
    - Restock produk
  - Tercatat di `request_log`

- **Manajemen Manager Cabang**
  - Hanya melihat daftar manager
  - 1 cabang = 1 manager

- **Laporan Global**
  - Total pendapatan seluruh cabang
  - Pendapatan per cabang
  - Nilai aset stok fisik (global & per cabang)

---

### 2. Manager Cabang (role: manager)

**Deskripsi:**
Penanggung jawab operasional cabang dan eksekusi pesanan.

**Batasan Data:**
Hanya dapat mengakses data sesuai `branch_id`.

**Fitur:**
- **Pemantauan Stok Real-Time**
  - Melihat `physical_qty` stok cabang

- **Request Restock**
  - Mengajukan restock ke admin pusat
  - Diproses otomatis jika disetujui

- **Laporan Penjualan Cabang**
  - Data transaksi (`orders`)
  - Best seller (`order_items`)

- **Antrean Pesanan**
  - Menampilkan pesanan dengan status `paid`

- **Update Status Pesanan**
  - `paid → cooking → completed`

- **Pembatalan Darurat**
  - Mengubah status ke `cancelled`
  - Wajib isi `cancel_reason`
  - Sistem mencatat ke `stock_log`

---

### 3. Customer (role: customer)

**Deskripsi:**
Pengguna utama yang melakukan pemesanan.

⚠️ Wajib memiliki akun untuk melakukan transaksi.

**Fitur:**
- **Pemilihan Cabang**
- **Katalog & Pemesanan**
- **Cart (Keranjang)**
- **Checkout & Pembayaran (QRIS / E-Wallet)**
- **Live Tracking Pesanan**
- **Riwayat Transaksi**

---

## 🔄 Alur Order

1. Customer checkout → `pending_payment`  
2. Payment sukses → `paid`  
3. Manager:
   - `paid → cooking`
   - `cooking → completed`  
4. Jika gagal:
   - `cooking → cancelled`

---

## 🛒 Validasi Cart (User Experience)

- Jika stok tidak mencukupi:
  - User tetap di halaman cart
  - Sistem menampilkan notifikasi jumlah stok tersedia

- Jika hanya 1 item dan stok habis:
  - User diarahkan ke katalog

Tujuan:
➡️ Mencegah user keluar dari sistem secara tiba-tiba

---

## 📦 Manajemen Stok

Konsep utama:

- `physical_qty` → stok nyata
- `reserved_qty` → stok yang sedang dipesan

### Mekanisme:
- Checkout → tambah `reserved_qty`
- Payment sukses → kurangi `physical_qty`
- Payment gagal / cancel → release `reserved_qty`

---

## 🗄️ Struktur Database

Beberapa tabel utama:

- **users** → data pengguna
- **branches** → data cabang
- **products** → data menu
- **categories** → kategori produk
- **promos** → data promo
- **promo_products** → relasi promo & produk
- **orders** → transaksi
- **order_items** → detail pesanan
- **payments** → pembayaran
- **stocks** → stok per cabang *(disarankan satu tabel terpusat)*
- **stock_log** → histori perubahan stok
- **request_log** → pengajuan restock

---

## 🧩 Enum yang Digunakan

### Order Status
- `pending_payment`
- `paid`
- `cooking`
- `completed`
- `cancelled`

### Payment Status
- `pending`
- `success`
- `failed`

### Payment Method
- `QRIS`
- `E_Wallet`

### Request Status
- `pending`
- `approved`
- `rejected`

### Activity Type (Stock Log)
- `sale`
- `restock_approved`
- `adjustment`
- `waste`

---

## 📊 Catatan Desain Sistem

- Tidak menggunakan role **Kitchen**
  → Digantikan oleh Manager Cabang

- Tidak ada fitur delivery
  → Fokus pada:
  - Order
  - Pembayaran
  - Stok

- Promo hanya dikelola oleh Admin Pusat
  → Menghindari inkonsistensi laporan keuangan

---

## 📌 Saran & Arahan Dosen (Penting)

Project ini telah disesuaikan berdasarkan masukan dosen:

1. **User Experience Cart**
   - Jika stok tidak cukup, user tetap di cart
   - Sistem harus memberi informasi, bukan memaksa keluar

2. **Fokus Sistem**
   - Tidak perlu fitur delivery
   - Fokus pada:
     - Order
     - Pembayaran
     - Dampak ke stok

3. **Role Sistem**
   - Tidak perlu role "Kitchen"
   - Digantikan oleh **Manager Cabang**
   - Manager bertanggung jawab atas:
     - Proses memasak
     - Update status pesanan
     - Pembatalan jika terjadi masalah

---

## ⚙️ Cara Menjalankan Project

```bash
git clone https://github.com/DavidMorganNeuron/PWL-KEL4-TUBES.git
cd repository
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
