<?php

class DashboardController
{
    public function index(): void
    {
        if (empty($_SESSION['user'])) {
            redirect('/login');
        }

        $user = $_SESSION['user'];
        $role = $user['role'] ?? '';

        if ($role === 'admin') {
            require BASE_PATH . '/app/views/dashboard/admin.php';
        } elseif ($role === 'aslab') {
            require BASE_PATH . '/app/views/dashboard/aslab.php';
        } elseif ($role === 'mahasiswa') {
            require BASE_PATH . '/app/views/dashboard/mahasiswa.php';
        } else {
            http_response_code(403);
            die('Peran pengguna tidak dikenali.');
        }
    }
}