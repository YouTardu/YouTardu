<?php
include 'db.php';

if(isset($_GET['token'])) {
    $token = $_GET['token'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE verification_token = :token");
    $stmt->execute(['token' => $token]);
    $user = $stmt->fetch();
    
    if($user) {
        $update = $pdo->prepare("UPDATE users SET email_verified = TRUE, verification_token = NULL WHERE id = :id");
        $update->execute(['id' => $user['id']]);
        
        header("Location: login.php?success=Email verified successfully. You can now login.");
    } else {
        header("Location: login.php?error=Invalid verification token");
    }
    exit();
} else {
    header("Location: login.php");
    exit();
}
?>





<?php
require 'db_connect.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE verification_token = ? AND is_verified = FALSE");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        if ($user) {
            $stmt = $pdo->prepare("UPDATE users SET is_verified = TRUE, verification_token = NULL WHERE id = ?");
            $stmt->execute([$user['id']]);
            echo "Email verified successfully! You can now <a href='index.php'>login</a>.";
        } else {
            echo "Invalid or expired token.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "No token provided.";
}
?>