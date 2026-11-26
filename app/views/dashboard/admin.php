<?php
// Dashboard admin (dosen pengampu jarkom): ringkasan praktikum Jaringan Komputer.
// URL: GET /dashboard (DashboardController@index, role admin)
$pageTitle = 'Dashboard Admin - Presensi Praktikum Jarkom';
require BASE_PATH . '/app/views/layout/header.php';

// Nilai bawaan aman agar tidak error sebelum DashboardController disambungkan ke DB
$course       = $course ?? null;
$stats        = array_merge([
    'total_aslab'     => 0,
    'total_mahasiswa' => 0,
    'meetings_done'   => 0,
    'open_sessions'   => 0,
], $stats ?? []);
$attention    = array_merge([
    'must_change_password' => 0,
    'stale_sessions'       => 0,
], $attention ?? []);
$openSessions = $openSessions ?? [];
$recentLogs   = $recentLogs ?? [];

$needsAttention = ((int) $attention['must_change_password'] > 0) || ((int) $attention['stale_sessions'] > 0);
?>

<!-- 1. Judul dan aksi cepat -->
<table class="table table-borderless align-middle mb-4">
    <tr>
        <td class="p-0">
            <h3 class="mb-1 text-white">Dashboard Admin</h3>
            <span class="text-white-50 small">
                <?= $course ? e($course['name']) . ' &middot; ' . e($course['term']) : 'Belum ada praktikum aktif' ?>
            </span>
        </td>
        <td class="p-0 text-end">
            <a href="/admin/users/form" class="btn btn-primary btn-sm me-2">+ Pengguna Baru</a>
            <a href="/admin/course" class="btn btn-outline-light btn-sm">Pengaturan Praktikum</a>
        </td>
    </tr>
</table>

<?php if (!$course): ?>
    <div class="alert alert-warning mb-4">
        Belum ada praktikum aktif. Atur di <a href="/admin/course" class="alert-link">Pengaturan Praktikum</a>.
    </div>
<?php endif; ?>

<!-- 2. Kartu statistik -->
<table class="table table-borderless mb-4" style="table-layout: fixed;">
    <tr>
        <td class="ps-0 pe-2">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-white-50 small text-uppercase">Aslab</div>
                    <div class="fs-2 fw-bold text-white my-1"><?= (int) $stats['total_aslab'] ?></div>
                    <a href="/admin/users?role=aslab" class="small text-info text-decoration-none">Kelola aslab &rarr;</a>
                </div>
            </div>
        </td>
        <td class="px-2">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-white-50 small text-uppercase">Praktikan</div>
                    <div class="fs-2 fw-bold text-white my-1"><?= (int) $stats['total_mahasiswa'] ?></div>
                    <a href="/admin/users?role=mahasiswa" class="small text-info text-decoration-none">Kelola praktikan &rarr;</a>
                </div>
            </div>
        </td>
        <td class="px-2">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-white-50 small text-uppercase">Pertemuan Terlaksana</div>
                    <div class="fs-2 fw-bold text-white my-1"><?= (int) $stats['meetings_done'] ?></div>
                    <a href="/recap" class="small text-info text-decoration-none">Lihat rekap &rarr;</a>
                </div>
            </div>
        </td>
        <td class="pe-0 ps-2">
            <div class="card h-100 <?= (int) $stats['open_sessions'] > 0 ? 'border-info' : '' ?>">
                <div class="card-body">
                    <div class="text-white-50 small text-uppercase">Sesi Terbuka</div>
                    <div class="fs-2 fw-bold text-white my-1"><?= (int) $stats['open_sessions'] ?></div>
                    <span class="small text-white-50">Sedang menerima presensi</span>
                </div>
            </div>
        </td>
    </tr>
</table>

<!-- 3. Perlu perhatian: hanya tampil bila ada yang perlu ditindak -->
<?php if ($needsAttention): ?>
    <div class="card border-warning mb-4">
        <div class="card-header fw-semibold text-warning">Perlu Perhatian</div>
        <ul class="list-group list-group-flush">
            <?php if ((int) $attention['stale_sessions'] > 0): ?>
                <li class="list-group-item bg-transparent text-white border-secondary d-flex justify-content-between align-items-center">
                    <span><?= (int) $attention['stale_sessions'] ?> sesi masih terbuka terlalu lama (kemungkinan lupa ditutup)</span>
                    <a href="#sesi-terbuka" class="small text-info text-decoration-none">Lihat &darr;</a>
                </li>
            <?php endif; ?>
            <?php if ((int) $attention['must_change_password'] > 0): ?>
                <li class="list-group-item bg-transparent text-white border-secondary d-flex justify-content-between align-items-center">
                    <span><?= (int) $attention['must_change_password'] ?> akun belum mengganti kata sandi awal</span>
                    <a href="/admin/users" class="small text-info text-decoration-none">Kelola akun &rarr;</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- 4. Sesi terbuka dan aktivitas terbaru -->
<table class="table table-borderless" style="table-layout: fixed;">
    <tr>
        <!-- Kiri: sesi yang sedang terbuka -->
        <td class="ps-0 pe-3 align-top" style="width: 55%;">
            <div class="card" id="sesi-terbuka">
                <div class="card-header fw-semibold text-white">Sesi Presensi Sedang Terbuka</div>
                <div class="card-body p-0">
                    <?php if (empty($openSessions)): ?>
                        <p class="text-white-50 small p-3 mb-0">Tidak ada sesi presensi yang sedang terbuka.</p>
                    <?php else: ?>
                        <table class="table table-sm table-hover mb-0 align-middle">
                            <thead>
                                <tr class="small text-white-50">
                                    <th class="ps-3">Pertemuan</th>
                                    <th>Aslab</th>
                                    <th class="pe-3 text-end">Dibuka</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($openSessions as $ses): ?>
                                    <tr>
                                        <td class="ps-3 text-white">Ke-<?= (int) $ses['meeting_number'] ?></td>
                                        <td class="text-white"><?= e($ses['aslab_name']) ?></td>
                                        <td class="pe-3 text-end text-white-50"><?= e($ses['opened_at']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </td>

        <!-- Kanan: audit log terbaru -->
        <td class="pe-0 ps-3 align-top" style="width: 45%;">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-semibold text-white">Aktivitas Terbaru</span>
                    <a href="/admin/audit-logs" class="small text-info text-decoration-none">Semua log</a>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($recentLogs)): ?>
                        <p class="text-white-50 small p-3 mb-0">Belum ada aktivitas tercatat.</p>
                    <?php else: ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($recentLogs as $log): ?>
                                <li class="list-group-item bg-transparent text-white border-secondary small">
                                    <div><?= e($log['action']) ?></div>
                                    <div class="text-white-50" style="font-size: 0.75rem;">
                                        <?= e($log['actor_name']) ?> &bull; <?= e($log['created_at']) ?>
                                    </div>
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