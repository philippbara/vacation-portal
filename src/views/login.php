<!DOCTYPE html>
<html>
<head>
    <title>Login - Vacation Portal</title>
</head>
<body>
    <h1>Login</h1>
    
    <?php
    if (isset($_SESSION['flash_message'])): ?>
        <div class="alert">
            <?= htmlspecialchars($_SESSION['flash_message']); ?>
        </div>
        <?php unset($_SESSION['flash_message']); // clear after displaying ?>
    <?php endif; ?>

    <form method="POST" action="/login">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>
</body>
</html>
