# Sistem Presensi dan Penilaian Praktikum

Web untuk merekap presensi, keterlambatan, poin keaktifan, dan nilai praktikum, dengan aturan yang bisa berbeda tiap mata kuliah.

Tugas UAS Pemrograman Web. Stack: PHP + MySQL.

## Progres Pengembangan — Tahap 1
Fitur ditambahkan bertahap pada branch pengembangan (`dev` dan `feat/...`):
- **Inisialisasi & Struktur Direktori:** Menyusun kerangka mini-MVC dengan pemisahan folder `public/`, `app/`, `config/`, `database/`, `uploads/`, dan `docs/`.
- **Skema Database (Tahap 1):** Membuat struktur tabel relasional inti (`users`, `courses`, `course_aslab`, `enrollments`) pada `database/schema.sql`.
- **Koneksi Database & Health Check (`feat/koneksi-db`):** Mengimplementasikan koneksi PDO tunggal (*Singleton*) pada `app/helpers/Database.php` dan endpoint pengujian `public/health.php`.
- **Front Controller & Sistem Routing (`feat/router`):** Membangun mekanisme *autoloader* kelas (`app/helpers/autoload.php`), daftar rute eksplisit (`app/routes.php`), *dispatcher* utama (`public/index.php`), aturan *URL rewriting* Apache (`public/.htaccess`), serta halaman penanganan error `404` (`app/views/errors/404.php`).

## Menjalankan di lokal
Kebutuhan: PHP 8.x dengan ekstensi `pdo_mysql`, MySQL/MariaDB.

1. Buat database, lalu impor `database/schema.sql` (dan `database/seed.sql` untuk data demo).
2. Atur koneksi database lewat environment variable (lihat `.env.example`) atau salin `config/config.local.example.php` menjadi `config/config.local.php`.
3. Jalankan server bawaan PHP dari folder project:php -S localhost:8000 -t public
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