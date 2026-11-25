<?php
$pageTitle = 'Dashboard - Sistem Presensi Praktikum';
require BASE_PATH . '/app/views/layout/header.php';

$user = $user ?? ($_SESSION['user'] ?? []);
$role = $user['role'] ?? 'mahasiswa';
?>

<?php
$pageTitle = 'Dashboard - Sistem Presensi Praktikum';
require BASE_PATH . '/app/views/layout/header.php';

$role = $user['role'];
?>

<section id="dashboard-section">
    <table class="table table-borderless mb-4">
        <tr>
            <td>
                <h2 class="mb-1">Selamat Datang, <?= e($user['name']) ?></h2>
                <p class="text-secondary mb-0">
                    Username: <strong><?= e($user['username']) ?></strong> &bull;
                    Peran: <span class="badge bg-info text-dark text-uppercase"><?= e($role) ?></span>
                </p>
            </td>
        </tr>
    </table>

    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th style="width: 28%;">Menu Utama</th>
                <th>Keterangan Fitur</th>
                <th style="width: 18%;" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($role === 'admin'): ?>
                <tr>
                    <td><strong>Manajemen Pengguna</strong></td>
                    <td>Tambah akun Aslab &amp; Praktikan, reset password, dan atur status aktif.</td>
                    <td class="text-center"><a href="/admin/users" class="btn btn-outline-info btn-sm w-100">Buka</a></td>
                </tr>
                <tr>
                    <td><strong>Mata Kuliah &amp; Penugasan</strong></td>
                    <td>Kelola daftar mata kuliah praktikum, penugasan Aslab, dan enrolment mahasiswa.</td>
                    <td class="text-center"><a href="/admin/courses" class="btn btn-outline-info btn-sm w-100">Buka</a></td>
                </tr>
                <tr>
                    <td><strong>Log Audit Sistem</strong></td>
                    <td>Pantau riwayat aktivitas penting seperti perubahan nilai dan sesi presensi.</td>
                    <td class="text-center"><a href="/admin/audit-logs" class="btn btn-outline-info btn-sm w-100">Buka</a></td>
                </tr>
            <?php elseif ($role === 'aslab'): ?>
                <tr>
                    <td><strong>Sesi Presensi (Token Dinamis)</strong></td>
                    <td>Buka sesi pertemuan baru, tampilkan kode token dinamis, dan tutup sesi.</td>
                    <td class="text-center"><a href="/aslab/sessions" class="btn btn-outline-info btn-sm w-100">Buka</a></td>
                </tr>
                <tr>
                    <td><strong>Penilaian Praktikum</strong></td>
                    <td>Input dan perbarui nilai tugas/laporan praktikan per pertemuan.</td>
                    <td class="text-center"><a href="/grades" class="btn btn-outline-info btn-sm w-100">Buka</a></td>
                </tr>
                <tr>
                    <td><strong>Poin Keaktifan &amp; Rekap</strong></td>
                    <td>Catat poin keaktifan praktikan dan lihat rekapitulasi presensi serta nilai.</td>
                    <td class="text-center"><a href="/recap" class="btn btn-outline-info btn-sm w-100">Buka</a></td>
                </tr>
            <?php else: ?>
                <tr>
                    <td><strong>Isi Presensi Pertemuan</strong></td>
                    <td>Masukkan kode token dinamis yang ditampilkan Aslab saat praktikum berlangsung.</td>
                    <td class="text-center"><a href="/mahasiswa/attendance" class="btn btn-info btn-sm fw-semibold w-100">Presensi</a></td>
                </tr>
                <tr>
                    <td><strong>Lihat Nilai &amp; Poin</strong></td>
                    <td>Pantau nilai setiap pertemuan praktikum dan akumulasi poin keaktifan Anda.</td>
                    <td class="text-center"><a href="/mahasiswa/grades" class="btn btn-outline-info btn-sm w-100">Buka</a></td>
                </tr>
                <tr>
                    <td><strong>Rekap Kehadiran</strong></td>
                    <td>Cek riwayat kehadiran seluruh pertemuan praktikum yang Anda ikuti.</td>
                    <td class="text-center"><a href="/mahasiswa/recap" class="btn btn-outline-info btn-sm w-100">Buka</a></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php require BASE_PATH . '/app/views/layout/footer.php'; ?>