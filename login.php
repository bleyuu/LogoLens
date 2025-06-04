<?php
session_start();
require_once 'helpers.php';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - LogoLens</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h3>Login</h3>
    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form action="auth.php" method="POST">
        <input type="email" name="email" placeholder="Email" required />
        <input type="password" name="password" placeholder="Password" required />
        <button type="submit" name="login" class="btn">Login</button>
    </form>
    <h3>Or</h3>
    <p>
        <a href="#" onclick="alert('OAuth login not implemented in demo.'); return false;" class="btn">Sign in with Google</a>
        <a href="#" onclick="alert('OAuth login not implemented in demo.'); return false;" class="btn">Sign in with Facebook</a>
    </p>
    <p>Don't have an account? <a href="register.php" class="btn">Register</a></p>
</div>
</body>
</html>