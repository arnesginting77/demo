# SIMPAL (Laravel): Sistem Informasi Inventaris dan Peminjaman Alat Bengkel TI

Implementasi Analisis Studi Kasus (fitur F01 sampai F10) dengan Laravel 11/12 dan MySQL.

## Kebutuhan
PHP 8.2+ (ekstensi mbstring, openssl, pdo_mysql, xml, ctype, fileinfo), Composer, MySQL/MariaDB (XAMPP atau Laragon sudah cukup).

## Pemasangan
1. Ekstrak zip, buka terminal di folder `simpal-laravel`.
2. `composer install`  (mengunduh folder `vendor`, butuh internet)
3. Salin `.env.example` menjadi `.env` (Windows: `copy .env.example .env`). Sesuaikan `DB_USERNAME` dan `DB_PASSWORD` bila perlu.
4. Buat database kosong bernama `simpal` (lewat phpMyAdmin atau `mysql -u root -e "CREATE DATABASE simpal"`).
5. `php artisan key:generate`
6. `php artisan migrate --seed`
7. `php artisan serve`, lalu buka http://127.0.0.1:8000

## Akun awal (ganti sebelum dipakai sungguhan)
| Peran | Username | Kata sandi |
|---|---|---|
| Admin / PLP | admin | admin123 |
| Kepala bengkel | kabeng | kabeng123 |
| Siswa | 1001 sampai 1004 | siswa123 |

## Struktur
- `routes/web.php` rute dan pembatasan peran (middleware `role`)
- `app/Http/Controllers` Auth, Dashboard, Barang, Siswa, Peminjaman, Pengembalian, Laporan
- `app/Models` model dan rumus stok (`Barang::tersedia`, `Peminjaman::status_tampil`)
- `database/migrations` tujuh tabel: users, siswa, barang, peminjaman, detail_peminjaman, pengembalian, detail_pengembalian
- `database/seeders` akun dan data contoh
- `resources/views` tampilan Blade, `public/css/app.css` gaya

## Aturan yang diterapkan
- Stok tersedia = total − rusak − sedang dipinjam. Pengajuan "Menunggu" tidak mengurangi stok.
- Saat persetujuan, baris alat dikunci (`lockForUpdate`) dan stok diperiksa ulang.
- Pengembalian sebagian didukung. Alat rusak masuk kolom `rusak` dan kembali ke stok lewat tombol "Pulihkan".
- Terlambat dihitung otomatis dari tanggal jatuh tempo. Kata sandi di-hash, form dilindungi CSRF.

## Bila terjadi galat saat `composer install`
Paket proyek ini sengaja minimal. Jalan keluar: buat proyek Laravel baru (`composer create-project laravel/laravel simpal`), lalu salin folder `app`, `database`, `lang`, `resources`, `routes`, `public/css`, file `bootstrap/app.php`, dan `.env.example` dari zip ini ke dalamnya.

## Belum tersedia
QR Code, notifikasi WhatsApp/email, grafik statistik, ganti kata sandi dari aplikasi, dan backup terjadwal (gunakan `mysqldump` via Task Scheduler/cron).
