<?php include __DIR__ . '/components/header.php'; ?>

<link rel="stylesheet" href="/css/input.css">

<div class="page-container">
    <h1>Edit User</h1>

    <form class="form-container aligned" method="POST" action="/users/edit/<?= htmlspecialchars($user['id']) ?>">
        <label>Username:</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" disabled>

        <label>Employee Code:</label>
        <input type="text" name="employee_code" value="<?= htmlspecialchars($user['employee_code']) ?>" disabled>

        <label>First Name:</label>
        <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>">

        <label>Last Name:</label>
        <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>">

        <label><span class="required">*</span> Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

        <label>Password (leave blank to keep current):</label>
        <input type="password" name="password" placeholder="••••••••">

        <label>Role:</label>
        <select name="role" disabled>
            <option value="employee" <?= $user['role'] === 'employee' ? 'selected' : '' ?>>Employee</option>
            <option value="manager" <?= $user['role'] === 'manager' ? 'selected' : '' ?>>Manager</option>
        </select>

        <button type="submit" class="dashboard-btn">Save Changes</button>
        <button type="button" class="dashboard-btn danger" onclick="window.location.href='/dashboard'">Cancel</button>
    </form>
</div>

<?php include __DIR__ . '/components/footer.php'; ?>
