<?php
// This file is loaded inside the FastRoute callback where $r is the RouteCollector.
// Add simple routes here for testing and later replace with controller handlers.

$r->addRoute('GET', '/', function () {
    header('Content-Type: text/html; charset=utf-8');
    echo '<h1>Vacation Portal</h1><p>Welcome — app running.</p>';
});

$r->addRoute('GET', '/login', function () {
    require __DIR__ . '/../views/login.php';
});

$r->addRoute('POST', '/login', function () {
    require __DIR__ . '/../controllers/AuthController.php';
    \App\Controllers\AuthController::login();
});

$r->addRoute('GET', '/logout', function () {
    require __DIR__ . '/../controllers/AuthController.php';
    \App\Controllers\AuthController::logout();
});

$r->addRoute('GET', '/dashboard', function () {
    require_once __DIR__ . '/../helpers/auth.php';
    requireLogin(); 

    require __DIR__ . '/../controllers/AuthController.php';
    \App\Controllers\AuthController::dashboard();
});

$r->addRoute('GET', '/users/{id:\d+}', function ($args) {
    require_once __DIR__ . '/../helpers/auth.php';
    requireLogin(); // must be logged in

    $current_user = $_SESSION['user'];
    $role = $current_user['role'];
    $requested_user_id = (int)$args['id'];

    require_once __DIR__ . '/../models/User.php';
    require_once __DIR__ . '/../models/VacationRequest.php';

    // Employees can only view their own requests
    if ($role === 'employee' && $requested_user_id !== $current_user['id']) {
        $_SESSION['flash_message'] = "⚠️ You cannot view other users' vacation requests.";
        header('Location: /dashboard');
        exit;
    }

    // Fetch user
    $user = \App\Models\User::find($requested_user_id);
    if (!$user) {
        $_SESSION['flash_message'] = "User not found.";
        header('Location: /dashboard');
        exit;
    }

    // Fetch vacation requests
    $requests = ($role === 'employee') 
        ? \App\Models\VacationRequest::byUser($current_user['id'])
        : \App\Models\VacationRequest::byUser($requested_user_id);

    require __DIR__ . '/../views/user_vacation_requests.php';
});

// Show form
$r->addRoute('GET', '/users/{id:\d+}/new', function ($args) {
    require_once __DIR__ . '/../helpers/auth.php';
    requireLogin();

    $current_user = $_SESSION['user'];
    $requested_user_id = (int)$args['id'];

    // Employees can only create their own requests
    if ($current_user['role'] === 'employee' && $current_user['id'] !== $requested_user_id) {
        $_SESSION['flash_message'] = "⚠️ You cannot create requests for other users.";
        header('Location: /dashboard');
        exit;
    }

    require_once __DIR__ . '/../models/User.php';
    $user = \App\Models\User::find($requested_user_id);
    if (!$user) {
        $_SESSION['flash_message'] = "User not found.";
        header('Location: /dashboard');
        exit;
    }

    require __DIR__ . '/../views/new_vacation_request.php';
});

// Handle form submission
$r->addRoute('POST', '/users/{id:\d+}/new', function ($args) {
    require_once __DIR__ . '/../helpers/auth.php';
    requireLogin();

    $current_user = $_SESSION['user'];
    $requested_user_id = (int)$args['id'];

    if ($current_user['role'] === 'employee' && $current_user['id'] !== $requested_user_id) {
        $_SESSION['flash_message'] = "⚠️ You cannot create requests for other users.";
        header('Location: /dashboard');
        exit;
    }

    require_once __DIR__ . '/../models/VacationRequest.php';

    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $reason = $_POST['reason'] ?? '';

    // Simple validation
    if (empty($start_date) || empty($end_date)) {
        $_SESSION['flash_message'] = "Start and end dates are required.";
        header("Location: /users/{$requested_user_id}/new");
        exit;
    }
    if (empty($reason)){
        $_SESSION['flash_message'] = "Vacation reason is required.";
        header("Location: /users/{$requested_user_id}/new");
        exit;
    }

    // Parse dates
    $start_ts = strtotime($start_date);
    $end_ts = strtotime($end_date);
    $today_ts = strtotime(date('Y-m-d'));

    // Check start <= end
    if ($start_ts > $end_ts) {
        $_SESSION['flash_message'] = "⚠️ End date must be after or equal to start date.";
        header("Location: /users/{$requested_user_id}/new");
        exit;
    }

    // Check both dates are in the future (or today)
    if ($start_ts <= $today_ts || $end_ts <= $today_ts) {
        $_SESSION['flash_message'] = "⚠️ Both start and end dates must be in the future.";
        header("Location: /users/{$requested_user_id}/new");
        exit;
    }

    \App\Models\VacationRequest::create($requested_user_id, $start_date, $end_date, $reason);

    $_SESSION['flash_message'] = "✅ Vacation request submitted successfully.";
    header("Location: /users/{$requested_user_id}");
    exit;
});

$r->addRoute('POST', '/users/{user_id:\d+}/{request_id:\d+}/delete', function ($args) {
    require_once __DIR__ . '/../helpers/auth.php';
    requireLogin();

    $current_user = $_SESSION['user'];
    $user_id = (int)$args['user_id'];
    $request_id = (int)$args['request_id'];

    if ($current_user['role'] === 'employee' && $current_user['id'] !== $user_id) {
        $_SESSION['flash_message'] = "⚠️ You cannot delete requests for other users.";
        header('Location: /dashboard');
        exit;
    }

    require_once __DIR__ . '/../models/VacationRequest.php';

    $request = \App\Models\VacationRequest::find($request_id);
    if (!$request || $request['user_id'] !== $user_id) {
        $_SESSION['flash_message'] = "Request not found.";
        header("Location: /users/{$user_id}");
        exit;
    }

    if ($request['status'] !== 'pending') {
        $_SESSION['flash_message'] = "Only pending requests can be deleted.";
        header("Location: /users/{$user_id}");
        exit;
    }

    \App\Models\VacationRequest::delete($request_id);
    $_SESSION['flash_message'] = "✅ Vacation request deleted successfully.";
    header("Location: /users/{$user_id}");
    exit;
});

// Approve vacation request
$r->addRoute('POST', '/users/{user_id:\d+}/{request_id:\d+}/approve', function($args) {
    require_once __DIR__ . '/../helpers/auth.php';
    requireRole('manager');

    $current_user = $_SESSION['user'];
    if (!in_array($current_user['role'], ['manager', 'admin'])) {
        $_SESSION['flash_message'] = "⚠️ You do not have permission to approve requests.";
        header('Location: /dashboard');
        exit;
    }

    require_once __DIR__ . '/../models/VacationRequest.php';
    $request = \App\Models\VacationRequest::find($args['request_id']);
    if (!$request || $request['status'] !== 'pending') {
        $_SESSION['flash_message'] = "Request not found or already processed.";
        header("Location: /users/{$args['user_id']}");
        exit;
    }

    \App\Models\VacationRequest::updateStatus($args['request_id'], 'approved');
    $_SESSION['flash_message'] = "✅ Vacation request approved.";
    header("Location: /users/{$args['user_id']}");
    exit;
});

// Reject vacation request
$r->addRoute('POST', '/users/{user_id:\d+}/{request_id:\d+}/reject', function($args) {
    require_once __DIR__ . '/../helpers/auth.php';
    requireRole('manager');

    $current_user = $_SESSION['user'];
    if (!in_array($current_user['role'], ['manager', 'admin'])) {
        $_SESSION['flash_message'] = "⚠️ You do not have permission to reject requests.";
        header('Location: /dashboard');
        exit;
    }

    require_once __DIR__ . '/../models/VacationRequest.php';
    $request = \App\Models\VacationRequest::find($args['request_id']);
    if (!$request || $request['status'] !== 'pending') {
        $_SESSION['flash_message'] = "Request not found or already processed.";
        header("Location: /users/{$args['user_id']}");
        exit;
    }

    \App\Models\VacationRequest::updateStatus($args['request_id'], 'rejected');
    $_SESSION['flash_message'] = "✅ Vacation request rejected.";
    header("Location: /users/{$args['user_id']}");
    exit;
});

$r->addRoute('GET', '/users/create', function () {
    require_once __DIR__ . '/../helpers/auth.php';
    requireRole('manager');

    require_once __DIR__ . '/../controllers/UserController.php';
    \App\Controllers\UserController::createForm();
});

// Handle form submission
$r->addRoute('POST', '/users/create', function () {
    require_once __DIR__ . '/../helpers/auth.php';
    requireRole('manager');

    require_once __DIR__ . '/../controllers/UserController.php';
    \App\Controllers\UserController::create();
});



