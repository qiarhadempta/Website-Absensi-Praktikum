<?php
// Dashboard praktikan: status sesi terbuka, ringkasan presensi, poin, dan riwayat pertemuan Jarkom.
// URL: GET /dashboard (DashboardController@index, role mahasiswa)
$pageTitle = 'Dashboard Praktikan - Presensi Praktikum Jarkom';
require BASE_PATH . '/app/views/layout/header.php';

$user = $user ?? ($_SESSION['user'] ?? []);
$course = $course ?? null;
$summary = array_merge([
    'hadir'        => 0,
    'izin'         => 0,
    'sakit'        => 0,
    'alpa'         => 0,
    'total_points' => 0,
    'avg_grade'    => 0,
], $summary ?? []);
$openSessions = $openSessions ?? [];
$history = $history ?? [];

$totalMeetings = (int) $summary['hadir'] + (int) $summary['izin'] + (int) $summary['sakit'] + (int) $summary['alpa'];
$attendancePct = $totalMeetings > 0 ? round(((int) $summary['hadir'] / $totalMeetings) * 100) : 0;
?>

<!-- 1. Header & Pintasan -->
<table class="table table-borderless align-middle mb-4">
    <tr>
        <td class="p-0">
            <h3 class="mb-1 text-white">Dashboard Praktikan</h3>
            <span class="text-white-50 small">
                <?= "Selamat datang, " . e($user['name'] ?? 'Praktikan') ?> (<?= e($user['username'] ?? '-') ?>)
                <?= $course ? ' &middot; ' . e($course['name']) . ' (' . e($course['term']) . ')' : '' ?>
            </span>
        </td>
        <td class="p-0 text-end">
            <a href="/mahasiswa/grades" class="btn btn-outline-light btn-sm me-2">Detail Nilai</a>
            <a href="/mahasiswa/recap" class="btn btn-outline-light btn-sm">Rekap Lengkap</a>
        </td>
    </tr>
</table>

<!-- 2. Banner Sesi Presensi Terbuka (Muncul jika ada sesi aktif) -->
<?php if (!empty($openSessions)): ?>
    <?php $activeSes = $openSessions[0]; ?>
    <div class="card border-info mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <span class="badge bg-info text-uppercase mb-1">Sesi Presensi Dibuka</span>
                <h5 class="mb-1 text-white">Pertemuan Ke-<?= (int) ($activeSes['meeting_number'] ?? 1) ?> sedang berlangsung</h5>
                <span class="text-white-50 small">Masukkan kode token yang ditampilkan Aslab di layar lab sebelum sesi ditutup.</span>
            </div>
            <div>
                <a href="/mahasiswa/attendance" class="btn btn-primary fw-semibold px-4">Isi Presensi &rarr;</a>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- 3. Kartu Statistik Kehadiran & Poin -->
<table class="table table-borderless mb-4" style="table-layout: fixed;">
    <tr>
        <td class="ps-0 pe-2">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-white-50 small text-uppercase">Hadir</div>
                    <div class="fs-2 fw-bold text-white my-1"><?= (int) $summary['hadir'] ?> <span class="fs-6 fw-normal text-info">(<?= $attendancePct ?>%)</span></div>
                    <span class="small text-white-50">Dari <?= $totalMeetings ?> pertemuan</span>
                </div>
            </div>
        </td>
        <td class="px-2">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-white-50 small text-uppercase">Izin / Sakit</div>
                    <div class="fs-2 fw-bold text-white my-1"><?= (int) $summary['izin'] + (int) $summary['sakit'] ?></div>
                    <span class="small text-white-50"><?= (int) $summary['izin'] ?> Izin &bull; <?= (int) $summary['sakit'] ?> Sakit</span>
                </div>
            </div>
        </td>
        <td class="px-2">
            <div class="card h-100 <?= (int) $summary['alpa'] > 0 ? 'border-warning' : '' ?>">
                <div class="card-body">
                    <div class="text-white-50 small text-uppercase">Alpa</div>
                    <div class="fs-2 fw-bold text-white my-1"><?= (int) $summary['alpa'] ?></div>
                    <span class="small text-white-50">Tanpa keterangan</span>
                </div>
            </div>
        </td>
        <td class="pe-0 ps-2">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-white-50 small text-uppercase">Poin Keaktifan</div>
                    <div class="fs-2 fw-bold text-info my-1">+<?= (int) $summary['total_points'] ?></div>
                    <span class="small text-white-50">Rata-rata nilai: <?= number_format((float) $summary['avg_grade'], 1) ?></span>
                </div>
            </div>
        </td>
    </tr>
</table>

<!-- 4. Riwayat Pertemuan Praktikum Jarkom -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold text-white">Riwayat Pertemuan Praktikum</span>
        <a href="/mahasiswa/attendance" class="small text-info text-decoration-none">Halaman Presensi &rarr;</a>
    </div>
    <div class="card-body p-0">
        <?php if (empty($history)): ?>
            <p class="text-white-50 small p-3 mb-0">Belum ada riwayat pertemuan praktikum yang tercatat.</p>
        <?php else: ?>
            <table class="table table-sm table-hover mb-0 align-middle">
                <thead>
                    <tr class="small text-white-50">
                        <th class="ps-3" style="width: 14%;">Pertemuan</th>
                        <th>Tanggal</th>
                        <th class="text-center" style="width: 16%;">Status</th>
                        <th class="text-center" style="width: 14%;">Nilai</th>
                        <th class="pe-3 text-end" style="width: 14%;">Poin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($history as $row): ?>
                        <?php
                        $status = strtolower($row['status'] ?? 'alpa');
                        $badgeClass = match ($status) {
                            'hadir' => 'bg-success',
                            'izin', 'sakit' => 'bg-warning text-dark',
                            default => 'bg-danger',
                        };
                        ?>
                        <tr>
                            <td class="ps-3 text-white fw-semibold">Ke-<?= (int) $row['meeting_number'] ?></td>
                            <td class="text-white-50"><?= e($row['session_date'] ?? '-') ?></td>
                            <td class="text-center">
                                <span class="badge <?= $badgeClass ?> text-uppercase"><?= e($status) ?></span>
                            </td>
                            <td class="text-center text-white"><?= $row['grade'] !== null ? e((string) $row['grade']) : '-' ?></td>
                            <td class="pe-3 text-end text-info fw-semibold">
                                <?= (int) ($row['points'] ?? 0) > 0 ? '+' . (int) $row['points'] : '0' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require BASE_PATH . '/app/views/layout/footer.php'; ?>