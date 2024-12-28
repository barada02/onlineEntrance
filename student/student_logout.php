<?php
session_start();

// Clear all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to student login page
header("Location: student_login.html");
exit();
?>