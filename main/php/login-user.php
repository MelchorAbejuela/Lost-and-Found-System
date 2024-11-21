<?php
session_start();
require './DBcon.php';

if (isset($_POST["submit"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Query the login_users table for the user
    $stmt = $conn->prepare("SELECT * FROM login_users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if ($password === $row["password"]) {
            $_SESSION["login"] = true;
            $_SESSION["id"] = $row["id"];
            
            // Success alert and redirect
            echo "<script>
                    alert('Login successful! Redirecting to your portal...');
                    window.location.href = 'user-portal.php';
                  </script>";
            exit();
        } else {
            echo "<script>alert('Incorrect password.');</script>";
        }
    } else {
        echo "<script>alert('Email is not registered.');</script>";
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
      <link rel="stylesheet" href="../css/login-user.css">
      <title>OCC Lost and Found System</title>
    </head>

<body>
      <nav class="navbar">
        <div class="left-side">
          <img src="" alt="">
          <h1>Lost and Found System</h1>
        </div>
        <div class="right-side">
          <a href="./login-admin.php">Login as Admin</a>
        </div>
      </nav>

      <div class="main">

        <div class="login-container">
          <div class="first-block">
            <img src="../img/user.png" alt="">
            <h1>User</h1>
            <form class="login" action="login-user.php" method="POST">
              <input type="email" name="email" placeholder="Email" required>
              <input type="password" name="password" placeholder="Password" required>
              <button type="submit" name="submit" class="button">Login</button>
            </form>
            <p>No account yet? <a href="register-user.php">Register here.</a></p>
          </div>
        </div>

        <div class="lost-found-theme">
          <img src="../img/landing-page-image.png" alt="">
        </div>

  </div>
</body>

</html>