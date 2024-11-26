<?php 
session_start(); 
require 'DBcon.php';  

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
            header("Location: admin-portal.php");
            exit();         
        } else {             
            $_SESSION['error'] = 'Incorrect password.';
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();         
        }     
    } else {         
        $_SESSION['error'] = 'Admin not registered.';
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();     
    }      

    $stmt->close();     
    $conn->close(); 
} 

?>

<?php 
if(isset($_SESSION['error'])) {
    echo "<div class='error'>" . $_SESSION['error'] . "</div>";
    unset($_SESSION['error']);
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
            <img src="../img/LAF-LOGO.png" alt="">
            <h1>Lost and Found System</h1>
        </div>
        <div class="right-side">
            <a href="login-user.php">Login as User</a>
        </div>
    </nav>

    <div class="main">

        <div class="lost-found-theme">
            <video src="../img/LAF-LOGO.mp4" autoplay loop muted></video>
        </div>

        <div class="login-container">
            <div class="first-block">
                <img src="../img/user.png" alt="">
                <h1>Admin</h1>

                <form class="login" action="login-admin.php" method="POST">
                    <input type="text" name="admin" placeholder="Admin Username" required>
                    <div class="password-container">
                        <input type="password" name="password" placeholder="Password" required>
                        <span class="password-toggle" onclick="togglePassword(this)">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="eye-icon">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </span>
                    </div>
                    <button type="submit" class="button">Login</button>
                </form>

                <p>No account yet? <a id="register-link" href="register-admin.php">Register here.</a></p>
            </div>
        </div>
    </div>
</body>

</html>

<script>
function togglePassword(element) {
    const passwordInput = element.previousElementSibling;
    const type = passwordInput.getAttribute('type');
    passwordInput.setAttribute('type', type === 'password' ? 'text' : 'password');
    element.classList.toggle('show-password');
}
</script>