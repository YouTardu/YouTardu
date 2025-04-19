<?php
session_start();
session_destroy();
header("Location: index.php");
exit;
?>



<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: login.php");
exit();
?>