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
            $first_name = trim($_POST['first_name']);
            $last_name = trim($_POST['last_name']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $role = $_POST['role'];

            // Basic empty field validation
            if (!$username || !$password || !$email ) {
                $_SESSION['flash_message'] = "Username, email and password are required.";
                header('Location: /users/create');
                exit;
            }
            // Basic email format validation
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['flash_message'] = "Invalid email format.";
                header('Location: /users/create');
                exit;
            }

            $pdo = get_db();            

            // Check if username already exists
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $exists = $stmt->fetchColumn();

            if ($exists) {
                $_SESSION['flash_message'] = "Username '{$username}' is already taken.";
                header('Location: /users/create');
                exit;
            }

            // Check if email already exists
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetchColumn() > 0) {
                $_SESSION['flash_message'] = "Email '{$email}' is already in use.";
                header('Location: /users/create');
                exit;
            }

            // Save new user
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("
                INSERT INTO users (username, first_name, last_name, email, password_hash, role)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$username, $first_name, $last_name, $email, $password_hash, $role]);

            $_SESSION['flash_message'] = "User created successfully.";
            header('Location: /dashboard');
            exit;
        }
    }
}
