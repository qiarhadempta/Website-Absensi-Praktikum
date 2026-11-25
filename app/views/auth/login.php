<?php
$pageTitle = 'Login - Sistem Presensi Praktikum';
require BASE_PATH . '/app/views/layout/header.php';
?>

<section id="login-section">
    <!-- Tabel pembungkus agar kotak login berada di tengah layar -->
    <table class="table table-borderless mx-auto" style="max-width: 450px;">
        <tr>
            <td>
                <h2>Masuk Sistem Praktikum</h2>
                <p>Masukkan username dan password Anda</p>
            </td>
        </tr>
        <tr>
            <td>
                <!-- [ISI 2: Kotak Alert Error jika $error tidak kosong (gunakan e($error))] -->
            </td>
        </tr>
        <tr>
            <td>
                <form method="post" action="/login">
                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

                    <!-- Tabel tata letak form input -->
                    <table class="table table-bordered">
                        <tr>
                            <th>Username (NIM / NIP)</th>
                            <td>
                                <!-- [ISI 3: <input type="text" name="username" class="form-control" required autofocus>] -->
                            </td>
                        </tr>
                        <tr>
                            <th>Password</th>
                            <td>
                                <!-- [ISI 4: <input type="password" name="password" class="form-control" required>] -->
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-end">
                                <!-- [ISI 5: <button type="submit" class="btn btn-primary">Masuk</button>] -->
                            </td>
                        </tr>
                    </table>
                </form>
            </td>
        </tr>
    </table>
</section>

<?php require BASE_PATH . '/app/views/layout/footer.php'; ?>