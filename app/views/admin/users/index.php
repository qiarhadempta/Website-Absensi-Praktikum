<?php
// Daftar akun semua role.
// URL: GET /admin/users (UserController@index)
$pageTitle = 'Kelola Akun - Presensi Praktikum Jarkom';
require BASE_PATH . '/app/views/layout/header.php';

$users      = $users ?? [];
$roleFilter = $roleFilter ?? ($_GET['role'] ?? '');
$success    = $success ?? null;
$error      = $error ?? null;
$currentId  = (int) ($_SESSION['user']['id'] ?? 0);
?>

<!-- 1. Header -->
<table class="table table-borderless align-middle mb-4">
    <tr>
        <td class="p-0">
            <h3 class="mb-1 text-white">Kelola Akun Pengguna</h3>
            <span class="text-white-50 small">Daftar akun Dosen Pengampu, Asisten Laboratorium, dan Praktikan Jarkom.</span>
        </td>
        <td class="p-0 text-end">
            <a href="/dashboard" class="btn btn-outline-light btn-sm me-2">&larr; Dashboard</a>
            <a href="/admin/users/form" class="btn btn-primary btn-sm">+ Tambah Akun</a>
        </td>
    </tr>
</table>

<?php if (!empty($success)): ?>
    <div class="alert alert-success small mb-4"><?= e($success) ?></div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger small mb-4"><?= e($error) ?></div>
<?php endif; ?>

<!-- 2. Filter Peran -->
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="get" action="/admin/users" class="m-0 p-0 bg-transparent">
            <table class="table table-borderless align-middle mb-0">
                <tr>
                    <td class="ps-0" style="width: 120px;">
                        <label for="role" class="form-label text-white small mb-0">Filter Role:</label>
                    </td>
                    <td style="width: 240px;">
                        <select name="role" id="role" class="form-select form-select-sm my-0">
                            <option value="">Semua Role</option>
                            <option value="admin" <?= $roleFilter === 'admin' ? 'selected' : '' ?>>Admin (Dosen)</option>
                            <option value="aslab" <?= $roleFilter === 'aslab' ? 'selected' : '' ?>>Aslab</option>
                            <option value="mahasiswa" <?= $roleFilter === 'mahasiswa' ? 'selected' : '' ?>>Mahasiswa (Praktikan)</option>
                        </select>
                    </td>
                    <td class="pe-0">
                        <button type="submit" class="btn btn-primary btn-sm me-1">Filter</button>
                        <?php if ($roleFilter !== ''): ?>
                            <a href="/admin/users" class="btn btn-outline-light btn-sm">Reset</a>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<!-- 3. Tabel Daftar Akun -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold text-white">Daftar Akun</span>
        <span class="text-white-50 small">Total: <?= count($users) ?> akun</span>
    </div>
    <div class="card-body p-0">
        <?php if (empty($users)): ?>
            <p class="text-white-50 small p-4 mb-0 text-center">Belum ada data akun pengguna.</p>
        <?php else: ?>
            <table class="table table-sm table-hover mb-0 align-middle">
                <thead>
                    <tr class="small text-white-50">
                        <th class="ps-3" style="width: 18%;">Username / NIM</th>
                        <th>Nama Lengkap</th>
                        <th style="width: 14%;">Role</th>
                        <th class="text-center" style="width: 12%;">Status</th>
                        <th class="pe-3 text-end" style="width: 28%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $row): ?>
                        <?php
                        $id       = (int) $row['id'];
                        $role     = $row['role'] ?? 'mahasiswa';
                        $isActive = (int) ($row['is_active'] ?? 1) === 1;
                        $mustChg  = (int) ($row['must_change_password'] ?? 0) === 1;

                        $roleBadge = match ($role) {
                            'admin' => 'bg-info',
                            'aslab' => 'bg-primary',
                            default => 'bg-secondary',
                        };
                        ?>
                        <tr>
                            <td class="ps-3 font-monospace text-info fw-semibold"><?= e($row['username']) ?></td>
                            <td>
                                <div class="text-white fw-semibold"><?= e($row['name']) ?></div>
                                <?php if ($mustChg): ?>
                                    <span class="text-warning" style="font-size: 0.72rem;">&bull; Belum ganti sandi awal</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?= $roleBadge ?> text-uppercase" style="font-size: 0.7rem;"><?= e($role) ?></span>
                            </td>
                            <td class="text-center">
                                <?php if ($isActive): ?>
                                    <span class="badge bg-success" style="font-size: 0.7rem;">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-danger" style="font-size: 0.7rem;">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="pe-3 text-end">
                                <a href="/admin/users/form?id=<?= $id ?>" class="btn btn-outline-light btn-sm me-1">Edit</a>

                                <?php if ($id !== $currentId): ?>
                                    <form method="post" action="/admin/users/reset-password" class="d-inline m-0 p-0 bg-transparent"
                                          onsubmit="return confirm('Reset sandi <?= e($row['username']) ?> menjadi sandi bawaan (username-nya)?');">
                                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                        <input type="hidden" name="id" value="<?= $id ?>">
                                        <button type="submit" class="btn btn-outline-info btn-sm me-1">Reset Sandi</button>
                                    </form>

                                    <form method="post" action="/admin/users/toggle-active" class="d-inline m-0 p-0 bg-transparent"
                                          onsubmit="return confirm('Ubah status aktif akun <?= e($row['username']) ?>?');">
                                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                        <input type="hidden" name="id" value="<?= $id ?>">
                                        <button type="submit" class="btn btn-sm <?= $isActive ? 'btn-outline-danger' : 'btn-outline-success' ?>">
                                            <?= $isActive ? 'Nonaktifkan' : 'Aktifkan' ?>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require BASE_PATH . '/app/views/layout/footer.php'; ?>