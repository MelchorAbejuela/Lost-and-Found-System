<?php
session_start();
require_once 'DBcon.php';

// Debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Remove strict session checks
try {
    // Begin transaction
    $conn->begin_transaction();

    // Delete all messages
    $stmt = $conn->prepare("DELETE FROM messages");
    $stmt->execute();

    // Delete all related media entries
    $stmt = $conn->prepare("DELETE FROM media");
    $stmt->execute();

    // Reset auto-increment
    $conn->query("ALTER TABLE messages AUTO_INCREMENT = 1");
    $conn->query("ALTER TABLE media AUTO_INCREMENT = 1");

    // Commit transaction
    $conn->commit();

    echo json_encode(['status' => 'success', 'message' => 'Chat cleared successfully']);
} catch (Exception $e) {
    // Rollback in case of error
    $conn->rollback();
    echo json_encode(['status' => 'error', 'message' => 'Error clearing chat: ' . $e->getMessage()]);
} finally {
    $conn->close();
}
?>