<?php

/**
 * Mencegah serangan XSS saat menampilkan teks ke HTML.
 */
function e(?string $text): string
{
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Membuat atau mengambil CSRF token untuk keamanan form POST.
 */
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Memverifikasi CSRF token dari request POST.
 */
function verify_csrf(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(403);
        die('Akses ditolak: CSRF token tidak valid.');
    }
}

/**
 * Mengarahkan halaman (redirect) ke URL tertentu.
 */
function redirect(string $path): void
{
    header("Location: $path");
    exit;
}