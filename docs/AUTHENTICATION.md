# Dokumentasi Sistem Autentikasi & Otorisasi

## 1. Role & Hak Akses

Sistem ini memiliki 3 role utama yang terbagi dalam 2 area:

| Role | Area Akses | Login Page | Guard | Middleware |
|------|------------|------------|-------|------------|
| **Buyer (Customer)** | Halaman Tamu (Produk, Cart, Profile) | `/login` (Guest) | `customer` | `guest.auth` |
| **Sales** | Halaman Tamu (Produk, Cart, Profile) | `/login` (Guest) | `web` | `guest.auth`, `sales` |
| **Admin** | Halaman Admin (Dashboard, Data Master) | `/admin/login` | `web` | `admin` |

## 2. Alur Autentikasi (Authentication Flow)

### A. Login Tamu (Sales / Buyer)
- **Endpoint**: `POST /login`
- **Controller**: `App\Http\Controllers\Guest\AuthController@login`
- **Logic**:
  1. Cek kredensial di guard `customer` (Email/HP).
  2. Jika gagal, cek kredensial di guard `web` (Email).
  3. Jika berhasil di `web`, cek role user.
     - Jika role `sales`: Izinkan login.
     - Jika role `admin`: **Tolak akses** (Logout & Error).
- **Redirection**:
  - Sukses: `/profile` atau halaman yang dituju sebelumnya.
  - Gagal: Kembali ke form login dengan pesan error.

### B. Login Admin
- **Endpoint**: `POST /admin/login`
- **Controller**: `App\Http\Controllers\Admin\AuthController@login`
- **Logic**:
  1. Cek kredensial di guard `web`.
  2. Jika berhasil, cek role user.
     - Jika `admin` atau `super admin`: Izinkan.
     - Jika bukan admin: **Tolak akses** (Logout & Error).
- **Redirection**:
  - Sukses: `/admin/dashboard`.
  - Gagal: Kembali ke form login dengan pesan error.

## 3. Middleware & Proteksi

### A. `EnsureGuestLogin` (`guest.auth`)
Digunakan untuk memproteksi halaman belanja (Cart, Checkout, Profile).
- **Validasi**:
  - Cek apakah user login sebagai `customer` ATAU `sales` (via guard `web`).
- **Respon Gagal**:
  - Redirect ke `/login` dengan pesan flash: *"Silakan login terlebih dahulu untuk mengakses halaman ini."*
- **Konflik Role**:
  - Jika Admin mencoba mengakses halaman ini, redirect ke `/admin/dashboard`.

### B. `EnsureAdmin` (`admin`)
Digunakan untuk memproteksi halaman admin (`/admin/*`).
- **Validasi**:
  - Cek apakah user login di guard `web`.
  - Cek apakah role user adalah `admin` atau `super admin`.
- **Respon Gagal**:
  - Jika belum login: Redirect ke `/admin/login` dengan pesan *"Silakan login sebagai Admin terlebih dahulu."*
  - Jika login sebagai Customer/Sales: Redirect ke `/admin/login` dengan pesan *"Akses ditolak."*

### C. `EnsureSales` (`sales`)
Digunakan untuk route spesifik Sales.
- **Validasi**:
  - Cek role `sales`.
- **Respon Gagal**:
  - Redirect ke `/login` dengan pesan error.

## 4. Keamanan Cart (Keranjang)
- Semua endpoint Cart (`/cart`, `/cart/items`, `/cart/checkout`) dilindungi oleh middleware `guest.auth`.
- User yang belum login **tidak dapat** mengakses fungsi tambah keranjang atau checkout.
- Sistem akan otomatis mengarahkan ke halaman login jika session habis.
