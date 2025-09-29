<?php include __DIR__ . '/components/header.php'; ?>

<link rel="stylesheet" href="/css/input.css">

<div class="page-container">
    <h1>New Vacation Request for <?= htmlspecialchars($user['username']) ?></h1>

    <form class="form-container aligned" method="post" action="/users/<?= $user['id'] ?>/new">
        <label>Start Date:</label>
        <input type="date" name="start_date" required>

        <label>End Date:</label>
        <input type="date" name="end_date" required>

        <label>Reason:</label>
        <textarea name="reason" rows="3"></textarea>

        <button type="submit" class="dashboard-btn" onclick="return confirm('Are you sure you want to create a new vacation request?');">
            Submit Request
        </button>
        <button type="button" class="dashboard-btn danger" onclick="window.location.href='/users/<?= $user['id'] ?>'">
            Cancel
        </button>
    </form>
</div>

<?php include __DIR__ . '/components/footer.php'; ?>
