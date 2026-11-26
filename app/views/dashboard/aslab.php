<?php
// Dashboard aslab: status sesi presensi Jarkom, ringkasan praktikan, dan progres pertemuan.
// URL: GET /dashboard (DashboardController@index, role aslab)
$pageTitle = 'Dashboard Aslab - Presensi Praktikum Jarkom';
require BASE_PATH . '/app/views/layout/header.php';

$user = $user ?? ($_SESSION['user'] ?? []);
$course = $course ?? null;
$stats = array_merge([
    'total_mahasiswa' => 0,
    'meetings_done'   => 0,
    'open_sessions'   => 0,
], $stats ?? []);
$openSessions = $openSessions ?? [];
$recentMeetings = $recentMeetings ?? [];
?>

<!-- 1. Header & Aksi Cepat -->
<table class="table table-borderless align-middle mb-4">
    <tr>
        <td class="p-0">
            <h3 class="mb-1 text-white">Dashboard Aslab</h3>
            <span class="text-white-50 small">
                <?= e($user['name'] ?? 'Aslab') ?>
                <?= $course ? ' &middot; ' . e($course['name']) . ' (' . e($course['term']) . ')' : '' ?>
            </span>
        </td>
        <td class="p-0 text-end">
            <a href="/aslab/sessions" class="btn btn-primary btn-sm me-2">+ Buka Sesi Presensi</a>
            <a href="/grades" class="btn btn-outline-light btn-sm me-2">Input Nilai</a>
            <a href="/points" class="btn btn-outline-light btn-sm">Poin Keaktifan</a>
        </td>
    </tr>
</table>

<!-- 2. Kartu Statistik -->
<table class="table table-borderless mb-4" style="table-layout: fixed;">
    <tr>
        <td class="ps-0 pe-2">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-white-50 small text-uppercase">Praktikan Terdaftar</div>
                    <div class="fs-2 fw-bold text-white my-1"><?= (int) $stats['total_mahasiswa'] ?></div>
                    <a href="/recap" class="small text-info text-decoration-none">Lihat rekapitulasi &rarr;</a>
                </div>
            </div>
        </td>
        <td class="px-2">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-white-50 small text-uppercase">Pertemuan Terlaksana</div>
                    <div class="fs-2 fw-bold text-white my-1"><?= (int) $stats['meetings_done'] ?></div>
                    <a href="/aslab/sessions" class="small text-info text-decoration-none">Kelola pertemuan &rarr;</a>
                </div>
            </div>
        </td>
        <td class="pe-0 ps-2">
            <div class="card h-100 <?= count($openSessions) > 0 ? 'border-info' : '' ?>">
                <div class="card-body">
                    <div class="text-white-50 small text-uppercase">Sesi Terbuka</div>
                    <div class="fs-2 fw-bold text-white my-1"><?= count($openSessions) ?></div>
                    <span class="small text-white-50">Sedang menerima presensi</span>
                </div>
            </div>
        </td>
    </tr>
</table>

<!-- 3. Sesi Terbuka dan Riwayat Pertemuan Terakhir -->
<table class="table table-borderless" style="table-layout: fixed;">
    <tr>
        <!-- Kiri: Sesi yang sedang terbuka -->
        <td class="ps-0 pe-3 align-top" style="width: 55%;">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-semibold text-white">Sesi Presensi Sedang Terbuka</span>
                    <a href="/aslab/sessions" class="small text-info text-decoration-none">Semua sesi</a>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($openSessions)): ?>
                        <p class="text-white-50 small p-3 mb-0">
                            Tidak ada sesi presensi yang sedang terbuka. Klik <strong>+ Buka Sesi Presensi</strong> saat praktikum dimulai.
                        </p>
                    <?php else: ?>
                        <table class="table table-sm table-hover mb-0 align-middle">
                            <thead>
                                <tr class="small text-white-50">
                                    <th class="ps-3">Pertemuan</th>
                                    <th>Dibuka Pukul</th>
                                    <th class="pe-3 text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($openSessions as $ses): ?>
                                    <tr>
                                        <td class="ps-3 text-white fw-semibold">Ke-<?= (int) $ses['meeting_number'] ?></td>
                                        <td class="text-white-50"><?= e($ses['opened_at']) ?></td>
                                        <td class="pe-3 text-end">
                                            <a href="/aslab/sessions/<?= (int) $ses['id'] ?>" class="btn btn-primary btn-sm">
                                                Layar Token &rarr;
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </td>

        <!-- Kanan: Pertemuan Terakhir -->
        <td class="pe-0 ps-3 align-top" style="width: 45%;">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-semibold text-white">Pertemuan Terakhir</span>
                    <a href="/grades" class="small text-info text-decoration-none">Input nilai</a>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($recentMeetings)): ?>
                        <p class="text-white-50 small p-3 mb-0">Belum ada pertemuan yang selesai dilaksanakan.</p>
                    <?php else: ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($recentMeetings as $m): ?>
                                <li class="list-group-item bg-transparent text-white border-secondary d-flex justify-content-between align-items-center small">
                                    <div>
                                        <div class="fw-semibold">Pertemuan Ke-<?= (int) $m['meeting_number'] ?></div>
                                        <div class="text-white-50" style="font-size: 0.75rem;"><?= e($m['session_date'] ?? '-') ?></div>
                                    </div>
                                    <span class="badge bg-secondary"><?= (int) ($m['present_count'] ?? 0) ?> Hadir</span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </td>
    </tr>
</table>

<?php require BASE_PATH . '/app/views/layout/footer.php'; ?>