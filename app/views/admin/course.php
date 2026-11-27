<?php
// Pengaturan batch Praktikum Jaringan Komputer aktif.
// URL: GET /admin/course, simpan ke POST /admin/course/save (CourseController@index)
$pageTitle = 'Pengaturan Praktikum - Presensi Praktikum Jarkom';
require BASE_PATH . '/app/views/layout/header.php';

$course        = $course ?? null;
$aslabCount    = (int) ($aslabCount ?? 0);
$studentCount  = (int) ($studentCount ?? 0);
$success       = $success ?? null;
$errors        = $errors ?? [];

$valCode = $course['code'] ?? 'JARKOM';
$valName = $course['name'] ?? 'Praktikum Jaringan Komputer';
$valTerm = $course['term'] ?? '2026/2027 Ganjil';
?>

<!-- 1. Header -->
<table class="table table-borderless align-middle mb-4">
    <tr>
        <td class="p-0">
            <h3 class="mb-1 text-white">Pengaturan Praktikum</h3>
            <span class="text-white-50 small">Konfigurasi periode aktif Praktikum Jaringan Komputer dan sinkronisasi peserta.</span>
        </td>
        <td class="p-0 text-end">
            <a href="/dashboard" class="btn btn-outline-light btn-sm">&larr; Dashboard</a>
        </td>
    </tr>
</table>

<?php if (!empty($success)): ?>
    <div class="alert alert-success small mb-4"><?= e($success) ?></div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger small mb-4">
        <ul class="mb-0 ps-3">
            <?php foreach ($errors as $err): ?>
                <li><?= e($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- 2. Grid 2 Kolom: Form Pengaturan & Status Kepesertaan -->
<table class="table table-borderless" style="table-layout: fixed;">
    <tr>
        <!-- Kiri: Identitas Praktikum -->
        <td class="ps-0 pe-3 align-top" style="width: 60%;">
            <div class="card">
                <div class="card-header fw-semibold text-white">
                    Identitas Praktikum Aktif
                </div>
                <div class="card-body">
                    <form method="post" action="/admin/course/save" class="m-0 p-0 bg-transparent">
                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                        <?php if (!empty($course['id'])): ?>
                            <input type="hidden" name="id" value="<?= (int) $course['id'] ?>">
                        <?php endif; ?>

                        <table class="table table-borderless align-middle mb-0">
                            <tr>
                                <td class="ps-0" style="width: 32%;">
                                    <label for="code" class="form-label text-white mb-0">Kode Praktikum</label>
                                </td>
                                <td class="pe-0">
                                    <input type="text" id="code" name="code" class="form-control my-0"
                                           value="<?= e($valCode) ?>" required>
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-0">
                                    <label for="name" class="form-label text-white mb-0">Nama Praktikum</label>
                                </td>
                                <td class="pe-0">
                                    <input type="text" id="name" name="name" class="form-control my-0"
                                           value="<?= e($valName) ?>" required>
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-0">
                                    <label for="term" class="form-label text-white mb-0">Semester / Periode</label>
                                </td>
                                <td class="pe-0">
                                    <input type="text" id="term" name="term" class="form-control my-0"
                                           value="<?= e($valTerm) ?>" placeholder="Contoh: 2026/2027 Ganjil" required>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="px-0 pt-3 text-end">
                                    <button type="submit" class="btn btn-primary px-4">Simpan Pengaturan</button>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </td>

        <!-- Kanan: Status Peserta & Sinkronisasi -->
        <td class="pe-0 ps-3 align-top" style="width: 40%;">
            <div class="card">
                <div class="card-header fw-semibold text-white">
                    Peserta Terhubung
                </div>
                <div class="card-body">
                    <p class="text-white-50 small mb-3">
                        Seluruh Aslab dan Praktikan berstatus aktif otomatis dihubungkan ke praktikum ini saat akun dibuat.
                    </p>
                    <table class="table table-sm mb-3">
                        <tr>
                            <td class="text-white">Asisten Lab (Aslab)</td>
                            <td class="text-end fw-bold text-info"><?= $aslabCount ?> akun</td>
                        </tr>
                        <tr>
                            <td class="text-white">Praktikan (Mahasiswa)</td>
                            <td class="text-end fw-bold text-info"><?= $studentCount ?> akun</td>
                        </tr>
                    </table>

                    <form method="post" action="/admin/course/sync" class="m-0 p-0 bg-transparent">
                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                        <button type="submit" class="btn btn-outline-info btn-sm w-100">
                            Sinkronkan Semua Akun Aktif
                        </button>
                    </form>
                </div>
            </div>
        </td>
    </tr>
</table>

<?php require BASE_PATH . '/app/views/layout/footer.php'; ?>