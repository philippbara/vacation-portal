<?php

namespace App\Models;

require_once __DIR__ . '/../../config/bootstrap.php';

class User
{
    public static function allEmployeesWithVacationCount()
    {
        $pdo = get_db();
        // Sort by employer who has the closest vacation request to today
        $stmt = $pdo->query("
            SELECT 
                u.id, 
                u.username, 
                u.employee_code,
                u.first_name, 
                u.last_name, 
                u.email,
                COUNT(vr.id) AS total_vacations,
                COUNT(CASE WHEN vr.status = 'pending' THEN 1 END) AS pending_requests,
                MIN(CASE WHEN vr.start_date >= CURRENT_DATE THEN vr.start_date END) AS next_vacation
            FROM users u
            LEFT JOIN vacation_requests vr ON u.id = vr.user_id
            WHERE u.role = 'employee'
            GROUP BY u.id
            ORDER BY next_vacation ASC NULLS LAST;
        ");
        return $stmt->fetchAll();
    }

    public static function find($id)
    {
        $pdo = get_db();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function delete(int $id)
    {
        $pdo = get_db();
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
    }
}
