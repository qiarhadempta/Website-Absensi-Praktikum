<?php

class Course
{
    public static function getActive(): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query('SELECT * FROM courses WHERE is_active = 1 LIMIT 1');
        return $stmt->fetch() ?: null;
    }

    public static function saveActive(?int $id, string $code, string $name, string $term): int
    {
        $pdo = Database::getConnection();
        if ($id !== null && $id > 0) {
            $stmt = $pdo->prepare('UPDATE courses SET code = :code, name = :name, term = :term WHERE id = :id');
            $stmt->execute([
                'code' => $code,
                'name' => $name,
                'term' => $term,
                'id'   => $id,
            ]);
            return $id;
        }

        $stmt = $pdo->prepare('INSERT INTO courses (code, name, term, is_active) VALUES (:code, :name, :term, 1)');
        $stmt->execute([
            'code' => $code,
            'name' => $name,
            'term' => $term,
        ]);
        return (int) $pdo->lastInsertId();
    }

    public static function syncUserToActiveCourse(int $userId, string $role): void
    {
        $course = self::getActive();
        if (!$course) {
            return;
        }

        $pdo = Database::getConnection();
        $cid = (int) $course['id'];

        if ($role === 'aslab') {
            $stmt = $pdo->prepare('INSERT IGNORE INTO course_aslab (course_id, user_id) VALUES (:cid, :uid)');
            $stmt->execute(['cid' => $cid, 'uid' => $userId]);
        } elseif ($role === 'mahasiswa') {
            $stmt = $pdo->prepare('INSERT IGNORE INTO enrollments (course_id, user_id) VALUES (:cid, :uid)');
            $stmt->execute(['cid' => $cid, 'uid' => $userId]);
        }
    }

    public static function syncAllActiveUsers(): void
    {
        $course = self::getActive();
        if (!$course) {
            return;
        }

        $pdo = Database::getConnection();
        $cid = (int) $course['id'];

        $stmtAslab = $pdo->prepare(
            "INSERT IGNORE INTO course_aslab (course_id, user_id)
             SELECT :cid, id FROM users WHERE role = 'aslab' AND is_active = 1"
        );
        $stmtAslab->execute(['cid' => $cid]);

        $stmtMhs = $pdo->prepare(
            "INSERT IGNORE INTO enrollments (course_id, user_id)
             SELECT :cid, id FROM users WHERE role = 'mahasiswa' AND is_active = 1"
        );
        $stmtMhs->execute(['cid' => $cid]);
    }

    public static function countParticipants(int $courseId): array
    {
        $pdo = Database::getConnection();

        $stmtAslab = $pdo->prepare(
            "SELECT COUNT(*) FROM course_aslab ca
             JOIN users u ON u.id = ca.user_id
             WHERE ca.course_id = :cid AND u.is_active = 1"
        );
        $stmtAslab->execute(['cid' => $courseId]);

        $stmtMhs = $pdo->prepare(
            "SELECT COUNT(*) FROM enrollments e
             JOIN users u ON u.id = e.user_id
             WHERE e.course_id = :cid AND u.is_active = 1"
        );
        $stmtMhs->execute(['cid' => $courseId]);

        return [
            'aslab'   => (int) $stmtAslab->fetchColumn(),
            'student' => (int) $stmtMhs->fetchColumn(),
        ];
    }
}