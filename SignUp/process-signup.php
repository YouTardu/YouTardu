<?php 
if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/', $password)) {
    header("Location: signup.php?error=Password must be at least 6 characters long, include at least one letter, one number, and one special character");
    exit();
}

if ($stmt->execute()) {
    $to = $email;
    $subject = "Account Verification";
    $message = "Hi $name, thank you for signing up! Please verify your email address.";
    $headers = "From: no-reply@example.com";

    mail($to, $subject, $message, $headers);
    header("Location: signup.php?success=Account created successfully. Please check your email.");
} else {
    header("Location: signup.php?error=Something went wrong. Please try again");
}

$recaptchaResponse = $_POST['g-recaptcha-response'];
$secretKey = "YOUR_SECRET_KEY";

$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptchaResponse");
$responseData = json_decode($response);

if (!$responseData->success) {
    header("Location: signup.php?error=Captcha validation failed. Try again.");
    exit();
}


session_start();
$conn = new mysqli($host, $user, $pass, $db);

$email = trim($_POST['email']);
$password = trim($_POST['password']);

$stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $name, $hashedPassword);
    $stmt->fetch();

    if (password_verify($password, $hashedPassword)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['user_name'] = $name;
        header("Location: dashboard.php");
    } else {
        header("Location: login.php?error=Invalid password");
    }
} else {
    header("Location: login.php?error=No account found with that email");
}
?>