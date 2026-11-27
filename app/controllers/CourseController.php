<?php

class CourseController
{
    private function requireAdmin(): void
    {
        if (empty($_SESSION['user'])) {
            redirect('/login');
        }
        if (($_SESSION['user']['role'] ?? '') !== 'admin') {
            http_response_code(403);
            die('Akses ditolak. Halaman ini khusus Dosen Pengampu.');
        }
    }

    public function index(): void
    {
        $this->requireAdmin();

        $course = Course::getActive();
        $counts = $course ? Course::countParticipants((int) $course['id']) : ['aslab' => 0, 'student' => 0];

        $aslabCount   = $counts['aslab'];
        $studentCount = $counts['student'];
        $success      = $_SESSION['flash_success'] ?? null;
        $errors       = $_SESSION['flash_errors'] ?? [];
        unset($_SESSION['flash_success'], $_SESSION['flash_errors']);

        require BASE_PATH . '/app/views/admin/course.php';
    }

    public function save(): void
    {
        $this->requireAdmin();
        verify_csrf();

        $id   = (int) ($_POST['id'] ?? 0);
        $code = strtoupper(trim($_POST['code'] ?? ''));
        $name = trim($_POST['name'] ?? '');
        $term = trim($_POST['term'] ?? '');

        $errors = [];
        if ($code === '' || $name === '' || $term === '') {
            $errors[] = 'Kode, nama praktikum, dan semester wajib diisi.';
        }

        if (!empty($errors)) {
            $_SESSION['flash_errors'] = $errors;
            redirect('/admin/course');
        }

        Course::saveActive($id > 0 ? $id : null, $code, $name, $term);
        Course::syncAllActiveUsers();

        $_SESSION['flash_success'] = 'Pengaturan praktikum berhasil disimpan.';
        redirect('/admin/course');
    }

    public function sync(): void
    {
        $this->requireAdmin();
        verify_csrf();

        Course::syncAllActiveUsers();
        $_SESSION['flash_success'] = 'Seluruh Aslab dan Praktikan aktif berhasil disinkronkan ke praktikum.';
        redirect('/admin/course');
    }
}