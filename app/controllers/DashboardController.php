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
            $this->showAdminDashboard($user);
        } elseif ($role === 'aslab') {
            require BASE_PATH . '/app/views/dashboard/aslab.php';
        } elseif ($role === 'mahasiswa') {
            require BASE_PATH . '/app/views/dashboard/mahasiswa.php';
        } else {
            http_response_code(403);
            die('Peran pengguna tidak dikenali.');
        }
    }

    private function showAdminDashboard(array $user): void
    {
        $pdo = Database::getConnection();

        // 1. Ambil praktikum Jarkom yang sedang aktif
        $stmtCourse = $pdo->query('SELECT id, code, name, term FROM courses WHERE is_active = 1 LIMIT 1');
        $course = $stmtCourse->fetch() ?: null;

        // 2. Hitung jumlah Aslab & Praktikan aktif
        $totalAslab = (int) $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'aslab' AND is_active = 1")->fetchColumn();
        $totalMahasiswa = (int) $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'mahasiswa' AND is_active = 1")->fetchColumn();

        // 3. Hitung akun aktif yang belum mengganti kata sandi awal
        $mustChangePassword = (int) $pdo->query("SELECT COUNT(*) FROM users WHERE must_change_password = 1 AND is_active = 1")->fetchColumn();

        $stats = [
            'total_aslab' => $totalAslab,
            'total_mahasiswa' => $totalMahasiswa,
            'meetings_done' => 0, // Diisi setelah tabel sessions dibuat
            'open_sessions' => 0, // Diisi setelah tabel sessions dibuat
        ];

        $attention = [
            'must_change_password' => $mustChangePassword,
            'stale_sessions' => 0, // Diisi setelah tabel sessions dibuat
        ];

        $openSessions = [];
        $recentLogs = [];

        require BASE_PATH . '/app/views/dashboard/admin.php';
    }
}