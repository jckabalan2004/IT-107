<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Return success response
echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
?>