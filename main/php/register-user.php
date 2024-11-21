<?php
require 'DBcon.php';

if (isset($_POST["submit"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmpassword = $_POST["confirmpassword"];

    // Check for duplicate email
    $stmt = $conn->prepare("SELECT * FROM registration WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script> alert('Email has already been taken'); </script>";
    } else {
        if ($password === $confirmpassword) {
            // Insert plain-text password into the database
            $stmt = $conn->prepare("INSERT INTO registration (email, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $email, $password);

            if ($stmt->execute()) {
                echo "<script> alert('Registration Successful'); window.location.href = 'login-user.php'; </script>";





            } else {
                echo "<script> alert('Error: " . $stmt->error . "'); </script>";
            }
        } else {
            echo "<script> alert('Password does not match'); </script>";
        }
    }



}
?>





<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/register-user.css">
  <title>OCC Lost and Found System</title>
</head>

<body>
  <nav class="navbar">
    <div class="left-side">
      <img src="../img/LAF-LOGO-2.png" alt="">
      <h1>Lost and Found System</h1>
    </div>
    <div class="right-side">
      <a href="register-admin.php">Register as Admin</a>
    </div>
  </nav>

  <div class="main">
    <div class="lost-found-theme">
      <img src="../img/landing-page-image.png" alt="">
    </div>

    <div class="login-container">
      <div class="first-block">
        <img src="../img/user.png" alt="">
        <h1>User</h1>
        <form class="login" action="register-user.php" method="POST">
          <input type="email" name="email" placeholder="Email" required>
          <input type="password" name="password" placeholder="Password" required>
          <input type="password" name="confirmpassword" placeholder="Confirm Password" required>
          <button type="submit" name="submit" class="button">Register</button>
        </form>
        <p>Already have an account? <a href="login-user.php">Sign in here.</a></p>
      </div>

    </div>

  </div>
</body>

</html>