<?php
include 'DBcon.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create uploads directory if it doesn't exist
$uploadDir = "uploads/";
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

try {
    // Check if the required fields are set
    if (!isset($_POST['sender_id']) || !isset($_POST['recipient_id'])) {
        throw new Exception('Missing required fields');
    }

    $sender_id = intval($_POST['sender_id']);
    $recipient_id = intval($_POST['recipient_id']);
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    
    // Start transaction
    $conn->begin_transaction();

    // Insert the message first
    $sql = "INSERT INTO messages (sender_id, recipient_id, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $sender_id, $recipient_id, $message);
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to insert message: ' . $stmt->error);
    }
    
    $message_id = $conn->insert_id;

    // Handle file upload if present
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['file'];
        $file_name = basename($file['name']);
        $file_type = $file['type'];
        $file_size = $file['size'];
        $temp_path = $file['tmp_name'];

        // Validate file type
        $allowed_types = ['image/jpeg', 'image/png', 'video/mp4'];
        if (!in_array($file_type, $allowed_types)) {
            throw new Exception('Invalid file type. Only JPG, PNG, and MP4 files are allowed.');
        }

        // Generate unique filename to prevent overwriting
        $unique_filename = uniqid() . '_' . $file_name;
        $file_path = $uploadDir . $unique_filename;

        // Move uploaded file
        if (!move_uploaded_file($temp_path, $file_path)) {
            throw new Exception('Failed to move uploaded file.');
        }

        // Insert media information
        $sql = "INSERT INTO media (message_id, file_name, file_type, file_size, file_path) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issis", $message_id, $file_name, $file_type, $file_size, $file_path);
        
        if (!$stmt->execute()) {
            throw new Exception('Failed to insert media information: ' . $stmt->error);
        }
    }

    // Commit transaction
    $conn->commit();
    
    echo json_encode(['status' => 'success', 'message_id' => $message_id]);

} catch (Exception $e) {
    // Rollback on error
    if (isset($conn)) {
        $conn->rollback();
    }
    
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

// Close connection
if (isset($stmt)) {
    $stmt->close();
}
$conn->close();
?>