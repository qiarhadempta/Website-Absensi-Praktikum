<?php
$pageTitle = 'Dashboard Admin - Sistem Presensi Praktikum';
require BASE_PATH . '/app/views/layout/header.php';

$user = $user ?? ($_SESSION['user'] ?? []);
$stats = $stats ?? [];
$activeSessions = $activeSessions ?? [];
$recentLogs = $recentLogs ?? [];
?>

<table class="table table-borderless align-middle mb-4">
    <tr>
        <td class="p-0">
            <h3 class="mb-1 text-white">Dashboard Admin</h3>
            <span class="text-white-50 small">Login sebagai <?= e($user['name'] ?? 'Admin') ?></span>
        </td>
        <td class="p-0 text-end">
            <a href="/admin/users" class="btn btn-info btn-sm fw-semibold me-2">+ Pengguna Baru</a>
            <a href="/admin/courses" class="btn btn-outline-light btn-sm">+ Mata Kuliah</a>
        </td>
    </tr>
</table>

<table class="table table-borderless mb-4" style="table-layout: fixed;">
    <tr>
        <td class="ps-0 pe-2">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-white small text-uppercase">Matkul Aktif</div>
                    <div class="fs-2 fw-bold text-white my-1"><?= (int) ($stats['active_courses'] ?? 0) ?></div>
                    <a href="/admin/courses" class="small text-info text-decoration-none">Lihat daftar &rarr;</a>
                </div>
            </div>
        </td>
        <td class="px-2">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-white small text-uppercase">Total Aslab</div>
                    <div class="fs-2 fw-bold text-white my-1"><?= (int) ($stats['total_aslab'] ?? 0) ?></div>
                    <a href="/admin/users?role=aslab" class="small text-info text-decoration-none">Kelola aslab &rarr;</a>
                </div>
            </div>
        </td>
        <td class="px-2">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-white small text-uppercase">Total Praktikan</div>
                    <div class="fs-2 fw-bold text-white my-1"><?= (int) ($stats['total_mahasiswa'] ?? 0) ?></div>
                    <a href="/admin/users?role=mahasiswa" class="small text-info text-decoration-none">Kelola praktikan &rarr;</a>
                </div>
            </div>
        </td>
        <td class="pe-0 ps-2">
            <div class="card border-info h-100">
                <div class="card-body">
                    <div class="text-info small text-uppercase">Sesi Token Aktif</div>
                    <div class="fs-2 fw-bold text-info my-1"><?= count($activeSessions) ?></div>
                    <span class="small text-white">Sedang berlangsung</span>
                </div>
            </div>
        </td>
    </tr>
</table>

<!-- DATA OPERASIONAL: SESI BERLANGSUNG & LOG AKTIVITAS TERBARU -->
<table class="table table-borderless" style="table-layout: fixed;">
    <tr>
        <!-- Kolom Kiri: Sesi Presensi Aktif -->
        <td class="ps-0 pe-3 align-top" style="width: 55%;">
            <div class="card">
                <div class="card-header fw-semibold text-white">
                    Sesi Presensi Sedang Terbuka
                </div>
                <div class="card-body p-0">
                    <?php if (empty($activeSessions)): ?>
                        <p class="text-white-50 small p-3 mb-0">Tidak ada sesi presensi yang sedang terbuka saat ini.</p>
                    <?php else: ?>
                        <table class="table table-sm table-hover mb-0 align-middle">
                            <thead>
                                <tr class="text-white small">
                                    <th class="ps-3">Mata Kuliah</th>
                                    <th>Pertemuan</th>
                                    <th>Aslab</th>
                                    <th class="pe-3 text-end">Token</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($activeSessions as $ses): ?>
                                    <tr>
                                        <td class="ps-3"><?= e($ses['course_name']) ?></td>
                                        <td>Ke-<?= (int) $ses['meeting_number'] ?></td>
                                        <td><?= e($ses['aslab_name']) ?></td>
                                        <td class="pe-3 text-end font-monospace text-info fw-bold"><?= e($ses['token']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </td>

        <!-- Kolom Kanan: Log Audit Terbaru -->
        <td class="pe-0 ps-3 align-top" style="width: 45%;">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-semibold text-white">Aktivitas Terbaru</span>
                    <a href="/admin/audit-logs" class="small text-info text-decoration-none">Semua Log</a>
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