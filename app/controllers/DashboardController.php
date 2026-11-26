<?php

class DashboardController
{
    public function index(): void
    {
        if (empty($_SESSION['user'])) {
            redirect('/login');
        }

        $user = $_SESSION['user'];

        // Paksa ganti kata sandi jika belum mengganti sandi awal
        if ((int) ($user['must_change_password'] ?? 0) === 1) {
            redirect('/password/change');
        }

        $role = $user['role'] ?? '';

        if ($role === 'admin') {
            $this->showAdminDashboard($user);
        } elseif ($role === 'aslab') {
            $this->showAslabDashboard($user);
        } elseif ($role === 'mahasiswa') {
            $this->showMahasiswaDashboard($user);
        } else {
            http_response_code(403);
            die('Peran pengguna tidak dikenali.');
        }
    }

    private function getActiveCourse(PDO $pdo): ?array
    {
        $stmt = $pdo->query('SELECT id, code, name, term FROM courses WHERE is_active = 1 LIMIT 1');
        return $stmt->fetch() ?: null;
    }

    private function showAdminDashboard(array $user): void
    {
        $pdo    = Database::getConnection();
        $course = $this->getActiveCourse($pdo);

        $totalAslab         = (int) $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'aslab' AND is_active = 1")->fetchColumn();
        $totalMahasiswa     = (int) $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'mahasiswa' AND is_active = 1")->fetchColumn();
        $mustChangePassword = (int) $pdo->query("SELECT COUNT(*) FROM users WHERE must_change_password = 1 AND is_active = 1")->fetchColumn();

        $stats = [
            'total_aslab'     => $totalAslab,
            'total_mahasiswa' => $totalMahasiswa,
            'meetings_done'   => 0, // Diisi saat tabel sessions ditambahkan
            'open_sessions'   => 0, // Diisi saat tabel sessions ditambahkan
        ];

        $attention = [
            'must_change_password' => $mustChangePassword,
            'stale_sessions'       => 0,
        ];

        $openSessions = [];
        $recentLogs   = [];

        require BASE_PATH . '/app/views/dashboard/admin.php';
    }

    private function showAslabDashboard(array $user): void
    {
        $pdo    = Database::getConnection();
        $course = $this->getActiveCourse($pdo);

        $totalMahasiswa = 0;
        if ($course) {
            $stmt = $pdo->prepare(
                "SELECT COUNT(*) FROM enrollments e
                 JOIN users u ON u.id = e.user_id
                 WHERE e.course_id = :cid AND u.role = 'mahasiswa' AND u.is_active = 1"
            );
            $stmt->execute(['cid' => $course['id']]);
            $totalMahasiswa = (int) $stmt->fetchColumn();
        }

        $stats = [
            'total_mahasiswa' => $totalMahasiswa,
            'meetings_done'   => 0,
            'open_sessions'   => 0,
        ];

        $openSessions   = [];
        $recentMeetings = [];

        require BASE_PATH . '/app/views/dashboard/aslab.php';
    }

    private function showMahasiswaDashboard(array $user): void
    {
        $pdo    = Database::getConnection();
        $course = null;

        // Pastikan praktikan terdaftar pada praktikum aktif
        $stmt = $pdo->prepare(
            "SELECT c.id, c.code, c.name, c.term
             FROM courses c
             JOIN enrollments e ON e.course_id = c.id
             WHERE c.is_active = 1 AND e.user_id = :uid
             LIMIT 1"
        );
        $stmt->execute(['uid' => (int) $user['id']]);
        $course = $stmt->fetch() ?: null;

        $summary = [
            'hadir'        => 0,
            'izin'         => 0,
            'sakit'        => 0,
            'alpa'         => 0,
            'total_points' => 0,
            'avg_grade'    => 0,
        ];

        $openSessions = [];
        $history      = [];

        require BASE_PATH . '/app/views/dashboard/mahasiswa.php';
    }
}