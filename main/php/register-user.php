<?php
require './DBcon.php';

if (isset($_POST["submit"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmpassword = $_POST["confirmpassword"];

    // Check for duplicate email in registration
    $stmt = $conn->prepare("SELECT * FROM registration WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Email is already registered.');</script>";
    } else {
        if ($password === $confirmpassword) {
            // Insert into registration table
            $stmt = $conn->prepare("INSERT INTO registration (email, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $email, $password);

            if ($stmt->execute()) {
                // Insert into login_users table
                $stmt = $conn->prepare("INSERT INTO login_users (email, password) VALUES (?, ?)");
                $stmt->bind_param("ss", $email, $password);
                $stmt->execute();

                echo "<script>alert('Registration successful!'); window.location.href = 'login-user.php';</script>";
            } else {
                echo "<script>alert('Error: Unable to register.');</script>";
            }
        } else {
            echo "<script>alert('Passwords do not match.');</script>";
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
    <title>Register Admin</title>
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
                <form class="login" action="register-admin.php" method="POST">
                    <input type="text" name="admin" placeholder="Admin Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="password" name="confirmPassword" placeholder="Confirm Password" required>
                    <button type="submit" class="button">Register</button>
                </form>
                <p>Already have an account? <a href="login-admin.php">Sign in here.</a></p>
            </div>
        </div>
    </div>
</body>

</html>
