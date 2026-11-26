-- Data contoh untuk demo. Semua data fiktif.
-- Jalankan SEKALI pada database yang sudah berisi schema.sql.
-- Hash di bawah dibuat dengan bcrypt dari kata sandi acak (bukan kata sandi umum).
-- mhs08 sengaja diberi must_change_password = 1 untuk mendemokan alur ganti sandi.
-- Hapus akun demo dari server publik setelah UAS. Jangan menaruh data praktikan asli di file ini.

INSERT INTO users (username, name, password_hash, role, must_change_password, is_active) VALUES
('admin01', 'Dosen Pengampu Jarkom', '$2y$10$fQzDc1GAYTrmSfL/.Be81OxsJxDoiMwoaiwVxeIY6/tSqa8p5G.wK', 'admin', 0, 1),
('aslab01', 'Aslab Contoh 01', '$2y$10$1ER8AxKuKz9dS4ra7eStWO5.R3bW42rLz9Sc/dIVgdEZsn.TkMvuy', 'aslab', 0, 1),
('aslab02', 'Aslab Contoh 02', '$2y$10$1ER8AxKuKz9dS4ra7eStWO5.R3bW42rLz9Sc/dIVgdEZsn.TkMvuy', 'aslab', 0, 1),
('mhs01', 'Praktikan Contoh 01', '$2y$10$4M2W80cPA.KB2PMHpowpyeOx/nEBjxtGpfHPdwKjksY2t7ayQjoRi', 'mahasiswa', 0, 1),
('mhs02', 'Praktikan Contoh 02', '$2y$10$4M2W80cPA.KB2PMHpowpyeOx/nEBjxtGpfHPdwKjksY2t7ayQjoRi', 'mahasiswa', 0, 1),
('mhs03', 'Praktikan Contoh 03', '$2y$10$4M2W80cPA.KB2PMHpowpyeOx/nEBjxtGpfHPdwKjksY2t7ayQjoRi', 'mahasiswa', 0, 1),
('mhs04', 'Praktikan Contoh 04', '$2y$10$4M2W80cPA.KB2PMHpowpyeOx/nEBjxtGpfHPdwKjksY2t7ayQjoRi', 'mahasiswa', 0, 1),
('mhs05', 'Praktikan Contoh 05', '$2y$10$4M2W80cPA.KB2PMHpowpyeOx/nEBjxtGpfHPdwKjksY2t7ayQjoRi', 'mahasiswa', 0, 1),
('mhs06', 'Praktikan Contoh 06', '$2y$10$4M2W80cPA.KB2PMHpowpyeOx/nEBjxtGpfHPdwKjksY2t7ayQjoRi', 'mahasiswa', 0, 1),
('mhs07', 'Praktikan Contoh 07', '$2y$10$4M2W80cPA.KB2PMHpowpyeOx/nEBjxtGpfHPdwKjksY2t7ayQjoRi', 'mahasiswa', 0, 1),
('mhs08', 'Praktikan Contoh 08', '$2y$10$4M2W80cPA.KB2PMHpowpyeOx/nEBjxtGpfHPdwKjksY2t7ayQjoRi', 'mahasiswa', 1, 1);

-- Ubah kode dan semester sesuai kenyataan.
INSERT INTO courses (code, name, term, is_active) VALUES
('JARKOM', 'Praktikum Jaringan Komputer', '2026/2027 Ganjil', 1);

-- Semua aslab ditugaskan ke praktikum jarkom.
INSERT INTO course_aslab (course_id, user_id)
SELECT c.id, u.id FROM courses c JOIN users u ON u.role = 'aslab'
WHERE c.code = 'JARKOM';

-- Semua praktikan terdaftar di praktikum jarkom.
INSERT INTO enrollments (course_id, user_id)
SELECT c.id, u.id FROM courses c JOIN users u ON u.role = 'mahasiswa'
WHERE c.code = 'JARKOM';
