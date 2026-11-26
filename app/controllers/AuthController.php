<?php

class AuthController
{
    public function showLogin(): void
    {
        if (!empty($_SESSION['user'])) {
            if ((int) ($_SESSION['user']['must_change_password'] ?? 0) === 1) {
                redirect('/password/change');
            }
            redirect('/dashboard');
        }

        $error = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_error']);

        require BASE_PATH . '/app/views/auth/login.php';
    }

    public function login(): void
    {
        verify_csrf();

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            $_SESSION['flash_error'] = 'Username dan password wajib diisi.';
            redirect('/login');
        }

        $user = User::findByUsername($username);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $_SESSION['flash_error'] = 'Username atau password salah.';
            redirect('/login');
        }

        if ((int) $user['is_active'] !== 1) {
            $_SESSION['flash_error'] = 'Akun Anda dinonaktifkan. Hubungi Dosen Pengampu.';
            redirect('/login');
        }

        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => (int) $user['id'],
            'username' => $user['username'],
            'name' => $user['name'],
            'role' => $user['role'],
            'must_change_password' => (int) $user['must_change_password'],
        ];

        if ((int) $user['must_change_password'] === 1) {
            redirect('/password/change');
        }

        redirect('/dashboard');
    }

    public function showChangePassword(): void
    {
        if (empty($_SESSION['user'])) {
            redirect('/login');
        }

        $forced = (int) ($_SESSION['user']['must_change_password'] ?? 0) === 1;
        $error  = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_error']);

        require BASE_PATH . '/app/views/auth/change_password.php';
    }

    public function changePassword(): void
    {
        if (empty($_SESSION['user'])) {
            redirect('/login');
        }

        verify_csrf();

        $oldPassword     = $_POST['old_password'] ?? '';
        $newPassword     = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($oldPassword === '' || $newPassword === '' || $confirmPassword === '') {
            $_SESSION['flash_error'] = 'Semua kolom kata sandi wajib diisi.';
            redirect('/password/change');
        }

        if (strlen($newPassword) < 8) {
            $_SESSION['flash_error'] = 'Kata sandi baru minimal 8 karakter.';
            redirect('/password/change');
        }

        if ($newPassword !== $confirmPassword) {
            $_SESSION['flash_error'] = 'Konfirmasi kata sandi baru tidak cocok.';
            redirect('/password/change');
        }

        if ($oldPassword === $newPassword) {
            $_SESSION['flash_error'] = 'Kata sandi baru tidak boleh sama dengan kata sandi lama.';
            redirect('/password/change');
        }

        $userId = (int) $_SESSION['user']['id'];
        $user   = User::findById($userId);

        if (!$user || !password_verify($oldPassword, $user['password_hash'])) {
            $_SESSION['flash_error'] = 'Kata sandi lama tidak sesuai.';
            redirect('/password/change');
        }

        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        User::updatePassword($userId, $newHash);

        session_regenerate_id(true);
        $_SESSION['user']['must_change_password'] = 0;

        redirect('/dashboard');
    }

    public function logout(): void
    {
        verify_csrf();

        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();

        redirect('/login');
    }
}