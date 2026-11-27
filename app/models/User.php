<?php

class User
{
    public static function findByUsername(string $username): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username LIMIT 1');
        $stmt->execute(['username' => $username]);
        return $stmt->fetch() ?: null;
    }

    public static function findById(int $id): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public static function all(?string $roleFilter = null): array
    {
        $pdo = Database::getConnection();
        if ($roleFilter !== null && in_array($roleFilter, ['admin', 'aslab', 'mahasiswa'], true)) {
            $stmt = $pdo->prepare('SELECT * FROM users WHERE role = :role ORDER BY role ASC, username ASC');
            $stmt->execute(['role' => $roleFilter]);
            return $stmt->fetchAll();
        }

        return $pdo->query('SELECT * FROM users ORDER BY role ASC, username ASC')->fetchAll();
    }

    public static function usernameExists(string $username, ?int $excludeId = null): bool
    {
        $pdo = Database::getConnection();
        if ($excludeId !== null && $excludeId > 0) {
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = :username AND id != :id');
            $stmt->execute(['username' => $username, 'id' => $excludeId]);
        } else {
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = :username');
            $stmt->execute(['username' => $username]);
        }
        return (int) $stmt->fetchColumn() > 0;
    }

    public static function create(array $data): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'INSERT INTO users (username, name, password_hash, role, must_change_password, is_active)
             VALUES (:username, :name, :hash, :role, 1, :is_active)'
        );
        $stmt->execute([
            'username'  => $data['username'],
            'name'      => $data['name'],
            'hash'      => $data['password_hash'],
            'role'      => $data['role'],
            'is_active' => $data['is_active'],
        ]);
        return (int) $pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'UPDATE users SET username = :username, name = :name, role = :role, is_active = :is_active WHERE id = :id'
        );
        $stmt->execute([
            'username'  => $data['username'],
            'name'      => $data['name'],
            'role'      => $data['role'],
            'is_active' => $data['is_active'],
            'id'        => $id,
        ]);
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

    public static function resetPassword(int $id, string $defaultPasswordHash): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'UPDATE users SET password_hash = :hash, must_change_password = 1 WHERE id = :id'
        );
        $stmt->execute([
            'hash' => $defaultPasswordHash,
            'id'   => $id,
        ]);
    }

    public static function toggleActive(int $id): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('UPDATE users SET is_active = 1 - is_active WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}