<?php

require_once __DIR__ . '/../app/helpers/Database.php';

header('Content-Type: text/plain; charset=utf-8');

try {
    $pdo = Database::getConnection();
    $pdo->query('SELECT 1');

    echo 'OK: aplikasi dan database terhubung';   
} catch(Throwable $e){
    http_response_code(500);
    echo 'GAGAL: database tidak terhubung';
}