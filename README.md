# SABANA Tenda — Rental Management System

> **Final Project — Sistem Analisis dan Desain · Group L**
> Sistem Inventaris & Peminjaman Barang untuk UMKM Rental Alat Camping
> Universitas Gadjah Mada — 2026

---

## Tentang Proyek

**SABANA Tenda** adalah UMKM penyewaan alat camping di Yogyakarta (tenda, sleeping bag, kompor, carrier, lampu, dll) yang sebelumnya dikelola **manual** — pemesanan via WhatsApp/datang langsung, pencatatan di nota tulis tangan, dan rekapitulasi melalui spreadsheet. Sistem manual ini menimbulkan beberapa masalah krusial:

| Permasalahan | Dampak | Prioritas |
|---|---|---|
| Pencatatan manual di nota | Human error, data sulit dibaca | Tinggi |
| Stok tidak update real-time | Double booking (1 barang, 2 pelanggan) | Tinggi |
| Sulit tracking barang | Barang hilang tak terdeteksi | Tinggi |
| Catatan peminjaman hilang | Transaksi tak bisa ditelusuri | Tinggi |
| Tidak ada histori pelanggan | Sulit evaluasi & layanan berulang | Sedang |
| Rekap & denda manual | Lambat, rawan salah hitung | Sedang |

**Akar masalah:** seluruh proses bergantung pada catatan fisik yang tidak tersinkronisasi — tidak ada basis data terpusat, tidak ada otomatisasi proses, dan tidak ada validasi stok & tanggal.

### Solusi yang Dirancang

Sistem ini menyediakan **single source of truth** terkomputerisasi dengan empat keunggulan utama:

- **Cek stok otomatis** — validasi stok sebelum transaksi diproses
- **Cegah double booking** — sistem mengunci stok saat transaksi berjalan
- **Denda otomatis** — perhitungan keterlambatan & ganti rugi tanpa intervensi manual
- **Dashboard real-time** — owner memantau performa bisnis dari mana saja

---

## Tim Pengembang — Group L

| Nama | NIM |
|---|---|
| Destiana Wicaksani | 24/536157/EK/24971 |
| Love's Nurani Hasan | 24/533831/EK/24890 |
| M Lintang Maulana Zulfan | 24/539064/EK/25105 |

---

## Ringkasan Hasil Analisis & Desain

Sistem ini dibangun mengikuti tahapan analisis sistem terstruktur:

### Tahap 1 — Information Gathering & Pemahaman Bisnis
- Triangulasi data via pemahaman proses, wawancara owner, dan observasi lapangan
- Identifikasi 6 permasalahan utama dengan pemetaan dampak dan prioritas

### Tahap 2 — Perancangan Alur Sistem
Perbandingan flowchart **sistem lama (manual)** vs **sistem baru (otomatis)**:
- Sistem lama: Pelanggan order WA → staff cek stok manual → catat di nota → barang disewa → cek manual saat kembali → hitung denda manual → rekap di Excel
- Sistem baru: Staff login → input transaksi → **sistem cek stok otomatis** → simpan + **kurangi stok otomatis** → cetak invoice → input return → **sistem hitung denda & update stok otomatis** → owner pantau dashboard real-time

### Tahap 3 — Data Modeling
- **Context Diagram (DFD Level 0):** Sistem SABANA di pusat dengan 3 entitas eksternal — Customer, Staff, Owner
- **DFD Level 1:** 4 proses utama (Manage Inventory, Process Rental, Process Return, Generate Report) dengan 5 data store (D1 Items, D2 Customers, D3 Rentals, D4 Returns, D5 Damaged Items)

### Tahap 4 — Data Dictionary
Definisi elemen data lengkap untuk: Items, Customers, Rentals, Rental Details, Returns, Damaged Items, dan Users.

### Tahap 5 — UML Modeling
- **Use Case Diagram:** 4 aktor (Admin, Staff, Owner, Customer) dengan 7 relasi include
- **Activity Diagram:** Swim lane Staff & Sistem untuk proses peminjaman
- **Sequence Diagram:** Alur 13 pesan sinkron Staff → UI → Controller → Database (validasi stok ≥ 1, INSERT rentals, INSERT rental_details, UPDATE items, INSERT invoices)

### Tahap 6 — Database Design (ERD)
8 tabel utama dengan foreign key dan kardinalitas relasi yang lengkap.

### Tahap 7 — Security & Validation Controls
- **Authentication:** Login system, password hashing, session management, failed login protection (max 5x, lockout 15 menit), show/hide password
- **Role-Based Access Control:** Staff/Admin (kelola transaksi, return, stok), Owner (semua akses + dashboard analytics + manage user + export laporan)
- **Inventory Validation:** Prevent negative stock, real-time update, double booking prevention, item condition validation (good/minor/heavy)
- **Rental Transaction Validation:** Required fields, date validation (return > rental), rental duration max 14 hari (perlu approval owner jika lebih), quantity validation, duplicate transaction prevention
- **Return Validation:** Late return detection, fine validation (otomatis tidak bisa di-manipulasi staff), damage validation, return completeness check

---

## Tech Stack

| Layer | Teknologi |
|---|---|
| **Backend Framework** | Laravel 11 (PHP 8.2+) |
| **Database** | MySQL 8.0+ |
| **Frontend** | Blade Templates + Tailwind CSS (CDN) + Alpine.js |
| **Icon System** | Custom SVG icon component (inline line-style icons) |
| **Charting** | Chart.js 4.x (untuk dashboard owner) |
| **Auth** | Laravel built-in session auth + custom RBAC middleware |
| **Typography** | Fraunces (display) + Plus Jakarta Sans (body) + JetBrains Mono (data) |
| **Architecture** | MVC + Service layer (RentalService, ReturnService) untuk business logic |

---

## Struktur Database (8 Tabel)

```
users               — Akun owner/admin/staff/customer
categories          — Kategori barang (tenda, carrier, sleeping gear, dll)
items               — Master barang inventaris
customers           — Data pelanggan (dapat linked ke user account)
rentals             — Header transaksi sewa
rental_details      — Detail item per transaksi
returns             — Catatan pengembalian (denda terhitung otomatis)
damaged_items       — Log barang rusak/hilang per transaksi
```

Relasi: `User 1-N Rental`, `Customer 1-N Rental`, `Rental 1-N RentalDetail`, `Rental 1-1 Return`, `Rental 1-N DamagedItem`, `Item 1-N RentalDetail/DamagedItem`, `Category 1-N Item`.

---

## Instalasi & Setup

### Prasyarat
- PHP **8.2+** dengan ekstensi: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`
- Composer **2.x**
- MySQL **8.0+** (atau MariaDB 10.6+)
- Web server (Apache/Nginx) atau gunakan `php artisan serve`

> Untuk kemudahan lokal di Windows, gunakan **XAMPP** atau **Laragon** yang sudah include PHP + MySQL + Apache.

### Langkah Setup

```bash
# 1. Clone / extract project
cd "sabana-tenda"

# 2. Install dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Buat database baru di MySQL
#    Nama database: sabana_tenda
#    Lewat phpMyAdmin atau:
mysql -u root -p -e "CREATE DATABASE sabana_tenda CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 6. Konfigurasi .env (sesuaikan kredensial MySQL Anda)
#    DB_DATABASE=sabana_tenda
#    DB_USERNAME=root
#    DB_PASSWORD=

# 7. Migrate & seed database
php artisan migrate --seed

# 8. Jalankan development server
php artisan serve
```

Aplikasi akan berjalan di **http://localhost:8000**.

---

## Akun Demo (sudah di-seed)

Semua password mengikuti format `<Role><Nama>123` (case-sensitive, kombinasi huruf & angka).

### Owner
| Username | Password | Email | Akses |
|---|---|---|---|
| `owner_sabana` | `OwnerSabana123` | owner@sabanatenda.id | Dashboard analytics, semua akses staff, manage user, export laporan |

### Admin
| Username | Password | Email |
|---|---|---|
| `admin_sabana` | `AdminSabana123` | admin@sabanatenda.id |

### Staff
| Username | Password | Nama |
|---|---|---|
| `staff_sabana` | `StaffSabana123` | Love's Nurani Hasan |
| `staff_lintang` | `StaffLintang123` | M Lintang Maulana Zulfan |
| `staff_rina` | `StaffRina123` | Rina Permatasari |

### Customer
| Username | Password | Nama |
|---|---|---|
| `budi_customer` | `BudiCustomer123` | Budi Santoso |
| `siti_customer` | `SitiCustomer123` | Siti Aisyah |
| `andi_customer` | `AndiCustomer123` | Andi Pratama |
| `dewi_customer` | `DewiCustomer123` | Dewi Lestari |
| `ricky_customer` | `RickyCustomer123` | Ricky Fadli |

> Selain 5 customer dengan login account, ada **7 walk-in customer** (tanpa login) yang sudah di-seed untuk simulasi transaksi datang langsung.

---

## Data Seed yang Tersedia

| Tabel | Jumlah | Catatan |
|---|---|---|
| Users | 10 | 1 Owner, 1 Admin, 3 Staff, 5 Customer |
| Categories | 6 | Tenda, Carrier, Sleeping Gear, Alat Masak, Lighting, Perlengkapan Lain |
| Items | 20 | Total stok ~150+ unit (Tenda Dome 4P, Sleeping Bag, Carrier 60L, dll) |
| Customers | 12 | 5 customer login + 7 walk-in |
| Rentals | 14 | Mix dari completed (9), active (4), late (1) — mencakup 45 hari ke belakang |
| Rental Details | ~40 | Average 3 item per transaksi |
| Returns | 9 | Termasuk 1 case kerusakan dengan damage_fee Rp 50.000 |
| Damaged Items | 1 | Resleting tenda dome (minor damage) |

---

## Alur Penggunaan

### 1. Sebagai **Owner** (`owner_sabana`)
- Login → otomatis ke `/admin/dashboard`
- Lihat overview: total barang, sedang disewa, terlambat, pendapatan bulan ini
- Chart tren peminjaman 30 hari + top items
- Akses penuh: Inventaris, Kategori, Peminjaman, Pengembalian, Pelanggan, User, **Laporan & Export CSV**

### 2. Sebagai **Staff** (`staff_sabana`)
- Login → otomatis ke `/staff/dashboard`
- Quick actions: Buat Sewa Baru, Proses Return, Cek Stok, Pelanggan
- **Buat Transaksi Sewa** (`/staff/rentals/create`):
  - Pilih pelanggan lama atau input baru
  - Pilih tanggal sewa & kembali (validasi: return > rental, max 14 hari)
  - Pilih item & jumlah (validasi: qty ≤ available_stock real-time)
  - Sistem otomatis hitung total = harga × qty × durasi
  - Simpan → stok otomatis berkurang → invoice digital dapat dicetak
- **Proses Pengembalian** (`/staff/returns/create?rental_code=...`):
  - Input tanggal kembali aktual
  - Cek kondisi tiap item, tandai kerusakan jika ada
  - Sistem otomatis hitung: `Denda = late_days × penalty × total_items + sum(repair_cost)`
  - Stok otomatis kembali (untuk item yang tidak hilang)

### 3. Sebagai **Customer** (`budi_customer`)
- Login → ke `/customer/dashboard`
- Lihat riwayat peminjaman pribadi + total pengeluaran
- Browse katalog publik → tersedia tombol *Sewa Sekarang* (arahkan ke katalog/login)

### 4. Sebagai **Pengunjung (tanpa login)**
- Akses katalog publik di `/catalog`
- Lihat detail produk, kategori, kondisi
- Daftar akun customer baru di `/register`

---

## Security Controls yang Diimplementasi

- **Password Hashing** — bcrypt (12 rounds), tidak pernah disimpan plain text
- **Password Rules** — minimal 8 karakter, kombinasi huruf & angka, case-sensitive
- **Failed Login Protection** — max 5 percobaan, lockout 15 menit
- **Session Management** — auto logout 15 menit idle (`SESSION_LIFETIME=15`)
- **Show/Hide Password** — ikon mata di form login
- **RBAC Middleware** — `EnsureUserHasRole` di route group (owner/admin/staff/customer)
- **Stock Validation** — `lockForUpdate()` di RentalService untuk cegah race condition
- **Date Validation** — return_date > rental_date (validasi di form + backend)
- **Max Rental Duration** — 14 hari (configurable via `SABANA_MAX_RENTAL_DAYS`)
- **Auto Fine Calculation** — formula `late_days × daily_penalty × total_items` tidak bisa di-override staff
- **Damage Validation** — wajib pilih level (minor/heavy/lost) + deskripsi + biaya
- **CSRF Protection** — built-in Laravel pada semua form POST/PUT/DELETE

---

## Konfigurasi Bisnis

Edit `.env` untuk mengubah aturan bisnis:

```env
SABANA_DAILY_PENALTY=25000        # Denda per hari per item (Rp)
SABANA_MAX_RENTAL_DAYS=14         # Max durasi sewa tanpa approval owner
SABANA_MAX_LOGIN_ATTEMPTS=5       # Max gagal login sebelum lockout
SABANA_LOCKOUT_MINUTES=15         # Durasi lockout akun
```

---

## Struktur Folder

```
sabana-tenda/
├── app/
│   ├── Http/
│   │   ├── Controllers/        # 9 controllers (Auth, Home, Dashboard, Inventory, Rental, Return, ...)
│   │   └── Middleware/         # EnsureUserHasRole.php
│   ├── Models/                 # 8 Eloquent models
│   ├── Providers/
│   └── Services/               # RentalService, ReturnService (business logic terpusat)
├── config/
│   ├── sabana.php              # Konfigurasi domain SABANA (denda, max days, dll)
│   └── *.php                   # Laravel standard configs
├── database/
│   ├── migrations/             # 10 migrations (Laravel default + 7 SABANA)
│   └── seeders/                # 5 seeders (User, Category, Item, Customer, Rental)
├── resources/
│   └── views/
│       ├── components/         # SVG icon component (custom line-style icons)
│       ├── layouts/            # app (public), dashboard (authenticated)
│       ├── partials/           # nav, footer, sidebar, topbar
│       ├── auth/               # login, register
│       ├── admin/              # dashboard, inventory, categories, customers, users, reports
│       ├── staff/              # dashboard, rentals (CRUD), returns, invoice
│       ├── customer/           # dashboard
│       ├── home.blade.php
│       ├── catalog.blade.php
│       ├── item-detail.blade.php
│       └── about.blade.php
├── routes/
│   └── web.php                 # Public + auth + role-protected route groups
├── public/
│   ├── index.php
│   └── .htaccess
└── composer.json
```

---

## Skenario Pengujian yang Disarankan

### Skenario 1: Validasi Stok Otomatis
1. Login sebagai staff
2. Buat sewa untuk Tenda Dome 4P (stok 8) sebanyak **10 unit**
3. Expected: Validasi gagal — "Stok tidak cukup. Tersedia: 8"

### Skenario 2: Cegah Double Booking
1. Buat 2 transaksi paralel untuk item dengan stok 1
2. Expected: Hanya satu yang berhasil; transaksi kedua mendapat error stok 0

### Skenario 3: Denda Keterlambatan Otomatis
1. Buat sewa dengan return_date kemarin
2. Proses return hari ini → `late_days = 1`, fine = `1 × 25000 × jumlah_item`
3. Expected: Sistem menampilkan denda yang dihitung otomatis (tidak bisa di-edit manual)

### Skenario 4: Failed Login Lockout
1. Login dengan password salah 5 kali berturut-turut
2. Expected: "Too many failed login attempts. Akun terkunci sementara." (15 menit)

### Skenario 5: Role-Based Access Control
1. Login sebagai staff → coba akses `/admin/users`
2. Expected: 403 Forbidden — "Anda tidak memiliki izin untuk mengakses halaman ini."

---

## Kontribusi & License

Proyek ini dibuat untuk tujuan akademik (Final Project mata kuliah Analisis dan Desain Sistem, Fakultas Ekonomika dan Bisnis UGM). Untuk pertanyaan, hubungi tim Group L melalui email kampus.

**License:** MIT

---

<p align="center">
  <strong>SABANA Tenda</strong> · Rental Management System<br>
  <em>Built with Laravel 11 · MySQL 8 · Tailwind CSS</em><br>
  © 2026 Group L · Universitas Gadjah Mada
</p>
