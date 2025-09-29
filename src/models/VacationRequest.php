<?php

namespace App\Models;

require_once __DIR__ . '/../../config/bootstrap.php';

class VacationRequest
{
    public static function all()
    {
        $pdo = get_db();
        $stmt = $pdo->query("SELECT vr.*, u.username FROM vacation_requests vr JOIN users u ON vr.user_id = u.id ORDER BY vr.start_date DESC");
        return $stmt->fetchAll();
    }

    public static function byUser(int $user_id)
    {
        $pdo = get_db();
        $stmt = $pdo->prepare("
            SELECT *
            FROM vacation_requests
            WHERE user_id = ?
            ORDER BY start_date ASC
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    public static function create(int $user_id, string $start_date, string $end_date, string $reason)
    {
        $pdo = get_db();
        $stmt = $pdo->prepare("
            INSERT INTO vacation_requests (user_id, start_date, end_date, reason, status)
            VALUES (?, ?, ?, ?, 'pending')
        ");
        $stmt->execute([$user_id, $start_date, $end_date, $reason]);
    }

    public static function find(int $request_id)
    {
        $pdo = get_db();
        $stmt = $pdo->prepare("SELECT * FROM vacation_requests WHERE id = ?");
        $stmt->execute([$request_id]);
        return $stmt->fetch(); // returns associative array or false if not found
    }

    public static function delete(int $request_id)
    {
        $pdo = get_db();
        $stmt = $pdo->prepare("DELETE FROM vacation_requests WHERE id = ?");
        $stmt->execute([$request_id]);
    }

    public static function updateStatus(int $request_id, string $status)
    {
        $pdo = get_db();
        $stmt = $pdo->prepare("UPDATE vacation_requests SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        $stmt->execute([$status, $request_id]);
    }
}
