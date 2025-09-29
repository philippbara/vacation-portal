<!DOCTYPE html>
<html>
<head>
    <title>Create New User</title>
    <style>
        label span.required {
            color: red;
        }
    </style>
</head>
<body>
    <h1>Create New User</h1>

    <?php if (!empty($_SESSION['flash_message'])): ?>
        <p style="color:red"><?= htmlspecialchars($_SESSION['flash_message']) ?></p>
        <?php unset($_SESSION['flash_message']); ?>
    <?php endif; ?>

    <form method="POST" action="/users/create">
        <p>
            <label><span class="required">*</span>Username: <input type="text" name="username" required></label>
        </p>
        <p>
            <label>First Name: <input type="text" name="first_name"></label>
        </p>
        <p>
            <label>Last Name: <input type="text" name="last_name"></label>
        </p>
        <p>
            <label><span class="required">*</span>Email: <input type="email" name="email" required></label>
        </p>
        <p>
            <label><span class="required">*</span>Password: <input type="password" name="password" required></label>
        </p>
        <p>
            <label>Role:
                <select name="role">
                    <option value="employee">Employee</option>
                    <option value="manager">Manager</option>
                </select>
            </label>
        </p>
        <p>
            <button type="submit">Create User</button>
            <a href="/dashboard"><button type="button">Cancel</button></a>
        </p>
    </form>
</body>
</html>
