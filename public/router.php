<?php
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// Serve the requested file if it exists
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false; // Let PHP built-in server serve it directly
}

// Otherwise, route through index.php
require_once __DIR__ . '/index.php';
