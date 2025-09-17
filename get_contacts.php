<?php
require_once 'config.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Get contacts for the logged-in user
    $stmt = $pdo->prepare("SELECT * FROM contacts WHERE user_id = ? ORDER BY name");
    $stmt->execute([$user_id]);
    $contacts = $stmt->fetchAll();

    echo json_encode(['success' => true, 'contacts' => $contacts]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>