<?php
session_start();
if (!isset($_SESSION['tmp_user_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    
    <title>2FA Verification</title>
    <link rel="stylesheet" href="index.php">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h3>Enter 2FA Code</h3>
    <form action="auth.php" method="POST">
        <input type="text" name="code" placeholder="6-digit code" required>
        <button type="submit" name="verify_2fa" class="btn">Verify</button>
    </form>
    <p>Open Google Authenticator and enter the code for your account.</p>
</div>
</body>
</html>