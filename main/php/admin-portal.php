<?php

session_start();
include('DBcon.php'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal - Lost and Found</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <link href="../css/admin-portal.css" rel="stylesheet">
</head>
<body>
    <!-- Top Navigation Bar -->
    <div class="top-nav d-flex justify-content-between align-items-center">
        <h4>Admin Portal</h4>
        <div>
            <!-- Chat Button -->
            <button class="btn btn-light" onclick="location.href='chat-admin.php'">
                <i class="fas fa-comments"></i> 
            </button>
            
            <!-- Logout Button -->
            <button class="btn btn-light" onclick="location.href='login-admin.php'">
                <i class="fas fa-sign-out-alt"></i> 
            </button>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="text-center mb-4">
            <img id="admin-picture" src="../img/user.png" alt="Admin" class="rounded-circle">
            <h5 class="mt-2">Welcome, Admin</h5>
        </div>
        <a href="#" class="nav-link active" id="dashboard-tab">Dashboard</a>
        <a href="#" class="nav-link" id="lost-items-tab">Lost Items Inventory</a>
        <a href="#" class="nav-link" id="report-log-tab">Report Log</a>
    </div>

    <!-- Content Area -->
    <div class="content-area">
        <!-- Dashboard Tab (CRUD Table) -->
    <!-- Dashboard Tab -->
<div id="dashboard" class="tab-content">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <h3>
            <i class="fas fa-tachometer-alt"></i>
            Dashboard
        </h3>
    </div>
    
    <!-- Dashboard Actions -->
    <div class="dashboard-actions">
        <div class="dashboard-filters">
            
        </div>
        <button class="add-item-btn" data-bs-toggle="modal" data-bs-target="#addItemModal">
            <i class="fas fa-plus"></i>
            Add Lost Item
        </button>
    </div>

    <!-- Dashboard Stats -->
    <div class="dashboard-stats">
        <div class="stat-card">
            <h4>Total Items</h4>
            <p class="stat-value">
                <?php
                    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM lost_items");
                    $row = mysqli_fetch_assoc($result);
                    echo $row['total'];
                ?>
            </p>
        </div>
        <div class="stat-card">
            <h4>Unclaimed Items</h4>
            <p class="stat-value">
                <?php
                    $result = mysqli_query($conn, "SELECT COUNT(*) as unclaimed FROM lost_items WHERE status = 'unclaimed'");
                    $row = mysqli_fetch_assoc($result);
                    echo $row['unclaimed'];
                ?>
            </p>
        </div>
        <div class="stat-card">
            <h4>Items Claimed Today</h4>
            <p class="stat-value">
                <?php
                    $result = mysqli_query($conn, "SELECT COUNT(*) as claimed_today FROM lost_items WHERE DATE(time_claimed) = CURDATE()");
                    $row = mysqli_fetch_assoc($result);
                    echo $row['claimed_today'];
                ?>
            </p>
        </div>
    </div>

    <!-- Table Section -->
<div class="table-responsive" style="max-height: 500px; overflow-y: auto; margin-top: 2rem;">
    <div class="dashboard-header" style="margin-bottom: 1rem;">
        <h3>
            <i class="fas fa-list"></i>
            Recent Lost Items
        </h3>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Items</th>
                <th>Item Name</th>
                <th>Category</th>
                <th>Timestamp Found</th>
                <th>Reported By</th>
                <th>Time Claimed</th>
                <th>Actions</th>
            </tr>
        </thead>
            <tbody>
            <?php
            $result = mysqli_query($conn, "SELECT * FROM lost_items WHERE status = 'unclaimed'");
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                    <td>
                        <a href='" . $row['image_path'] . "' target='_blank'>
                            <img src='" . $row['image_path'] . "' alt='Item Image' style='max-width: 60px; height: 60px; object-fit: cover; border-radius: 8px; cursor: pointer; transition: transform 0.3s ease;' onmouseover='this.style.transform=\"scale(1.1)\"' onmouseout='this.style.transform=\"scale(1)\"'>
                        </a>
                    </td>
                    <td>" . $row['item_name'] . "</td>
                    <td>" . $row['category'] . "</td>
                    <td>" . $row['timestamp_found'] . "</td>
                    <td>" . $row['reported_by'] . "</td>
                    <td>Unclaimed</td>
                    <td>
                        <div class='btn-group' role='group'>
                            <button type='button' class='btn btn-info btn-sm'
                                data-bs-toggle='modal'
                                data-bs-target='#claimModal'
                                data-id='" . $row['id'] . "'>
                                <i class='fas fa-check-circle'></i>
                            </button>
                            <button type='button' class='btn btn-success btn-sm'>
                                <i class='fas fa-edit'></i>
                            </button>
                        </div>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

    <!-- Table Wrapper -->
    <div style="max-height: 500px; overflow-y: auto;">
        <table class="table table-striped">
            <!-- Existing table content -->
        </table>
    </div>
</div>

<div id="lost-items" class="tab-content" style="display:none;">
    <h3>Lost Items Inventory</h3>
    <div style="max-height: 350px; overflow-y: auto; margin-bottom: 15px;">
        <table class="table table-bordered mb-4">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Timestamp Found</th>
                    <th>Reported By</th>
                    <th>Time Claimed</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = mysqli_query($conn, "SELECT * FROM lost_items");
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>" . $row['id'] . "</td>
                        <td>" . $row['item_name'] . "</td>
                        <td>" . $row['category'] . "</td>
                        <td>" . $row['timestamp_found'] . "</td>
                        <td>" . $row['reported_by'] . "</td>
                        <td>" . $row['time_claimed'] . "</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <h4>Lost Items by Category</h4>
    <div style="max-height: 400px; overflow-y: auto;">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Item Count</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = mysqli_query($conn, "SELECT category, COUNT(*) as count FROM lost_items GROUP BY category");
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>" . $row['category'] . "</td>
                        <td>" . $row['count'] . "</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<div id="report-log" class="tab-content" style="display:none;">
    <h3>Report Log</h3>
    <div style="max-height: 600px; overflow-y: auto;">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Timestamp Found</th>
                    <th>Reported By</th>
                    <th>Time Claimed</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = mysqli_query($conn, "SELECT * FROM lost_items ORDER BY timestamp_found DESC");
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>" . $row['id'] . "</td>
                        <td>" . $row['item_name'] . "</td>
                        <td>" . $row['category'] . "</td>
                        <td>" . $row['timestamp_found'] . "</td>
                        <td>" . $row['reported_by'] . "</td>
                        <td>" . ($row['status'] == 'claimed' ? 'Claimed' : 'Unclaimed') . "</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

    <!-- Modal to Add New Lost Item -->
<div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addItemModalLabel">Add Lost Item</h5>
                <button type="button" class="btn-close" id="btn-exit" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="add-item.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="itemName" class="form-label">Item Name</label>
                        <input type="text" class="form-control" id="itemName" name="item_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <input type="text" class="form-control" id="category" name="category" required>
                    </div>
                    <div class="mb-3">
                        <label for="timestampFound" class="form-label">Timestamp Found</label>
                        <input type="datetime-local" class="form-control" id="timestampFound" name="timestamp_found" required>
                    </div>
                    <div class="mb-3">
                        <label for="reportedBy" class="form-label">Reported By</label>
                        <input type="text" class="form-control" id="reportedBy" name="reported_by" required>
                    </div>
                    <div class="mb-3">
                        <label for="itemImage" class="form-label">Item Image</label>
                        <input type="file" class="form-control" id="itemImage" name="item_image">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Item</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for claiming an item -->
<div class="modal fade" id="claimModal" tabindex="-1" aria-labelledby="claimModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="claimModalLabel">Claim Item</h5>
                <button type="button" class="btn-close" id="btn-exit" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="claimForm" action="claim-item.php" method="POST">
                    <input type="hidden" id="itemId" name="id">
                    <div class="mb-3">
                        <label for="timeClaimed" class="form-label">Time Claimed</label>
                        <input type="datetime-local" class="form-control" id="timeClaimed" name="time_claimed" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Claim Item</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- modal for image preview -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imagePreviewModalLabel">Image Preview</h5>
                <button type="button" class="btn-close" id="btn-exit" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="previewImage" src="" alt="Preview" style="max-width: 100%; height: auto;">
            </div>
        </div>
    </div>
</div>


    <!-- Bootstrap 5 JS, Popper, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script src="../scripts/admin-portal.js"></script> <!-- External JS -->
</body>
</html>
