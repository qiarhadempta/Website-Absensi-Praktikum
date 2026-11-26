<?php
// Ganti password. Wajib dilakukan saat login pertama (must_change_password = 1).
// URL: GET /password/change, kirim ke POST /password/change (AuthController@showChangePassword)
$pageTitle = 'Ganti Kata Sandi - Presensi Praktikum Jarkom';
require BASE_PATH . '/app/views/layout/header.php';

$error  = $error ?? null;
$forced = (bool) ($forced ?? false);
?>

<section id="ganti-sandi" class="py-3">
    <table class="table table-borderless mx-auto" style="max-width: 520px;">
        <tr>
            <td class="p-0">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0 text-white">Ganti Kata Sandi</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($forced): ?>
                            <div class="alert alert-warning small">
                                <strong>Wajib Ganti Kata Sandi:</strong> Demi keamanan akun, Anda harus mengganti kata sandi bawaan terlebih dahulu sebelum mengakses sistem praktikum.
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger small"><?= e($error) ?></div>
                        <?php endif; ?>

                        <form method="post" action="/password/change">
                            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

                            <table class="table table-borderless align-middle mb-0">
                                <tr>
                                    <td class="ps-0" style="width: 40%;">
                                        <label for="old_password" class="form-label text-white mb-0">Kata sandi lama</label>
                                    </td>
                                    <td class="pe-0">
                                        <input type="password" id="old_password" name="old_password" class="form-control" required autofocus>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-0">
                                        <label for="new_password" class="form-label text-white mb-0">Kata sandi baru</label>
                                    </td>
                                    <td class="pe-0">
                                        <input type="password" id="new_password" name="new_password" class="form-control" minlength="8" required>
                                        <div class="text-white-50" style="font-size: 0.75rem;">Minimal 8 karakter.</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-0">
                                        <label for="confirm_password" class="form-label text-white mb-0">Ulangi sandi baru</label>
                                    </td>
                                    <td class="pe-0">
                                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" minlength="8" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="px-0 pt-3 text-end">
                                        <?php if (!$forced): ?>
                                            <a href="/dashboard" class="btn btn-outline-light me-2">Batal</a>
                                        <?php endif; ?>
                                        <button type="submit" class="btn btn-primary px-4">Simpan Kata Sandi</button>
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