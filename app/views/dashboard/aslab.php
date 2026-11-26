<?php
// Dashboard operasional untuk Asisten Laboratorium (Aslab).
// URL: GET /dashboard (DashboardController@index, role aslab)
$pageTitle = 'Dashboard Aslab - Sistem Presensi Praktikum';
require BASE_PATH . '/app/views/layout/header.php';

$user = $user ?? ($_SESSION['user'] ?? []);
$courses = $courses ?? [];
$openSessions = $openSessions ?? [];
$totalStudents = $totalStudents ?? 0;
?>

<!-- 1. HEADER & AKSI CEPAT -->
<table class="table table-borderless align-middle mb-4">
    <tr>
        <td class="p-0">
            <h3 class="mb-1 text-white">Dashboard Aslab</h3>
            <span class="text-white-50 small">Selamat bertugas, <?= e($user['name'] ?? 'Asisten Praktikum') ?></span>
        </td>
        <td class="p-0 text-end">
            <a href="/aslab/sessions" class="btn btn-info btn-sm fw-semibold me-2">+ Buka Sesi Presensi</a>
            <a href="/grades" class="btn btn-outline-light btn-sm">Input Nilai</a>
        </td>
    </tr>
</table>

<!-- 2. KARTU METRIK RINGKAS (GRID 3 KOLOM) -->
<table class="table table-borderless mb-4" style="table-layout: fixed;">
    <tr>
        <td class="ps-0 pe-2">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-white small text-uppercase">Matkul Diampu</div>
                    <div class="fs-2 fw-bold text-white my-1"><?= count($courses) ?></div>
                    <span class="small text-white-50">Semester berjalan</span>
                </div>
            </div>
        </td>
        <td class="px-2">
            <div class="card border-info h-100">
                <div class="card-body">
                    <div class="text-info small text-uppercase">Sesi Token Aktif</div>
                    <div class="fs-2 fw-bold text-info my-1"><?= count($openSessions) ?></div>
                    <span class="small text-white">Sedang menerima presensi</span>
                </div>
            </div>
        </td>
        <td class="pe-0 ps-2">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-white small text-uppercase">Total Praktikan</div>
                    <div class="fs-2 fw-bold text-white my-1"><?= (int) $totalStudents ?></div>
                    <a href="/recap" class="small text-info text-decoration-none">Lihat rekapitulasi &rarr;</a>
                </div>
            </div>
        </td>
    </tr>
</table>

<!-- 3. SESI PRESENSI YANG SEDANG TERBUKA -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold text-white">Sesi Presensi Sedang Terbuka</span>
        <a href="/aslab/sessions" class="small text-info text-decoration-none">Semua Sesi &rarr;</a>
    </div>
    <div class="card-body p-0">
        <?php if (empty($openSessions)): ?>
            <p class="text-white-50 small p-3 mb-0">
                Tidak ada sesi presensi yang sedang terbuka. Klik <strong>+ Buka Sesi Presensi</strong> saat praktikum dimulai.
            </p>
        <?php else: ?>
            <table class="table table-sm table-hover mb-0 align-middle">
                <thead>
                    <tr class="text-white small">
                        <th class="ps-3">Mata Kuliah</th>
                        <th>Pertemuan</th>
                        <th>Dibuka Pukul</th>
                        <th>Token Aktif</th>
                        <th class="pe-3 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($openSessions as $row): ?>
                        <tr>
                            <td class="ps-3 text-white fw-semibold"><?= e($row['course_name']) ?></td>
                            <td class="text-white">Pertemuan <?= (int) $row['meeting_number'] ?></td>
                            <td class="text-white-50"><?= e($row['opened_at']) ?></td>
                            <td class="font-monospace text-info fw-bold"><?= e($row['token'] ?? '-') ?></td>
                            <td class="pe-3 text-end">
                                <a href="/aslab/sessions/<?= (int) $row['id'] ?>" class="btn btn-info btn-sm fw-semibold">
                                    Tampilkan Token
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<!-- 4. MATA KULIAH PRAKTIKUM YANG DITUGASKAN -->
<div class="card">
    <div class="card-header fw-semibold text-white">
        Mata Kuliah Praktikum Saya
    </div>
    <div class="card-body p-0">
        <?php if (empty($courses)): ?>
            <p class="text-white-50 small p-3 mb-0">
                Anda belum ditugaskan pada mata kuliah praktikum mana pun. Hubungi Administrator.
            </p>
        <?php else: ?>
            <table class="table table-sm table-hover mb-0 align-middle">
                <thead>
                    <tr class="text-white small">
                        <th class="ps-3" style="width: 15%;">Kode</th>
                        <th>Nama Mata Kuliah</th>
                        <th style="width: 18%;">Semester</th>
                        <th class="pe-3 text-end" style="width: 32%;">Aksi Cepat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $row): ?>
                        <tr>
                            <td class="ps-3 font-monospace text-info"><?= e($row['code']) ?></td>
                            <td class="text-white fw-semibold"><?= e($row['name']) ?></td>
                            <td class="text-white-50"><?= e($row['semester']) ?></td>
                            <td class="pe-3 text-end">
                                <a href="/aslab/sessions?course_id=<?= (int) $row['id'] ?>" class="btn btn-outline-info btn-sm me-1">Sesi</a>
                                <a href="/grades?course_id=<?= (int) $row['id'] ?>" class="btn btn-outline-light btn-sm me-1">Nilai</a>
                                <a href="/recap?course_id=<?= (int) $row['id'] ?>" class="btn btn-outline-light btn-sm">Rekap</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require BASE_PATH . '/app/views/layout/footer.php'; ?>