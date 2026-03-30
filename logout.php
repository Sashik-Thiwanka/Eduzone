<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect back to the home page
// IMPORTANT: Change 'home.html' to your actual home page file name
header('Location: index.html'); 
exit;
?>