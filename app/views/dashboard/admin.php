<?php
// Ringkasan data untuk admin.
// URL: GET /dashboard (DashboardController@index, role admin)
// Variabel dari controller: $stats (array: jumlah matkul, aslab, praktikan)
define('BASE_PATH', dirname(__DIR__));
$pageTitle = 'Dashboard Admin';
require BASE_PATH . '/app/views/layout/header.php';
?>
<section id="ringkasan">
    <h2>Ringkasan</h2>
    <table class="table">
        <tr><td>Matkul aktif</td><td><!-- [ISI: $stats] --></td></tr>
        <tr><td>Aslab</td><td><!-- [ISI: $stats] --></td></tr>
        <tr><td>Praktikan</td><td><!-- [ISI: $stats] --></td></tr>
    </table>
</section>
<section id="pintasan">
    <h2>Pintasan</h2>
    <!-- [ISI: tautan ke kelola matkul, akun, audit log] -->
</section>

<?php require BASE_PATH . '/app/views/layout/footer.php'; ?>
