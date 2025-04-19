<?php
include 'db.php';

if(!isset($_GET['token'])) {
    header("Location: forgot_password.php");
    exit();
}

$token = $_GET['token'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = :token AND reset_token_expires > NOW()");
$stmt->execute(['token' => $token]);
$user = $stmt->fetch();

if(!$user) {
    header("Location: forgot_password.php?error=Invalid or expired reset token");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <?php if (isset($_GET['error'])) { ?>
        <p style="color: red;"><?php echo $_GET['error']; ?></p>
    <?php } ?>
    <form action="update_password.php" method="post">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        
        <label for="password">New Password:</label>
        <input type="password" name="password" required><br><br>
        
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" required><br><br>
        
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>