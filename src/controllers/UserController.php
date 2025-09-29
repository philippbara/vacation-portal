<?php

namespace App\Controllers;

require_once __DIR__ . '/../../config/bootstrap.php';
require_once __DIR__ . '/../models/User.php';

class UserController
{
    public static function createForm()
    {
        require __DIR__ . '/../views/create_user.php';
    }

    public static function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $employee_code = trim($_POST['employee_code']);
            $first_name = trim($_POST['first_name']);
            $last_name = trim($_POST['last_name']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $role = $_POST['role'];

            // Basic empty field validation
            if (!$username || !$password || !$email || !$employee_code) {
                header('Location: /users/create');
                exit;
            }
            // Basic email format validation
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                header('Location: /users/create');
                exit;
            }

            $pdo = get_db();

            // Check if username already exists
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $exists = $stmt->fetchColumn();

            if ($exists) {
                $_SESSION['flash_messages'][] = [
                    'text' => "Username '{$username}' is already taken.",
                    'type' => 'error'
                ];
                header('Location: /users/create');
                exit;
            }

            // Check if email already exists
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetchColumn() > 0) {
                $_SESSION['flash_messages'][] = [
                    'text' => "Email '{$email}' is already taken.",
                    'type' => 'error'
                ];
                header('Location: /users/create');
                exit;
            }

            // Check if employer id already exists
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE employee_code = ?");
            $stmt->execute([$employee_code]);
            if ($stmt->fetchColumn() > 0) {
                $_SESSION['flash_messages'][] = [
                    'text' => "Employer code '{$employee_code}' is already taken.",
                    'type' => 'error'
                ];
                header('Location: /users/create');
                exit;
            }

            if (!preg_match("/^\d{7}$/", $employee_code)) {
                $_SESSION['flash_messages'][] = [
                    'text' => "Employer code '{$employee_code}' must be 7 digits.",
                    'type' => 'error'
                ];
                header('Location: /users/create');
                exit;
            }

            // Save new user
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("
                INSERT INTO users (username, first_name, last_name, email, password_hash, role, employee_code)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$username, $first_name, $last_name, $email, $password_hash, $role, $employee_code]);

            $_SESSION['flash_messages'][] = [
                'text' => "User created successfully.",
                'type' => 'success'
            ];
            header('Location: /dashboard');
            exit;
        }
    }
}
