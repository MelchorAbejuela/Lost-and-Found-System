<?php
include 'DBcon.php';

$sql = "SELECT * FROM lost_items";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['item_name']}</td>
            <td>{$row['category']}</td>
            <td>{$row['timestamp_found']}</td>
            <td>{$row['reported_by']}</td>
            <td><button class='btn btn-danger delete-btn' data-id='{$row['id']}'>Delete</button></td>
          </tr>";
}
?>
