<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <?php if (isset($_GET['message'])) { ?>
        <p style="color: green;"><?php echo $_GET['message']; ?></p>
    <?php } ?>
    <?php if (isset($_GET['error'])) { ?>
        <p style="color: red;"><?php echo $_GET['error']; ?></p>
    <?php } ?>
    <form action="send_reset_link.php" method="post">
        <label for="email">Email:</label>
        <input type="email" name="email" required><br><br>
        
        <button type="submit">Send Reset Link</button>
    </form>
    <p>Remember your password? <a href="login.php">Login here</a></p>
</body>
</html>




<?php
require 'db_connect.php';
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendPasswordResetEmail($email, $token) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@gmail.com';
        $mail->Password = 'your_app_password';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('your_email@gmail.com', 'Your App');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body = "Click the link to reset your password: <a href='http://yourdomain.com/reset_password.php?token=$token'>Reset Password</a>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            $token = bin2hex(random_bytes(32));
            $stmt = $pdo->prepare("INSERT INTO password_resets (email, token) VALUES (?, ?)");
            $stmt->execute([$email, $token]);

            if (sendPasswordResetEmail($email, $token)) {
                echo "Password reset link has been sent to your email.";
            } else {
                echo "Failed to send reset email.";
            }
        } else {
            echo "Email not found.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form method="POST" action="">
        <input type="email" name="email" placeholder="Enter your email" required><br>
        <button type="submit">Send Reset Link</button>
    </form>
</body>
</html>