<?php
session_start();
require_once 'helpers.php';

// Register, Login, 2FA logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = getDb();

    if (isset($_POST['register'])) {
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $secret = generate_totp_secret();
        $stmt = $pdo->prepare("INSERT INTO users (email, password, totp_secret) VALUES (?, ?, ?)");
        $stmt->execute([$email, $password, $secret]);
        $_SESSION['user_id'] = $pdo->lastInsertId();
        header('Location: index.php');
        exit;
    }

    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['tmp_user_id'] = $user['id'];
            header('Location: 2fa.php');
            exit;
        }
        $error = "Invalid login.";
    }

    // 2FA verification
    if (isset($_POST['verify_2fa'])) {
        $code = $_POST['code'];
        $tmp_user_id = $_SESSION['tmp_user_id'] ?? null;
        if ($tmp_user_id) {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
            $stmt->execute([$tmp_user_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user && get_totp_code($user['totp_secret']) == $code) {
                $_SESSION['user_id'] = $user['id'];
                unset($_SESSION['tmp_user_id']);
                header('Location: index.php');
                exit;
            } else {
                $error = "Invalid 2FA code.";
            }
        }
    }
}
?>