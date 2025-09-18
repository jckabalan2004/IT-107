<?php
require_once 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Not authenticated']);
        exit;
    }

    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
        exit;
    }
    
    $name = trim($input['name']);
    $email = trim($input['email']);
    $phone = isset($input['phone']) ? trim($input['phone']) : '';
    $user_id = $_SESSION['user_id'];

    if (empty($name) || empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Name and email are required']);
        exit;
    }
    
    try {

        $stmt = $pdo->prepare("INSERT INTO contacts (user_id, name, email, phone) VALUES (?, ?, ?, ?)");
        
        if ($stmt->execute([$user_id, $name, $email, $phone])) {
            echo json_encode(['success' => true, 'message' => 'Contact added successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add contact']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

?>
