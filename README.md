# PAS Market - PT Pakeffendi

E-commerce untuk penjualan produk PT Pakeffendi, dibangun dengan Laravel 12.

## Stack Teknologi
- PHP 8.5+
- Laravel 12
- MySQL/MariaDB
- Bootstrap 5

## Fitur Utama
- Dashboard Admin (produk, kategori, brand, pesanan, pengguna)
- Halaman Guest (produk, keranjang, checkout, profil)
- Search produk (nama & SKU)
- Cart System
- Multi-role (Admin, Sales, Customer)

## Git Workflow
- `main`: Production branch (hanya untuk merge dari develop)
- `develop`: Development branch (merge fitur/fix)
- `feature/*` & `fix/*`: Branch fitur/bugfix baru

## Instalasi Lokal
1. Clone repository
2. `composer install`
3. `cp .env.example .env` & konfigurasi database
4. `php artisan key:generate`
5. `php artisan migrate --seed`
6. `php artisan storage:link`
7. `php artisan serve`

## Changelog
- 2026-05-13: Fix guest navbar search (hanya search saat Enter/klik tombol, prefill input)
- 2026-05-13: Fix storage product image (nama file asli, spasi jadi "-", flat di products/)
- 2026-05-13: Initial setup git workflow
