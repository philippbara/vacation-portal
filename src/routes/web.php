<?php
// This file is loaded inside the FastRoute callback where $r is the RouteCollector.
// Add simple routes here for testing and later replace with controller handlers.

$r->addRoute('GET', '/', function () {
    header('Content-Type: text/html; charset=utf-8');
    echo '<h1>Vacation Portal</h1><p>Welcome — app running.</p>';
});

$r->addRoute('GET', '/hello', function () {
    header('Content-Type: text/plain; charset=utf-8');
    echo "Hello, Vacation Portal";
});

$r->addRoute('GET', '/users', function () {
    require_once __DIR__ . '/../models/user.php';
    $users = \App\Models\User::all();
    header('Content-Type: application/json');
    echo json_encode($users, JSON_PRETTY_PRINT);
});
    