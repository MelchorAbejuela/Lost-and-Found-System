<?php
require './DBcon.php';

if (isset($_POST["submit"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmpassword = $_POST["confirmpassword"]; 

    $duplicate = mysqli_query($conn, "SELECT * FROM registration WHERE email = '$email'");

    if (!$duplicate) {
        die("Query failed: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($duplicate) > 0) {
        echo "<script> alert (' Email has already taken'); </script>";
    } else {
        if ($password == $confirmpassword) {
            $query = "INSERT INTO tb_user (, email, password) VALUES ( '$email', '$password')";

            if (mysqli_query($conn, $query)) {
                echo "<script> alert ('Register Successful'); window.location.href = 'index.php';  </script>";
            } else {
                echo "<script> alert ('Error: " . mysqli_error($conn) . "'); </script>";
            }
        } else {
            echo "<script> alert ('Password does not match'); </script>";
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
      <img src="" alt="">
      <h1>Lost and Found System</h1>
    </div>
    <div class="right-side">
      <h1>Register as User</h1>
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
          <input type="email" name="email" placeholder="Email" required>
          <input type="password" name="password" placeholder="Password" required>
          <input type="password" name="password" placeholder="Comfirm password">
          <button type="submit" name="submit" class="button">Register</button>
        </form>
        <p>Already have account?<a href="login-user.php">Sign in here.</a></p>
      </div>
    </div>

  </div>
</body>

</html>