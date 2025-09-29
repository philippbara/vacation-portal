<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Employees</title>
    <style>
        table { border-collapse: collapse; width: 90%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px 12px; }
        th { background-color: #eee; }
        a { text-decoration: none; color: #00f; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h1>Dashboard - Employees</h1>
    <p>Welcome, <?= htmlspecialchars($username) ?>! Role: <strong><?= htmlspecialchars($role) ?></strong></p>

    <?php if (in_array($role, ['manager', 'admin'])): ?>
        <p>
            <a href="/users/create" style="padding: 6px 12px; background: #4CAF50; color: white; text-decoration: none;">➕ Create New User</a>
        </p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th></th> <!-- Attention icon -->
                <th>Username</th>
                <th>Name</th>
                <th>Email</th>
                <th>Total Vacation Requests</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
                <?php 
                    // Determine if user has any pending requests
                    $pending_count = $u['pending_requests'] ?? 0; 
                ?>
                <tr onclick="window.location='/users/<?= $u['id'] ?>'" style="cursor:pointer;">
                    <td style="text-align:center;">
                        <?php if ($pending_count > 0): ?>
                            ⚠️ <?= $pending_count ?>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($u['username']) ?></td>
                    <td><?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= htmlspecialchars($u['total_vacations']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p>
        <a href="/logout">
            <button type="button">Logout</button>
        </a>
    </p>
</body>
</html>
