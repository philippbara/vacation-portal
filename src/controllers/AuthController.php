<?php
namespace App\Controllers;

require_once __DIR__ . '/../../config/bootstrap.php';

class AuthController
{
    public static function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pdo = get_db();
            $username = $_POST['username'];
            $password = $_POST['password'];

            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'role' => $user['role']
                ];

                // Redirect to originally requested page if exists
                if (!empty($_SESSION['redirect_after_login'])) {
                    $redirect = $_SESSION['redirect_after_login'];
                    unset($_SESSION['redirect_after_login']);
                    header("Location: " . $redirect);
                } else {
                    header('Location: /dashboard');
                }
                exit();
            } else {
                $_SESSION['flash_message'] = "Invalid username or password.";
                header('Location: /login');
                exit();
            }
        }

        require __DIR__ . '/../views/login.php';
    }

    public static function logout()
    {
        // Clear all session data
        $_SESSION = [];
        session_destroy();

        // Redirect with message
        session_start(); // restart to set flash after destroy
        $_SESSION['flash_message'] = "👋 You have been logged out.";
        header('Location: /login');
        exit;
    }

    public static function dashboard()
    {
        $user = $_SESSION['user'];
        $role = $user['role'];
        $username = $user['username'];
        $user_id  = $user['id'];

        require_once __DIR__ . '/../models/User.php';
        require_once __DIR__ . '/../models/VacationRequest.php';

        if ($role === 'manager' || $role === 'admin') {
            // Fetch employees with vacation counts
            $users = \App\Models\User::allEmployeesWithVacationCount();
            require __DIR__ . '/../views/dashboard_users.php';
        } else {
            // Employee: redirect directly to their own vacation requests
            header("Location: /users/{$user_id}");
            exit;
        }
    }
}
