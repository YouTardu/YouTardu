<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if($password !== $confirmPassword) {
        header("Location: reset_password.php?token=$token&error=Passwords do not match");
        exit();
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = :token AND reset_token_expires > NOW()");
    $stmt->execute(['token' => $token]);
    $user = $stmt->fetch();

    if($user) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $update = $pdo->prepare("UPDATE users SET password = :password, reset_token = NULL, reset_token_expires = NULL WHERE id = :id");
        $update->execute([
            'password' => $hashedPassword,
            'id' => $user['id']
        ]);
        
        header("Location: login.php?success=Password has been reset successfully");
    } else {
        header("Location: forgot_password.php?error=Invalid or expired reset token");
    }
    exit();
}
?>