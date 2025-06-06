<?php
session_start();
require_once 'helpers.php';
$pdo = getDb();

$user = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    // Password change reminder every 30 days
    $last_change = strtotime($user['last_password_change']);
    if (time() - $last_change > 30 * 24 * 3600) {
        $reminder = "Please change your password for security!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>LogoLens Project</title>
    <style>
        body { font-family: Arial; margin: 2em; background: #f8f8ff; }
        .container { max-width: 600px; margin: auto; background: #fff; border-radius: 8px; padding: 2em; box-shadow: 0 4px 16px #ccc; }
        input, textarea { width: 100%; margin-bottom: 1em; padding: 0.5em; }
        .btn { padding: 0.5em 2em; background: #3498db; color: #fff; border: none; border-radius: 4px; }
        .btn:hover { background: #217dbb; }
        .msg { margin: 1em 0; color: green; }
        .error { color: red; }
    </style>
</head>
<body>
<div class="container">
    <h2>LogoLens: Secure Logo Metadata Platform</h2>
    <?php if ($user): ?>
        <p>Welcome, <?=htmlspecialchars($user['email'])?></p>
        <?php if (isset($reminder)): ?>
            <div class="error"><?= $reminder ?></div>
        <?php endif; ?>
        <a href="upload_logo_form.php" class="btn">Upload Logo Metadata</a>
        <a href="view_logos.php" class="btn">View My Logos</a>
        <a href="change_password.php" class="btn">Change Password</a>
        <a href="logout.php" class="btn">Logout</a>
    <?php else: ?>
        <a href="login.php" class="btn">Login</a>
        <a href="register.php" class="btn">Register</a>
    <?php endif; ?>
</div>
</body>
</html>