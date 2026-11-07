# Sistem Presensi dan Penilaian Praktikum

Web untuk merekap presensi, keterlambatan, poin keaktifan, dan nilai praktikum, dengan aturan yang bisa berbeda tiap mata kuliah.

Tugas UAS Pemrograman Web. Stack: PHP + MySQL.

## Status
Kerangka project. Fitur ditambahkan bertahap.

## Menjalankan di lokal
Kebutuhan: PHP 8.x dengan ekstensi `pdo_mysql`, MySQL/MariaDB.

1. Buat database, lalu impor `database/schema.sql` (dan `database/seed.sql` untuk data demo).
2. Atur koneksi database lewat environment variable (lihat `.env.example`) atau salin `config/config.local.example.php` menjadi `config/config.local.php`.
3. Jalankan server bawaan PHP dari folder project:
   ```
   php -S localhost:8000 -t public
   ```
4. Buka `http://localhost:8000`. Cek koneksi database di `http://localhost:8000/health.php`.

## Akun demo
Diisi setelah `seed.sql` dibuat.

## Website
Link deployment diisi setelah deploy.

## Struktur
- `public/` satu-satunya folder yang bisa diakses web
- `app/` controller, model, middleware, view, helper
- `config/` konfigurasi (tanpa rahasia)
- `database/` skema dan data contoh
- `uploads/` berkas unggahan (tidak masuk git)
- `docs/` ERD, catatan white box, draf laporan
