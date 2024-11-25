<?php
session_start();
include('DBcon.php'); // Assuming DB connection is included

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the item ID and time claimed from the form
    $itemId = $_POST['id'];
    $timeClaimed = $_POST['time_claimed'];

    // Update the database to mark the item as claimed
    $sql = "UPDATE lost_items SET time_claimed = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $timeClaimed, $itemId);
    
    if ($stmt->execute()) {
        // If successful, redirect back to the dashboard
        header('Location: admin-portal.php');
        exit();
    } else {
        echo "Error claiming the item.";
    }
}
?>
