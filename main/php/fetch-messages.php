<?php
include 'DBcon.php';

$sender_id = $_GET['sender_id'];
$recipient_id = $_GET['recipient_id'];

$sql = "SELECT * FROM messages 
        WHERE (sender = ? AND recipient = ?) 
        OR (sender = ? AND recipient = ?) 
        ORDER BY timestamp ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $sender_id, $recipient_id, $recipient_id, $sender_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages);
?>
