<?php
// Konfigurasi aplikasi. Jangan menulis rahasia asli di file ini (file ini masuk git).
// Urutan sumber nilai:
//   1. environment variable (dipakai di server, mis. Coolify)
//   2. config/config.local.php (dipakai di hosting tanpa env var; tidak masuk git)
//   3. default untuk pengembangan lokal

$local = is_file(__DIR__ . '/config.local.php') ? require __DIR__ . '/config.local.php' : [];

return [
    'app_env' => getenv('APP_ENV') ?: ($local['app_env'] ?? 'development'),
    'db_host' => getenv('DB_HOST') ?: ($local['db_host'] ?? 'localhost'),
    'db_name' => getenv('DB_NAME') ?: ($local['db_name'] ?? 'presensi'),
    'db_user' => getenv('DB_USER') ?: ($local['db_user'] ?? 'root'),
    'db_pass' => getenv('DB_PASS') !== false ? getenv('DB_PASS') : ($local['db_pass'] ?? ''),
];
