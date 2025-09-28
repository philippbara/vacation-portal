<?php
declare(strict_types=1);

use Dotenv\Dotenv;

$root = dirname(__DIR__);

// Load .env
$dotenv = Dotenv::createImmutable($root);
$dotenv->load();


// Environment-based error reporting
if (getenv('APP_ENV') === 'development' || getenv('APP_DEBUG') === 'true') {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(0);
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Simple PDO factory (lazy init).
 * Use get_db() anywhere to get a PDO instance.
 */
function get_db(): PDO
{
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
    $port = $_ENV['DB_PORT'] ?? '5432';
    $dbname = $_ENV['DB_NAME'] ?? 'vacation_portal';
    $user = $_ENV['DB_USER'] ?? 'vacation_user';
    $pass = $_ENV['DB_PASS'] ?? '';

    $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $pdo = new PDO($dsn, $user, $pass, $options);
    return $pdo;
}
