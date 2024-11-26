<?php 
include('DBcon.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {     
    // Collect and sanitize input data     
    $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);     
    $category = mysqli_real_escape_string($conn, $_POST['category']);     
    $timestamp_found = mysqli_real_escape_string($conn, $_POST['timestamp_found']);     
    $reported_by = mysqli_real_escape_string($conn, $_POST['reported_by']);     
    $status = 'unclaimed'; // Add default status
    
    // Handle image upload     
    $image_path = ''; // Default to empty if no image is uploaded      
    if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] == 0) {         
        // Define the upload path         
        $target_dir = "uploads/";         
        $target_file = $target_dir . basename($_FILES["item_image"]["name"]);                  
        
        // Move the uploaded file to the target directory         
        if (move_uploaded_file($_FILES["item_image"]["tmp_name"], $target_file)) {             
            // If the image is uploaded successfully, store the file path             
            $image_path = $target_file;         
        }     
    }      

    // Prepare and execute the SQL statement using prepared statements
    $sql = "INSERT INTO lost_items (item_name, category, timestamp_found, reported_by, image_path, status) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssss', $item_name, $category, $timestamp_found, $reported_by, $image_path, $status);

    if ($stmt->execute()) {
        // Redirect to admin-portal with a success query parameter         
        header("Location: admin-portal.php?success=1");         
        exit; // Ensure the script stops after redirection     
    } else {         
        echo "Error: " . $stmt->error;     
    }

    // Close the statement
    $stmt->close();
}
?>