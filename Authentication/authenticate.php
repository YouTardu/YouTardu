<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL to prevent SQL injection
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Login successful
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        
        header("Location: dashboard.php");
        exit();
    } else {
        // Login failed
        header("Location: login.php?error=Invalid username or password");
        exit();
    }
}
?>


// ... existing code ...

if ($user && password_verify($password, $user['password'])) {
    if(!$user['email_verified']) {
        header("Location: login.php?error=Please verify your email first");
        exit();
    }
    
    // Login successful
    session_start();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    
    header("Location: dashboard.php");
    exit();
} else {
    // Login failed
    header("Location: login.php?error=Invalid username or password");
    exit();
}

// ... rest of the code ...