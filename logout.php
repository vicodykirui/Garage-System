<?php
// Start the session to access session variables
session_start();

// Destroy the session to log the user out
session_destroy();

// Redirect to login page after logout
header("Location: login.html");
exit();
?>
