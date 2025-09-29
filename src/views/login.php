<?php include __DIR__ . '/components/header.php'; ?>

<link rel="stylesheet" href="/css/input.css">

<div class="page-container">
    <h1>Login</h1>
    
    <form class="form-container aligned" method="POST" action="/login">
        <label for="username">Username:</label>
        <input id="username" type="text" name="username" required>

        <label for="password">Password:</label>
        <input id="password" type="password" name="password" required>

        <button type="submit">Login</button>
    </form>
</div>

<?php include __DIR__ . '/components/footer.php'; ?>
