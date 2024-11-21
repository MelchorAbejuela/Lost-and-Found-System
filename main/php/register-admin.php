<?php 
require './DBcon.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["admin"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];

    // Check if username already exists in admin_registration table
    $stmt = $conn->prepare("SELECT * FROM admin_registration WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Username already exists.'); window.history.back();</script>";
    } else {
        if ($password === $confirmPassword) {
            // Insert into admin_registration table
            $stmt = $conn->prepare("INSERT INTO admin_registration (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $password);

            if ($stmt->execute()) {
                echo "<script>alert('Registration successful!'); window.location.href = 'login-admin.php';</script>";
            } else {
                echo "<script>alert('Error: Unable to register. Please try again later.');</script>";
            }
        } else {
            echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
        }
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
    <link rel="stylesheet" href="../css/register-admin.css">
    <title>OCC Lost and Found System</title>
</head>

<body>
    <nav class="navbar">
        <div class="left-side">
            <img src="" alt="">
            <h1>Lost and Found System</h1>
        </div>
        <div class="right-side">
            <a href="./register-user.php">Register as User</a>
        </div>
    </nav>

    <div class="main">

        <div class="lost-found-theme">
            <video src="../img/logo-vid.mp4" autoplay loop muted></video>
        </div>

        <div class="login-container">
            <div class="first-block">
                <img src="../img/user.png" alt="">
                <h1>Admin</h1>
                <form class="login" action="login-admin.php" method="POST">
                    <input type="text" name="admin" placeholder="Admin" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="password" name="comfirnpassword" placeholder="Confirm Password" required>
                    <button type="submit" class="button">Login</button>
                </form>
                <p>Already have an account? <a href="login-admin.php">Sign in here.</a></p>
            </div>
        </div>

    </div>
</body>

</html>