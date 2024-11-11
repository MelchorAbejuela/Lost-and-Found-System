<?php
include 'DBcon.php';

$sender_id = $_POST['sender_id'];
$recipient_id = $_POST['recipient_id'];
$message = $_POST['message'];
$role = $_POST['role']; // 'user' or 'admin'

if (!empty($message)) {
    $sql = "INSERT INTO messages (sender_id, recipient_id, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $sender_id, $recipient_id, $message);
    $stmt->execute();
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Message cannot be empty']);
}
?>
