# Inventory Management System (inventory-laravel)
### Implementasi Materi Pertemuan 4 & 5 Pemrograman Web (Mini Project 3)

Aplikasi **Inventory Management System** berbasis framework Laravel yang menerapkan seluruh materi dan aturan bisnis dari:
- **Pertemuan 4:** *Relational Database + Advanced PHP* (Relasi 1:N & M:N, Foreign Key RESTRICT/CASCADE, Aggregat, Session, Autentikasi Role, DB Transaction, Row Locking `FOR UPDATE`, Keamanan & Validasi Ketat).
- **Pertemuan 5:** *Laravel Fundamental* (Arsitektur MVC, Request Lifecycle, Migrations, Eloquent ORM, Model Relationships, Factory & Seeder, Resource Controller, Route Name, Blade Templates & Layouts, Pagination).

---

## 1. Arsitektur MVC & Alur Request
Alur request mengikuti siklus standar Laravel:
$$\text{HTTP Request} \longrightarrow \text{Route} \longrightarrow \text{Middleware (Auth/Role)} \longrightarrow \text{Controller} \longleftrightarrow \text{Eloquent Model / Database} \longrightarrow \text{Blade View Response}$$

1. **Route (`routes/web.php`):** Mengarahkan request ke Resource Controller yang sesuai dan mengisolasi otorisasi via middleware (`role:admin`).
2. **Middleware (`EnsureUserHasRole.php`):** Memvalidasi hak akses server-side (Admin vs Staff). Mengembalikan HTTP 403 Forbidden bila akses tidak diizinkan.
3. **Controller (`app/Http/Controllers/`):** Mengatur logika bisnis, validasi input, transaksi database atomic (`DB::transaction`), dan meneruskan data ke view.
4. **Model & Eloquent (`app/Models/`):** Merepresentasikan entitas bisnis, relasi antar-tabel, local query scopes (`search`, `filterByCategory`, `lowStock`, `applySorting`).
5. **Blade View (`resources/views/`):** Menggunakan inheritance layout (`layouts.app`), form CSRF token (`@csrf`), method spoofing (`@method`), alert flash messages, serta pagination link.

---

## 2. Struktur Database & Relasi Eloquent

```
users (id, name, username, email, role ['admin','staff'], password)
  │
  └──< stock_movements (id, product_id, user_id, type ['IN','OUT'], quantity, reference_number, notes)
          │
categories (id, name, description)
  │ (1:N)
  └──< products (id, category_id, sku, name, description, price, stock, minimum_stock)
          │ (M:N)
          └──< product_suppliers >─── suppliers (id, name, email, phone, address)
```

### Relasi Model:
1. **Category & Product (`One-to-Many`):**
   - `Category::hasMany(Product::class)`
   - `Product::belongsTo(Category::class)`
   - *Referential Integrity:* Kategori dilindungi dari penghapusan jika masih memiliki produk (`RESTRICT`).
2. **Product & Supplier (`Many-to-Many`):**
   - Menggunakan junction table `product_suppliers` dengan pivot `supplier_price`.
   - `Product::belongsToMany(Supplier::class, 'product_suppliers')->withPivot('supplier_price')->withTimestamps()`
   - `Supplier::belongsToMany(Product::class, 'product_suppliers')->withPivot('supplier_price')->withTimestamps()`
3. **Stock Movements (`Transactions`):**
   - `Product::hasMany(StockMovement::class)`
   - `StockMovement::belongsTo(Product::class)`
   - `StockMovement::belongsTo(User::class)`
   - *Business Rule:* Produk yang memiliki histori mutasi tidak boleh dihapus sembarangan untuk menjaga audit trail pembukuan.

---

## 3. Fitur Utama & Logika Bisnis

### A. Autentikasi & Otorisasi Berbasis Role
- **Login Fleksibel:** Pengguna dapat login menggunakan **Email** maupun **Username**.
- **Keamanan Password:** Menggunakan `Hash::make()` (Bcrypt).
- **Session Regeneration:** Memanggil `session()->regenerate()` setelah login sukses untuk mencegah *Session Fixation attack*.
- **Role Permissions:**
  - **Admin:** Memiliki akses penuh (Manajemen User, CRUD Kategori, CRUD Produk, CRUD Supplier, dan Transaksi Stok).
  - **Staff:** Melihat katalog produk, kategori, supplier, dashboard, dan mencatat transaksi stok masuk/keluar. Dilarang mengakses administrasi pengguna (HTTP 403 Forbidden).

### B. Transaksi Aman (Barang Masuk & Barang Keluar)
- **Database Transaction (`DB::transaction`):** Memastikan operasi update stok dan pencatatan riwayat `stock_movements` terjadi secara konsisten (*ACID*). Bila salah satu gagal, seluruh perubahan di-rollback otomatis.
- **Row-Level Locking (`lockForUpdate` / `FOR UPDATE`):** Mencegah race condition ketika multi-user melakukan transaksi secara bersamaan.
- **Validasi Barang Keluar (OUT):**
  - Kuantitas harus $> 0$.
  - Dicek secara semantik: kuantitas keluar tidak boleh melebihi stok yang tersedia (`quantity <= stock`).
  - Mencegah stok menjadi negatif.

### C. Master Data CRUD (Resource Controller)
- **Produk:** CRUD lengkap dengan kode SKU unik, harga, stok fisik, batas minimum stok, kategori, dan pemasok multi-relasi.
- **Kategori:** CRUD dengan jumlah agregat produk (`withCount('products')`).
- **Supplier:** CRUD dengan pencatatan kontak perusahaan.

### D. Fitur Search, Filter, Sorting, & Pagination
- **Search:** Pencarian instan berdasarkan nama produk atau kode SKU.
- **Filter Kategori:** Dropdown filter kategori produk.
- **Filter Stok Rendah:** Checkbox khusus untuk menampilkan barang yang stoknya $\le$ minimum stok.
- **Sorting Whitelist:** Pengurutan aman berdasarkan kolom yang diizinkan (`name`, `sku`, `price`, `stock`, `created_at`) dengan opsi Ascending / Descending.
- **Pagination:** Menjaga parameter query string tetap aktif saat berpindah halaman (`withQueryString()`).

### E. Dashboard Eksekutif & Statistik Agregat
- **Total Produk:** `COUNT(products.id)`
- **Nilai Total Inventaris:** `SUM(stock * price)`
- **Peringatan Stok Rendah:** Indikator produk yang berada di bawah ambang batas aman.
- **Ringkasan Mutasi:** Akumulasi kuantitas Barang Masuk (`IN`) vs Barang Keluar (`OUT`).
- **Tabel Transaksi Terbaru:** 6 histori mutasi terakhir secara real-time.

---

## 4. Akun Uji Coba (Demo Seeders)

| Role | Username | Email | Password | Hak Akses |
|---|---|---|---|---|
| **Admin** | `admin` | `admin@inventory.com` | `password123` | Akses Penuh (User, Master Data, Transaksi) |
| **Staff** | `staff` | `staff@inventory.com` | `password123` | Input Mutasi Stok, Lihat Master Data & Dashboard |
| **Staff 2**| `budi` | `budi@inventory.com` | `password123` | Input Mutasi Stok, Lihat Master Data & Dashboard |

---

## 5. Cara Menjalankan Project

### Persyaratan Sistem:
- PHP >= 8.0 (XAMPP PHP)
- MySQL / MariaDB (XAMPP MySQL)
- Composer

### Langkah-langkah:
1. Pastikan Apache & MySQL di XAMPP telah menyala:
   - Database: `inventory_db`
   - Port: `3306`
2. Pindah ke direktori project:
   ```bash
   cd "C:\xampp\htdocs\mini project 3\inventory-laravel"
   ```
3. Jalankan migrasi dan seeder database:
   ```bash
   php artisan migrate:fresh --seed
   ```
4. Jalankan pengujian otomatis (Unit/Feature Tests):
   ```bash
   php artisan test
   ```
   *(Seluruh 11 pengujian lolos: Autentikasi, Otorisasi, CRUD, Search, Filter, DB Transaction, dan Validasi Stok Negatif)*.
5. Jalankan server lokal:
   ```bash
   php artisan serve
   ```
6. Buka browser dan akses:
   ```
   http://localhost:8000
   ```
