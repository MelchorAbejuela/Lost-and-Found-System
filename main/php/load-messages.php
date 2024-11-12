<?php
include 'DBcon.php';

$sender_id = $_GET['sender_id'];
$recipient_id = $_GET['recipient_id'];
$last_id = isset($_GET['last_id']) ? intval($_GET['last_id']) : 0;

$query = "SELECT * FROM messages WHERE 
          ((sender_id = ? AND recipient_id = ?) 
          OR (sender_id = ? AND recipient_id = ?))
          AND id > ? ORDER BY id ASC";

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
