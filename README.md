# Website-Absensi-Praktikum - UAS Pemrograman Web

**Judul kerja:** Sistem Presensi dan Penilaian Praktikum Berbasis Web
**Sifat:** individu | **Presentasi:** W16 (urutan diacak) | **Output:** laporan format KP, website (code + DB deployed), source code di git

> Dokumen ini punya tiga bagian kerja: ketentuan yang harus dipenuhi, keputusan teknis, dan checklist. Centang `[x]` saat selesai. Yang bertanda **(ASUMSI)** belum kamu putuskan, ubah kalau salah.

---

## 1. Ketentuan UAS (dari dosen)

### Output wajib
- [ ] Laporan format KP
- [ ] Fitur wajib: Auth + CRUD (bebas library dan framework)
- [ ] Minimal 1 role, ada access control
- [ ] Website dengan code dan DB sudah **deployed**
- [ ] Source code di git

### Presentasi
| Hal | Ketentuan |
|---|---|
| Durasi | 7 menit presentasi + 1 menit buffer transisi (slot 8 menit) |
| Format | Tanpa PPT. Laporan tidak ditampilkan. Cukup demo aplikasi yang sudah deployed |
| Cadangan | Siapkan kode di **local** yang bisa langsung dijalankan dan diubah |
| Jadwal | Tidak menerima presentasi di luar jadwal dan waktu yang sudah ditentukan |
| Urutan | Diacak. Dua hari, 12 mahasiswa per hari, mulai 07.30 |

### Sanksi dan aturan AI
- AI boleh dipakai, tetapi kamu **harus bertanggung jawab** atas kodenya.
- Akan ada **random check white box**.
- Gagal menjelaskan kode yang ditulis → diminta **tugas tambahan live coding** dalam sesi yang sama.
- Soal live coding terkait proyek yang kamu buat, dengan batasan waktu.

---

## 2. Konsekuensi teknis dari ketentuan itu

1. **Setiap baris kode harus bisa kamu jelaskan.** Jangan menempelkan kode dari AI atau tutorial yang belum kamu pahami. Fitur yang tidak bisa kamu jelaskan lebih baik dipotong.
2. **Demo hanya 7 menit.** Siapkan akun dan data contoh (seed) supaya tidak ada pendaftaran atau pengisian data saat demo.
3. **Live coding terkait proyekmu.** Struktur harus sederhana dan konsisten, supaya kamu bisa cepat menemukan lokasi kode untuk logika seperti "telat lewat N menit = alpa" atau "cek hak akses aslab".
4. **Ada dua lingkungan yang harus berjalan:** server deployed untuk demo, dan local untuk cadangan serta live coding. Pastikan keduanya diuji sebelum hari H.
5. **Tidak ada ruang untuk bug di menit terakhir.** Fitur dibekukan lebih awal, sisa waktu dipakai untuk pengujian, laporan, dan latihan.

---

## 3. Keputusan proyek

### Aktor
| Aktor | Kemampuan |
|---|---|
| Admin (koordinator/dosen) | Kelola matkul dan aturannya, akun aslab, assign aslab ke matkul, enrol mahasiswa (import CSV), lihat semua rekap dan audit log |
| Aslab | Hanya di matkul yang ditugaskan: buka/tutup sesi, tampilkan kode sesi, koreksi presensi manual, setujui izin/sakit, input poin dan nilai, rekap + export |
| Mahasiswa | Login, absen dengan kode sesi, ajukan izin/sakit, lihat presensi, poin, dan nilai **miliknya sendiri** |

### Mekanisme presensi (versi desktop)
1. Aslab membuka sesi. Layar aslab menampilkan **kode 6 digit yang berganti tiap 30-60 detik**.
2. Mahasiswa yang sudah login mengetik kode di halaman "Absen".
3. Server memeriksa: kode valid, sesi terbuka, belum ada catatan presensi. Waktu dicatat dari **server**.
4. Masuk dalam toleransi → hadir. Lewat toleransi → ditolak (alpa).
5. Aslab menutup sesi → mahasiswa tanpa catatan otomatis alpa. Setelah ditutup, koreksi hanya manual oleh aslab dengan alasan, tercatat di audit log.
6. Kode dihitung dari `HMAC(secret_sesi, floor(waktu/30))` dipotong 6 digit. Terima jendela saat ini dan satu jendela sebelumnya.
7. Input kode dibatasi (rate limit), misalnya 5 percobaan per menit per user.

### Aturan per matkul (konfigurasi, bukan kode)
- `toleransi_menit`
- Komponen nilai dan bobotnya (laprak, tugas, poin keaktifan, dll.)

### Cakupan
**Masuk:**
- Auth + role + access control
- Matkul, enrolment, sesi
- Presensi dengan kode berputar
- Rekap + export CSV
- Izin/sakit dengan bukti
- Koreksi manual + audit log
- Poin keaktifan (log manual oleh aslab)
- Komponen nilai dan nilai akhir tertimbang

**Tidak masuk (tulis sebagai pengembangan lanjutan di laporan):**
- Tanya jawab real-time
- Aturan kompleks di luar toleransi dan bobot nilai
- Integrasi dengan sistem akademik
- PWA
- Pembatasan IP (opsional, hanya jika rentang IP lab bisa dipastikan)

### Yang belum diputuskan
- [ ] **Stack (ASUMSI): PHP + MySQL/MariaDB**, sesuai materi kuliah. Ganti kalau kamu memilih yang lain.
- [ ] Tempat hosting dan database (putuskan **sebelum** tahap 1 selesai)
- [ ] Akun mahasiswa dibuat lewat import CSV oleh admin/aslab (ASUMSI: tidak ada registrasi bebas)
- [ ] Praktikum memakai komputer lab atau laptop sendiri (menentukan apakah pembatasan IP masuk akal)

---

## 4. Setup GitHub

### Repo
- [ ] Buat repo, misalnya `praktikum-presensi`
- [ ] Tentukan public atau private. Kalau private, pastikan dosen bisa mengakses bila meminta link.
- [ ] Tambahkan `README.md`, `.gitignore`, `.env.example` di commit pertama
- [ ] **Jangan pernah commit:** `.env`, password DB, file upload, dump data asli. Kalau terlanjur, ganti kredensialnya, jangan hanya menghapus file.

### `.gitignore` minimal
```
.env
config/config.php
/uploads/*
!/uploads/.gitkeep
vendor/
node_modules/
*.log
.DS_Store
```

### Branch dan commit
- `main` = selalu bisa dijalankan dan sama dengan yang di-deploy
- Satu fitur satu branch: `feat/auth`, `feat/attendance`, `feat/recap`
- Merge ke `main` hanya jika fitur sudah diuji
- Pesan commit singkat dan jelas: `feat: tambah login`, `fix: hitung telat pakai waktu server`, `docs: update README`
- Beri tag di tiap milestone: `v0.1-auth`, `v0.2-attendance`, `v1.0-final`
- Commit kecil dan sering, supaya riwayat git menunjukkan kamu yang mengerjakan

### README minimal
- Deskripsi singkat, daftar fitur
- Cara menjalankan di local (kebutuhan, import `schema.sql` dan `seed.sql`, salin `.env.example` ke `.env`)
- Akun demo untuk tiap role
- Link website deployed

---

## 5. Struktur project (ASUMSI: PHP, MVC sederhana)

```
praktikum-presensi/
├── public/                 # satu-satunya folder yang bisa diakses web
│   ├── index.php           # front controller / router
│   └── assets/             # css, js
├── app/
│   ├── controllers/        # AuthController, SessionController, AttendanceController, ...
│   ├── models/             # akses DB (User, Course, Attendance, ...)
│   ├── middleware/         # cek login, cek role, cek akses matkul
│   ├── views/              # template halaman
│   └── helpers/            # csrf, validasi, generator kode sesi
├── config/
│   └── config.example.php
├── database/
│   ├── schema.sql          # struktur tabel
│   └── seed.sql            # data contoh untuk demo
├── uploads/                # bukti izin/sakit, di luar akses langsung
├── docs/
│   ├── erd.png
│   ├── whitebox.md         # catatan penjelasan kode (lihat bagian 8)
│   └── laporan/            # draf laporan KP
├── .env.example
├── .gitignore
└── README.md
```

**Aturan struktur:**
- Hanya `public/` yang menjadi document root. Folder `app/`, `config/`, dan `database/` tidak boleh bisa dibuka lewat URL.
- Cek hak akses ditaruh di **middleware**, bukan tersebar di tiap view. Dengan begitu saat live coding kamu tahu persis di mana mengubahnya.
- File upload divalidasi tipe dan ukuran, disimpan dengan nama acak, dan tidak dieksekusi sebagai script.

### Tabel utama
`users`, `courses`, `course_aslab`, `enrollments`, `sessions`, `attendance`, `leave_requests`, `point_logs`, `grade_components`, `grades`, `audit_logs`

---

## 6. Timeline check

**Catatan penting:** tanggal W16 belum saya ketahui. Isi kolom *Target* dengan tanggal nyata dari kalender akademik, lalu hitung mundur dari hari presentasimu. Aturan pegangan: **fitur dibekukan minimal 2 minggu sebelum W16.**

### Tahap 0: Persiapan
Target: ____
- [ ] Putuskan stack dan hosting
- [ ] Buat repo GitHub, commit awal (README, .gitignore, .env.example)
- [ ] Buat struktur folder
- [ ] Siapkan environment local yang berjalan (web server + DB)
- [ ] Gambar ERD awal dan simpan di `docs/`

**Selesai jika:** `git clone` lalu menjalankan README menghasilkan halaman kosong yang tampil.

### Tahap 1: Fondasi dan deploy awal
Target: ____
- [ ] `schema.sql` untuk tabel `users`, `courses`, `course_aslab`, `enrollments`
- [ ] Login dan logout, password di-hash (bcrypt/Argon2)
- [ ] Session aman: `HttpOnly`, `SameSite`, regenerate ID setelah login
- [ ] Role dan middleware (admin, aslab, mahasiswa)
- [ ] CSRF token di semua form
- [ ] **Deploy versi kosong ini ke hosting** (HTTPS aktif, DB tersambung)

**Selesai jika:** tiga akun dengan role berbeda bisa login di server deployed dan dialihkan ke halaman yang sesuai.

### Tahap 2: Matkul, enrolment, sesi
Target: ____
- [ ] CRUD matkul + konfigurasi `toleransi_menit`
- [ ] CRUD akun aslab dan assign ke matkul
- [ ] Import CSV mahasiswa (NIM) dan enrol ke matkul
- [ ] Buat sesi praktikum (jadwal, status belum dibuka/terbuka/ditutup)
- [ ] Cek akses matkul: aslab A tidak bisa membuka matkul B

**Selesai jika:** admin bisa menyiapkan satu matkul lengkap dengan aslab dan mahasiswa tanpa menyentuh database langsung.

### Tahap 3: Inti presensi
Target: ____
- [ ] Buka dan tutup sesi oleh aslab
- [ ] Halaman kode sesi berputar (HMAC) di sisi aslab
- [ ] Halaman absen mahasiswa, validasi kode dengan jendela waktu
- [ ] Hitung hadir/alpa dari **waktu server** dan toleransi
- [ ] Rate limit input kode
- [ ] Unique constraint: satu mahasiswa satu presensi per sesi
- [ ] Saat sesi ditutup, yang belum absen otomatis alpa
- [ ] Koreksi manual oleh aslab, wajib alasan, tercatat di `audit_logs`

**Selesai jika:** satu sesi bisa dijalankan penuh dari buka sampai tutup, termasuk kasus telat dan koreksi manual.

### Tahap 4: Rekap dan izin/sakit
Target: ____
- [ ] Rekap presensi per matkul dan per mahasiswa
- [ ] Export CSV
- [ ] Pengajuan izin/sakit + upload bukti (validasi file)
- [ ] Persetujuan oleh aslab mengubah status presensi
- [ ] Halaman mahasiswa: lihat presensi sendiri

**Selesai jika:** masalah rekap kehadiran yang kamu ceritakan bisa diselesaikan hanya dengan satu halaman rekap.

### Tahap 5: Poin dan penilaian
Target: ____
- [ ] Log poin keaktifan oleh aslab (mahasiswa, sesi, poin, keterangan)
- [ ] Komponen nilai per matkul + bobot
- [ ] Input nilai per komponen
- [ ] Nilai akhir tertimbang (rapikan dan uji perhitungannya)
- [ ] Halaman mahasiswa: lihat poin dan nilai sendiri

**Selesai jika:** nilai akhir satu mahasiswa bisa dihitung dan angkanya cocok dengan hitungan manual.

### Tahap 6: Pembekuan fitur dan pengerasan keamanan
Target: ____ (**minimal 2 minggu sebelum W16**)
- [ ] Jalankan checklist keamanan (bagian 7)
- [ ] Uji akses silang: URL data matkul lain dan data mahasiswa lain harus ditolak
- [ ] Uji input aneh: karakter khusus, kode salah berulang, upload file berbahaya
- [ ] Perbaiki bug, tidak ada fitur baru
- [ ] Tag `v1.0-final`, deploy ulang

**Selesai jika:** semua checklist keamanan lulus dan tidak ada bug yang kamu ketahui di alur demo.

### Tahap 7: Laporan format KP
Target: ____
- [ ] Latar belakang dan rumusan masalah (dari pengalaman praktikum nyata)
- [ ] Tujuan dan batasan masalah (cakupan bagian 3)
- [ ] Analisis kebutuhan (aktor, fitur, aturan telat)
- [ ] Perancangan (ERD, alur presensi, hak akses)
- [ ] Implementasi (potongan kode penting, tangkapan layar)
- [ ] Pengujian (uji akses, uji aturan telat, uji keamanan)
- [ ] Penutup dan pengembangan lanjutan (yang tidak dikerjakan)
- [ ] Tulis sambil mengerjakan, jangan menunggu semua selesai

### Tahap 8: Persiapan UAS
Target: ____
- [ ] Lengkapi `docs/whitebox.md` (bagian 8)
- [ ] Siapkan data demo: seed berisi akun tiap role, satu matkul, satu sesi siap
- [ ] Siapkan cadangan local: clone terbaru, DB ter-import, **sudah dites dijalankan**
- [ ] Siapkan akses internet cadangan (hotspot) kalau server deployed bermasalah
- [ ] Latihan demo dengan stopwatch, target di bawah 7 menit
- [ ] Latihan live coding (bagian 8)
- [ ] Cek ulang jadwalmu begitu urutan diacak diumumkan

---

## 7. Checklist keamanan

- [ ] Password di-hash dengan bcrypt/Argon2, tidak ada MD5/SHA mentah
- [ ] Cookie session: `HttpOnly`, `Secure`, `SameSite`, session ID di-regenerate setelah login
- [ ] Semua query memakai prepared statement
- [ ] Semua output di-escape (XSS)
- [ ] CSRF token di semua form yang mengubah data
- [ ] Hak akses dicek di **server** pada setiap request, bukan hanya menyembunyikan tombol
- [ ] Kepemilikan data dicek (mahasiswa hanya melihat miliknya, aslab hanya matkul yang ditugaskan)
- [ ] Rate limit login dan input kode sesi
- [ ] Upload: validasi tipe dan ukuran, nama file acak, tidak bisa dieksekusi
- [ ] `.env` dan kredensial tidak ada di git
- [ ] Pesan error tidak membocorkan detail (stack trace, query)
- [ ] Perubahan presensi dan nilai tercatat di audit log

---

## 8. Persiapan UAS: demo dan white box

### Skrip demo (target 7 menit)
| Menit | Isi |
|---|---|
| 0:00-0:30 | Masalah dan solusi dalam dua kalimat |
| 0:30-1:30 | Login sebagai admin: matkul dengan toleransi dan komponen nilai |
| 1:30-3:30 | Aslab membuka sesi, kode berputar tampil, mahasiswa absen (satu tepat waktu, satu telat jadi alpa), sesi ditutup |
| 3:30-4:30 | Koreksi manual + audit log, persetujuan izin/sakit |
| 4:30-5:30 | Rekap dan export CSV, nilai dan poin |
| 5:30-6:30 | Tunjukkan satu bukti access control (aslab matkul A ditolak di matkul B) |
| 6:30-7:00 | Penutup, siap menjawab pertanyaan |

Jangan mendaftar atau mengisi data panjang saat demo. Semua sudah ada di seed.

### Catatan white box (`docs/whitebox.md`)
Untuk setiap fitur inti, tulis: file dan fungsi yang terlibat, alur singkat, dan alasan desainnya. Minimal untuk:
- Login dan pengaturan session
- Middleware role dan akses matkul
- Pembuatan dan validasi kode sesi berputar
- Penentuan hadir/alpa dan penutupan sesi
- Rate limit
- Perhitungan nilai akhir
- Audit log

Tes dirimu: tutup editor, jelaskan alurnya dari ingatan. Yang tidak bisa dijelaskan harus dipelajari ulang atau disederhanakan.

### Latihan live coding
Karena soal live coding terkait proyekmu dan dibatasi waktu, latih perubahan kecil seperti:
- Mengubah aturan telat (misalnya tambah status "terlambat" sebelum alpa)
- Menambah role atau membatasi satu halaman untuk role tertentu
- Menambah kolom pada CRUD (form, tabel, query)
- Menambah filter pada rekap

Hitung waktunya. Kalau satu perubahan kecil membutuhkan lebih dari beberapa menit karena kamu mencari lokasi kodenya, struktur proyekmu terlalu berantakan.

---

## 9. Risiko terbesar

1. **Hosting terlambat disiapkan.** Deploy kerangka kosong di tahap 1, jangan menunggu aplikasi jadi.
2. **Fitur berlebih.** Scope di bagian 3 sudah cukup berat untuk satu orang. Tambah hanya jika tahap 6 sudah lewat.
3. **Kode yang tidak kamu pahami.** Konsekuensinya langsung: live coding tambahan di hadapan dosen.
4. **Perhitungan nilai dan rekap.** Terlihat sederhana, tetapi sering lebih lama dari perkiraan. Beri waktu cukup di tahap 4 dan 5.
5. **Tidak ada cadangan.** Dua lingkungan (deployed dan local) harus sudah dites sebelum hari H.
