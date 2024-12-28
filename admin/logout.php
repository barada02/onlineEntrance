<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page with success message
header("Location: admin_login.html?success=" . urlencode("You have been successfully logged out"));
exit();
?>
