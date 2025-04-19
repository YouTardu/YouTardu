<?php
include 'db.php';
require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $resetToken = bin2hex(random_bytes(16));
    $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if($user) {
        $update = $pdo->prepare("UPDATE users SET reset_token = :token, reset_token_expires = :expires WHERE id = :id");
        $update->execute([
            'token' => $resetToken,
            'expires' => $expires,
            'id' => $user['id']
        ]);

        // Send reset email
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@example.com';
        $mail->Password = 'your_email_password';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        
        $mail->setFrom('your_email@example.com', 'Your Website');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body = "Please click the following link to reset your password: <a href='http://yourwebsite.com/reset_password.php?token=$resetToken'>Reset Password</a><br><br>This link will expire in 1 hour.";
        
        if($mail->send()) {
            header("Location: forgot_password.php?message=Password reset link has been sent to your email");
        } else {
            header("Location: forgot_password.php?error=Failed to send reset link");
        }
    } else {
        header("Location: forgot_password.php?message=If the email exists, a reset link has been sent");
    }
    exit();
}
?>





<?php
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_GET['token'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ? AND created_at > NOW() - INTERVAL 1 HOUR");
        $stmt->execute([$token]);
        $reset = $stmt->fetch();

        if ($reset) {
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
            $stmt->execute([$new_password, $reset['email']]);

            $stmt = $pdo->prepare("DELETE FROM password_resets WHERE token = ?");
            $stmt->execute([$token]);

            echo "Password reset successfully! You can now <a href='index.php'>login</a>.";
        } else {
            echo "Invalid or expired token.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form method="POST" action="">
        <input type="password" name="new_password" placeholder="New Password" required><br>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>







