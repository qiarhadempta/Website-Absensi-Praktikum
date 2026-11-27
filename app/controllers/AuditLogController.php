<?php

class AuditLogController
{
    public function index(): void
    {
        if (empty($_SESSION['user'])) {
            redirect('/login');
        }
        if (($_SESSION['user']['role'] ?? '') !== 'admin') {
            http_response_code(403);
            die('Akses ditolak. Halaman ini khusus Dosen Pengampu.');
        }

        $logs = []; // Akan diisi query setelah tabel audit_logs dibuat di Tahap 3
        require BASE_PATH . '/app/views/admin/audit_logs.php';
    }
}