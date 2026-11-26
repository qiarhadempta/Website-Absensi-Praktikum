<?php

class User
{
    public static function findByUsername(string $username): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username LIMIT 1');
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        return $user ?: null;
    }

    public static function findById(int $id): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();

        return $user ?: null;
    }

    public static function updatePassword(int $id, string $newPasswordHash): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'UPDATE users SET password_hash = :hash, must_change_password = 0 WHERE id = :id'
        );
        $stmt->execute([
            'hash' => $newPasswordHash,
            'id'   => $id,
        ]);
    }
}