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
            <a href="./register-user.html">Register as User</a>
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
                <form class="login" action="login-admin.php" method="GET">
                    <input type="text" name="admin" placeholder="Admin" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="password" name="comfirnpassword" placeholder="Confirm Password" required>
                    <button type="submit" class="button">Login</button>
                </form>
                <p>Already have an account? <a href="login-admin.html">Sign in here.</a></p>
            </div>
        </div>

    </div>
</body>

</html>