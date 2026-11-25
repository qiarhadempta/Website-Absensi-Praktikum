<?php

class AuthController
{
    public function showLogin(): void
    {
        $error = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_error']);

        require BASE_PATH . '/app/views/auth/login.php';
    }
}