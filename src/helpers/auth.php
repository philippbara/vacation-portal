<?php
function requireLogin() {
    if (!isset($_SESSION['user'])) {
        // Save message for next request
        $_SESSION['flash_message'] = "⚠️ Please log in to access this page.";

        // Save current URL to return to after login
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];

        // Redirect to login
        header('Location: /login');
        exit();
    }
}

function requireRole($role) {
    requireLogin(); // always ensure logged in first

    if ($_SESSION['user']['role'] !== $role) {
        $_SESSION['flash_message'] = "⚠️ You do not have permission to access that page.";
        header('Location: /dashboard');
        exit();
    }
}