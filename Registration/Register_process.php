<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->execute(['username' => $username, 'password' => $password]);
        
        header("Location: login.php?success=Registration successful. Please login.");
        exit();
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            // Duplicate username
            header("Location: register.php?error=Username already exists");
        } else {
            header("Location: register.php?error=Registration failed");
        }
        exit();
    }
}
?>





<?php
include 'db.php';
require 'vendor/autoload.php'; // PHPMailer အတွက်

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $verificationToken = md5(uniqid(rand(), true));

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, verification_token) VALUES (:username, :email, :password, :token)");
        $stmt->execute([
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'token' => $verificationToken
        ]);
        
        // Send verification email
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
        $mail->Subject = 'Email Verification';
        $mail->Body = "Please click the following link to verify your email: <a href='http://yourwebsite.com/verify.php?token=$verificationToken'>Verify Email</a>";
        
        if($mail->send()) {
            header("Location: login.php?success=Registration successful. Please check your email for verification.");
        } else {
            header("Location: register.php?error=Registration successful but email sending failed");
        }
        exit();
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            header("Location: register.php?error=Username or email already exists");
        } else {
            header("Location: register.php?error=Registration failed");
        }
        exit();
    }
}
?>