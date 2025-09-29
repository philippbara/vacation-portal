<!DOCTYPE html>
<html>
<head>
    <title>Vacation Requests for <?= htmlspecialchars($user['username']) ?></title>
    <style>
        table { border-collapse: collapse; width: 90%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px 12px; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h1>Vacation Requests for <?= htmlspecialchars($user['username']) ?></h1>

    <?php if (!empty($_SESSION['flash_message'])): ?>
        <p style="color: red;"><?= htmlspecialchars($_SESSION['flash_message']) ?></p>
        <?php unset($_SESSION['flash_message']); ?>
    <?php endif; ?>

    <?php if ($current_user['id'] === $user['id']): ?>
        <p>
            <a href="/users/<?= $user['id'] ?>/new" style="padding: 6px 12px; background: #4CAF50; color: white; text-decoration: none;">+ New Vacation Request</a>
        </p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Reason</th>
                <th>Submitted At</th>
                <th>Duration (weekdays)</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($requests as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['start_date']) ?></td>
                    <td><?= htmlspecialchars($r['end_date']) ?></td>
                    <td><?= htmlspecialchars($r['reason']) ?></td>
                    <td>
                        <?php
                            $submitted = new DateTime($r['created_at']);
                            echo $submitted->format('Y-m-d');
                        ?>
                    </td>
                    <td>
                        <?php
                        $start = new DateTime($r['start_date']);
                        $end = new DateTime($r['end_date']);
                        $interval = new DateInterval('P1D');
                        $period = new DatePeriod($start, $interval, $end->modify('+1 day'));
                        $weekdays = 0;
                        foreach ($period as $dt) {
                            if ($dt->format('N') < 6) { // 1-5 are Monday-Friday
                                $weekdays++;
                            }
                        }
                        echo $weekdays;
                        ?>
                    </td>
                    <td><?= htmlspecialchars(ucfirst($r['status'])) ?></td>
                    <td>
                        <?php if ($r['status'] === 'pending'): ?>
                            <?php if ($current_user['role'] === 'employee' && $current_user['id'] === $user['id']): ?>
                                <form method="post" action="/users/<?= $user['id'] ?>/<?= $r['id'] ?>/delete" style="display:inline;">
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this pending request?');">
                                        Delete
                                    </button>
                                </form>
                            <?php elseif (in_array($current_user['role'], ['manager', 'admin'])): ?>
                                <form method="post" action="/users/<?= $user['id'] ?>/<?= $r['id'] ?>/approve" style="display:inline;">
                                    <button type="submit" onclick="return confirm('Are you sure you want to approve this vacation request?');">Approve</button>
                                </form>
                                <form method="post" action="/users/<?= $user['id'] ?>/<?= $r['id'] ?>/reject" style="display:inline;">
                                    <button type="submit" onclick="return confirm('Are you sure you want to reject this vacation request?');">Reject</button>
                                </form>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (in_array($current_user['role'], ['manager', 'admin'])): ?>
        <p>
            <a href="/dashboard">
                <button type="button">Back to Dashboard</button>
            </a>
        </p>
    <?php endif; ?>
    <p>
        <a href="/logout">
            <button type="button">Logout</button>
        </a>
    </p>
</body>
</html>
