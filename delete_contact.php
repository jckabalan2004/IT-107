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
    
    if (!$input || !isset($input['contact_id'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
        exit;
    }
    
    $contact_id = $input['contact_id'];
    $user_id = $_SESSION['user_id'];
    
    try {
        $stmt = $pdo->prepare("SELECT id FROM contacts WHERE id = ? AND user_id = ?");
        $stmt->execute([$contact_id, $user_id]);
        $contact = $stmt->fetch();
        
        if (!$contact) {
            echo json_encode(['success' => false, 'message' => 'Contact not found or access denied']);
            exit;
        }

        $stmt = $pdo->prepare("DELETE FROM contacts WHERE id = ?");
        
        if ($stmt->execute([$contact_id])) {
            echo json_encode(['success' => true, 'message' => 'Contact deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete contact']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

?>
