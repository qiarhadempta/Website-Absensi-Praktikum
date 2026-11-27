<?php

class UserController
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

        $roleFilter = $_GET['role'] ?? null;
        $users      = User::all($roleFilter);
        $success    = $_SESSION['flash_success'] ?? null;
        $error      = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        require BASE_PATH . '/app/views/admin/users/index.php';
    }

    public function form(): void
    {
        $this->requireAdmin();

        $id   = (int) ($_GET['id'] ?? 0);
        $user = $id > 0 ? User::findById($id) : null;

        $errors = $_SESSION['flash_errors'] ?? [];
        $old    = $_SESSION['flash_old'] ?? [];
        unset($_SESSION['flash_errors'], $_SESSION['flash_old']);

        require BASE_PATH . '/app/views/admin/users/form.php';
    }

    public function save(): void
    {
        $this->requireAdmin();
        verify_csrf();

        $id       = (int) ($_POST['id'] ?? 0);
        $username = trim($_POST['username'] ?? '');
        $name     = trim($_POST['name'] ?? '');
        $role     = $_POST['role'] ?? 'mahasiswa';
        $password = $_POST['password'] ?? '';
        $isActive = (int) ($_POST['is_active'] ?? 1) === 1 ? 1 : 0;

        $errors = [];
        if ($username === '' || strlen($username) > 30) {
            $errors[] = 'Username / NIM wajib diisi (maksimal 30 karakter).';
        }
        if ($name === '') {
            $errors[] = 'Nama lengkap wajib diisi.';
        }
        if (!in_array($role, ['admin', 'aslab', 'mahasiswa'], true)) {
            $errors[] = 'Role tidak valid.';
        }
        if (User::usernameExists($username, $id > 0 ? $id : null)) {
            $errors[] = 'Username / NIM sudah terdaftar pada akun lain.';
        }
        if ($id === 0 && strlen($password) < 8) {
            $errors[] = 'Kata sandi awal minimal 8 karakter.';
        }

        if (!empty($errors)) {
            $_SESSION['flash_errors'] = $errors;
            $_SESSION['flash_old']    = [
                'username'  => $username,
                'name'      => $name,
                'role'      => $role,
                'is_active' => $isActive,
            ];
            redirect('/admin/users/form' . ($id > 0 ? '?id=' . $id : ''));
        }

        if ($id > 0) {
            User::update($id, [
                'username'  => $username,
                'name'      => $name,
                'role'      => $role,
                'is_active' => $isActive,
            ]);
            Course::syncUserToActiveCourse($id, $role);
            $_SESSION['flash_success'] = "Data akun {$username} berhasil diperbarui.";
        } else {
            $newId = User::create([
                'username'      => $username,
                'name'          => $name,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'role'          => $role,
                'is_active'     => $isActive,
            ]);
            Course::syncUserToActiveCourse($newId, $role);
            $_SESSION['flash_success'] = "Akun baru {$username} berhasil ditambahkan.";
        }

        redirect('/admin/users');
    }

    public function resetPassword(): void
    {
        $this->requireAdmin();
        verify_csrf();

        $id   = (int) ($_POST['id'] ?? 0);
        $user = $id > 0 ? User::findById($id) : null;

        if (!$user || $id === (int) $_SESSION['user']['id']) {
            $_SESSION['flash_error'] = 'Akun tidak valid untuk direset.';
            redirect('/admin/users');
        }

        // Sandi bawaan saat reset diset sama dengan username-nya dan wajib diganti saat login
        $defaultHash = password_hash($user['username'], PASSWORD_DEFAULT);
        User::resetPassword($id, $defaultHash);

        $_SESSION['flash_success'] = "Kata sandi {$user['username']} direset menjadi '{$user['username']}'. Pengguna wajib menggantinya saat login.";
        redirect('/admin/users');
    }

    public function toggleActive(): void
    {
        $this->requireAdmin();
        verify_csrf();

        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0 || $id === (int) $_SESSION['user']['id']) {
            $_SESSION['flash_error'] = 'Tidak dapat mengubah status akun sendiri.';
            redirect('/admin/users');
        }

        User::toggleActive($id);
        $_SESSION['flash_success'] = 'Status aktif akun berhasil diperbarui.';
        redirect('/admin/users');
    }
}