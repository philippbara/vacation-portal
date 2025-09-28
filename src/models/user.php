<?php
namespace App\Models;

require_once __DIR__ . '/../../config/bootstrap.php';

class User
{
    public static function all(): array
    {
        $pdo = get_db();
        $stmt = $pdo->query("SELECT id, name, email, role, created_at FROM users");
        return $stmt->fetchAll();
    }

    public static function findByEmail(string $email): ?array
    {
        $pdo = get_db();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }
}
