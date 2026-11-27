<?php
// Riwayat perubahan penting (koreksi presensi, perubahan nilai, manajemen akun).
// URL: GET /admin/audit-logs (AuditLogController@index)
$pageTitle = 'Audit Log - Presensi Praktikum Jarkom';
require BASE_PATH . '/app/views/layout/header.php';

$logs = $logs ?? [];
$selectedAction = $_GET['action'] ?? '';
?>

<!-- 1. Header -->
<table class="table table-borderless align-middle mb-4">
    <tr>
        <td class="p-0">
            <h3 class="mb-1 text-white">Audit Log Sistem</h3>
            <span class="text-white-50 small">Jejak perubahan data presensi, nilai modul, poin, dan keamanan akun.</span>
        </td>
        <td class="p-0 text-end">
            <a href="/dashboard" class="btn btn-outline-light btn-sm">&larr; Kembali ke Dashboard</a>
        </td>
    </tr>
</table>

<!-- 2. Filter Aksi -->
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="get" action="/admin/audit-logs" class="m-0 p-0 bg-transparent">
            <table class="table table-borderless align-middle mb-0">
                <tr>
                    <td class="ps-0" style="width: 140px;">
                        <label for="action" class="form-label text-white small mb-0">Filter Kategori:</label>
                    </td>
                    <td style="width: 260px;">
                        <select name="action" id="action" class="form-select form-select-sm my-0">
                            <option value="">Semua Aktivitas</option>
                            <option value="ATTENDANCE" <?= $selectedAction === 'ATTENDANCE' ? 'selected' : '' ?>>Koreksi Presensi</option>
                            <option value="GRADE" <?= $selectedAction === 'GRADE' ? 'selected' : '' ?>>Perubahan Nilai</option>
                            <option value="POINT" <?= $selectedAction === 'POINT' ? 'selected' : '' ?>>Poin Keaktifan</option>
                            <option value="USER" <?= $selectedAction === 'USER' ? 'selected' : '' ?>>Manajemen Akun</option>
                        </select>
                    </td>
                    <td class="pe-0">
                        <button type="submit" class="btn btn-primary btn-sm me-1">Terapkan</button>
                        <?php if ($selectedAction !== ''): ?>
                            <a href="/admin/audit-logs" class="btn btn-outline-light btn-sm">Reset</a>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<!-- 3. Tabel Log Audit -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold text-white">Catatan Aktivitas</span>
        <span class="text-white-50 small"><?= count($logs) ?> entri ditampilkan</span>
    </div>
    <div class="card-body p-0">
        <?php if (empty($logs)): ?>
            <p class="text-white-50 small p-4 mb-0 text-center">
                Belum ada catatan aktivitas yang terekam pada audit log.
            </p>
        <?php else: ?>
            <table class="table table-sm table-hover mb-0 align-middle">
                <thead>
                    <tr class="small text-white-50">
                        <th class="ps-3" style="width: 18%;">Waktu</th>
                        <th style="width: 22%;">Pelaku</th>
                        <th style="width: 18%;">Aksi</th>
                        <th class="pe-3">Rincian Perubahan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $row): ?>
                        <?php
                        $role = strtoupper($row['actor_role'] ?? '');
                        $badgeClass = $role === 'ADMIN' ? 'bg-info' : 'bg-secondary';
                        ?>
                        <tr>
                            <td class="ps-3 font-monospace text-white-50 small">
                                <?= e($row['created_at'] ?? '-') ?>
                            </td>
                            <td>
                                <span class="text-white fw-semibold"><?= e($row['actor_name'] ?? 'Sistem') ?></span>
                                <?php if ($role !== ''): ?>
                                    <span class="badge <?= $badgeClass ?> ms-1" style="font-size: 0.65rem;"><?= e($role) ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="font-monospace text-info small fw-semibold"><?= e($row['action'] ?? '-') ?></span>
                            </td>
                            <td class="pe-3 text-white small">
                                <?= e($row['details'] ?? '-') ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require BASE_PATH . '/app/views/layout/footer.php'; ?>