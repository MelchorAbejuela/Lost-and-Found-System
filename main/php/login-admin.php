<?php
require './DBcon.php'; // Adjust the path as needed

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["admin"];
    $password = $_POST["password"];

    // Query to check for the admin credentials
    $query = "SELECT * FROM admins WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    $admin = mysqli_fetch_assoc($result);

    // Verify password
    if ($admin && $admin["password"] == $password) {
        // Start session and redirect to admin dashboard
        session_start();
        $_SESSION["admin_login"] = true;
        header("Location: admin-dashboard.php");
    } else {
        echo "<script>alert('Incorrect Username or Password');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/login-admin.css">
  <title>OCC Lost and Found System</title>
</head>

<body>
  <nav class="navbar">
    <div class="left-side">
      <img src="" alt="">
      <h1>Lost and Found System</h1>
    </div>
    <div class="right-side">
      <h1>Login as Admin</h1>
    </div>
  </nav>

  <div class="main">

    <div class="login-container">
      <div class="first-block">
        <img src="../img/avatar-male.png" alt="">
        <h1>Admin</h1>
        <form class="login" action="login-admin.php" method="GET">
          <input type="text" name="admin" placeholder="Admin" required>
          <input type="password" name="password" placeholder="Password" required>
          <button type="submit" class="button">Login</button>
        </form>
        <p>No account yet? <a href="">Register here.</a></p>
      </div>
    </div>

    <div class="lost-found-theme">
      <img src="../img/lost-found-theme.png" alt="">
    </div>

  </div>
</body>

</html>