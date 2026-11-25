<?php

class AuthController
{
    public function showLogin(): void
    {
        if (!empty($_SESSION['user'])) {
            redirect('/dashboard');
        }

        $error = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_error']);

        require BASE_PATH . '/app/views/auth/login.php';
    }

    public function login(): void
    {
        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_error'] = 'Sesi formulir tidak valid. Silakan coba lagi.';
            redirect('/login');
        }

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
            $_SESSION['flash_error'] = 'Akun Anda dinonaktifkan. Hubungi Administrator.';
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

        redirect('/dashboard');
    }

    public function logout(): void
    {
        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            redirect('/dashboard');
        }

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