<!DOCTYPE html>
<html>
<head>
    <title>New Vacation Request - <?= htmlspecialchars($user['username']) ?></title>
</head>
<body>
    <h1>New Vacation Request for <?= htmlspecialchars($user['username']) ?></h1>

    <?php if (!empty($_SESSION['flash_message'])): ?>
        <p style="color: red;"><?= htmlspecialchars($_SESSION['flash_message']) ?></p>
        <?php unset($_SESSION['flash_message']); ?>
    <?php endif; ?>

    <form method="post" action="/users/<?= $user['id'] ?>/new">
        <p>
            <label>Start Date: <input type="date" name="start_date" required></label>
        </p>
        <p>
            <label>End Date: <input type="date" name="end_date" required></label>
        </p>
        <p>
            <label>Reason:<br>
                <textarea name="reason" rows="3" cols="30"></textarea>
            </label>
        </p>
        <p>
            <button type="submit" onclick="return confirm('Are you sure you want to create a new vacation request?');">Submit Request</button>
            <a href="/users/<?= $user['id'] ?>">Cancel</a>
        </p>
    </form>
</body>
</html>
