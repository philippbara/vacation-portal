<?php include __DIR__ . '/components/header.php'; ?>

<link rel="stylesheet" href="/css/input.css">

<div class="page-container">
    <h1>Create New User</h1>

    <form class="form-container aligned" method="POST" action="/users/create">
        <label><span class="required">*</span> Username:</label>
        <input type="text" name="username" required>

        <label><span class="required">*</span> Employee Code:</label>
        <input type="text" name="employee_code" required>

        <label>First Name:</label>
        <input type="text" name="first_name">

        <label>Last Name:</label>
        <input type="text" name="last_name">

        <label><span class="required">*</span> Email:</label>
        <input type="email" name="email" required>

        <label><span class="required">*</span> Password:</label>
        <input type="password" name="password" required>

        <label>Role:</label>
        <select name="role">
            <option value="employee">Employee</option>
            <option value="manager">Manager</option>
        </select>

        <button type="submit" class="dashboard-btn">Create User</button>
        <button type="button" class="dashboard-btn danger" onclick="window.location.href='/dashboard'">Cancel</button>
    </form>
</div>

<?php include __DIR__ . '/components/footer.php'; ?>
