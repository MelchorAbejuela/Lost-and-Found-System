<?php include 'DBcon.php';  
$search = isset($_GET['search']) ? $_GET['search'] : ''; 
$sql = "SELECT * FROM lost_items WHERE item_name LIKE ? OR category LIKE ?"; 
$stmt = $conn->prepare($sql); 
$searchParam = "%$search%"; 
$stmt->bind_param("ss", $searchParam, $searchParam); 
$stmt->execute(); 
$result = $stmt->get_result();  

while ($row = $result->fetch_assoc()) {     
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

$stmt->close(); 
$conn->close(); 
?>