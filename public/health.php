<?php
// Cek cepat apakah aplikasi dan database terhubung. Tidak menampilkan detail error.
// Hapus file ini atau batasi aksesnya sebelum versi final.
$c = require __DIR__ . '/../config/config.php';
header('Content-Type: text/plain; charset=utf-8');
try {
    $pdo = new PDO(
        "mysql:host={$c['db_host']};dbname={$c['db_name']};charset=utf8mb4",
        $c['db_user'],
        $c['db_pass'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $pdo->query('SELECT 1');
    echo 'OK: aplikasi dan database terhubung';
} catch (Throwable $e) {
    http_response_code(500);
    echo 'GAGAL: database tidak terhubung';
}
