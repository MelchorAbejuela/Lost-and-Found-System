<?php
include 'DBcon.php';

$sender_id = $_GET['sender_id'];
$recipient_id = $_GET['recipient_id'];
$last_id = isset($_GET['last_id']) ? intval($_GET['last_id']) : 0;

$query = "SELECT m.*, media.file_path, media.file_type 
        FROM messages m 
        LEFT JOIN media ON m.id = media.message_id 
        WHERE ((m.sender_id = ? AND m.recipient_id = ?) 
        OR (m.sender_id = ? AND m.recipient_id = ?)) 
        AND m.id > ? 
        ORDER BY m.id ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("iiiii", $sender_id, $recipient_id, $recipient_id, $sender_id, $last_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages);
?>
