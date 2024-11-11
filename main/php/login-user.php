<?php
require './DBcon.php';

if (isset($_POST["submit"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $result = mysqli_query($conn, "SELECT * FROM registration WHERE email = '$email'");
    $row = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) > 0) {
        if ($password == $row["password"]) {
            $_SESSION["login"] = true;
            $_SESSION["id"] = $row["id"];
            header("Location: user-portal.html");
            exit(); // Terminate the script 
        } else {
            echo "<script> alert ('Wrong Password'); </script>";
        }
    } else {
        echo "<script> alert ('User Not Registered'); </script>";
    }
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
      <h1>Login as User</h1>
    </div>
  </nav>

  <div class="main">

    <div class="lost-found-theme">
      <img src="../img/lost-found-theme.png" alt="">
    </div>

    <div class="login-container">
      <div class="first-block">
        <img src="../img/avatar-female.png" alt="">
        <h1>User</h1>
        <form class="login" action="userphase.html" method="POST">
          <input type="email" name="email" placeholder="Email" id="email" required>
          <input type="password" name="password" placeholder="Password" id="password" required>
          <a href="user-portal.php"><button type="submit" name="submmit" class="button">Login</button></a>
        </form>
        <p>No account yet? <a href="register-user.php">Register here.</a></p>
      </div>
    </div>

  </div>
</body>

</html>