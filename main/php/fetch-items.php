<?php
include('DBcon.php');

$search = isset($_GET['search']) ? $_GET['search'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : 'unclaimed';

$query = "SELECT * FROM lost_items 
          WHERE status = ? AND 
          (item_name LIKE ? OR category LIKE ?)";
$searchParam = "%$search%";

$stmt = $conn->prepare($query);
$stmt->bind_param('sss', $status, $searchParam, $searchParam);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    // Your existing row generation code remains the same
    echo "<tr class='animate-fade-in'>
        <td>
        <img src='" . htmlspecialchars($row['image_path']) . "' 
        alt='Item Image' 
        class='item-image preview-image' 
        data-image-src='" . htmlspecialchars($row['image_path']) . "'>
        </td>
        <td>" . htmlspecialchars($row['item_name']) . "</td>
        <td>" . htmlspecialchars($row['category']) . "</td>
        <td>" . htmlspecialchars($row['timestamp_found']) . "</td>
        <td>" . htmlspecialchars($row['reported_by']) . "</td>
        <td><span class='status-badge'>Available</span></td>
        <td><a href='chat-user.php?item_id=" . htmlspecialchars($row['id']) . "' class='claim-btn'>Claim</a></td>
    </tr>"; 
}
?>