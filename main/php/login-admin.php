<?php
session_start();
require './DBcon.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["admin"];
    $password = $_POST["password"];

    // Query the admin_registration table for admin credentials
    $stmt = $conn->prepare("SELECT * FROM admin_registration WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if ($password === $row["password"]) {
            $_SESSION["admin_login"] = true;
            $_SESSION["id"] = $row["id"];
            
            // Redirect to admin dashboard
            echo "<script>
                    alert('Login successful! Redirecting to admin dashboard...');
                    window.location.href = 'admin-dashboard.php';
                  </script>";
        } else {
            echo "<script>alert('Incorrect password.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Admin not registered.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login-admin.css">
    <title>Admin Login</title>
</head>

<body>
    <nav class="navbar">
        <div class="left-side">
            <img src="" alt="">
            <h1>Lost and Found System</h1>
        </div>
        <div class="right-side">
            <a href="./login-user.php">Login as User</a>
        </div>
    </nav>

    <div class="main">
        <div class="login-container">
            <div class="first-block">
                <img src="../img/user.png" alt="">
                <h1>Admin</h1>
                <form class="login" action="login-admin.php" method="POST">
                    <input type="text" name="admin" placeholder="Admin Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit" class="button">Login</button>
                </form>
                <p>No account yet? <a href="register-admin.php">Register here.</a></p>
            </div>
        </div>
    </div>
</body>

</html>
