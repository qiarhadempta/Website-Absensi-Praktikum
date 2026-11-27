<?php
// Tambah atau ubah akun. Mode ubah bila ada ?id=.
// URL: GET /admin/users/form[?id=], kirim ke POST /admin/users/save (UserController@form)
$pageTitle = 'Form Akun - Presensi Praktikum Jarkom';
require BASE_PATH . '/app/views/layout/header.php';

$user   = $user ?? null;
$errors = $errors ?? [];
$old    = $old ?? [];

$isEdit = !empty($user['id']);
$valId       = $isEdit ? (int) $user['id'] : 0;
$valUsername = $old['username'] ?? ($user['username'] ?? '');
$valName     = $old['name'] ?? ($user['name'] ?? '');
$valRole     = $old['role'] ?? ($user['role'] ?? 'mahasiswa');
$valActive   = isset($old['is_active']) ? (string) $old['is_active'] : (string) ($user['is_active'] ?? '1');
?>

<section id="form-akun" class="py-2">
    <table class="table table-borderless mx-auto" style="max-width: 600px;">
        <tr>
            <td class="p-0">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 text-white"><?= $isEdit ? 'Ubah Data Akun' : 'Tambah Akun Baru' ?></h4>
                        <a href="/admin/users" class="small text-info text-decoration-none">&larr; Kembali</a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger small">
                                <ul class="mb-0 ps-3">
                                    <?php foreach ($errors as $err): ?>
                                        <li><?= e($err) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="/admin/users/save" class="m-0 p-0 bg-transparent">
                            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                            <?php if ($isEdit): ?>
                                <input type="hidden" name="id" value="<?= $valId ?>">
                            <?php endif; ?>

                            <table class="table table-borderless align-middle mb-0">
                                <tr>
                                    <td class="ps-0" style="width: 35%;">
                                        <label for="username" class="form-label text-white mb-0">Username / NIM</label>
                                    </td>
                                    <td class="pe-0">
                                        <input type="text" id="username" name="username" class="form-control my-0"
                                               value="<?= e($valUsername) ?>" placeholder="Contoh: D1041241067 atau aslab03" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-0">
                                        <label for="name" class="form-label text-white mb-0">Nama Lengkap</label>
                                    </td>
                                    <td class="pe-0">
                                        <input type="text" id="name" name="name" class="form-control my-0"
                                               value="<?= e($valName) ?>" placeholder="Nama lengkap pengguna" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-0">
                                        <label for="role" class="form-label text-white mb-0">Role</label>
                                    </td>
                                    <td class="pe-0">
                                        <select id="role" name="role" class="form-select my-0">
                                            <option value="mahasiswa" <?= $valRole === 'mahasiswa' ? 'selected' : '' ?>>Mahasiswa (Praktikan)</option>
                                            <option value="aslab" <?= $valRole === 'aslab' ? 'selected' : '' ?>>Aslab (Asisten Lab)</option>
                                            <option value="admin" <?= $valRole === 'admin' ? 'selected' : '' ?>>Admin (Dosen Pengampu)</option>
                                        </select>
                                    </td>
                                </tr>
                                <?php if (!$isEdit): ?>
                                    <tr>
                                        <td class="ps-0">
                                            <label for="password" class="form-label text-white mb-0">Kata Sandi Awal</label>
                                        </td>
                                        <td class="pe-0">
                                            <input type="password" id="password" name="password" class="form-control my-0" minlength="8" required>
                                            <div class="text-white-50 mt-1" style="font-size: 0.75rem;">
                                                Minimal 8 karakter. Pengguna wajib menggantinya saat login pertama kali.
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td class="ps-0">
                                        <label for="is_active" class="form-label text-white mb-0">Status Akun</label>
                                    </td>
                                    <td class="pe-0">
                                        <select id="is_active" name="is_active" class="form-select my-0">
                                            <option value="1" <?= $valActive === '1' ? 'selected' : '' ?>>Aktif</option>
                                            <option value="0" <?= $valActive === '0' ? 'selected' : '' ?>>Nonaktif</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="px-0 pt-3 text-end">
                                        <a href="/admin/users" class="btn btn-outline-light me-2">Batal</a>
                                        <button type="submit" class="btn btn-primary px-4">Simpan Akun</button>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</section>

<?php require BASE_PATH . '/app/views/layout/footer.php'; ?>