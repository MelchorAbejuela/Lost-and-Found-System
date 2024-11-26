<?php  
session_start();  
require 'DBcon.php';  

if (isset($_POST["submit"])) {     
    $email = $_POST["email"];     
    $password = $_POST["password"];          

    // First check the registration table     
    $stmt = $conn->prepare("SELECT * FROM registration WHERE email = ? AND password = ?");     
    $stmt->bind_param("ss", $email, $password);     
    $stmt->execute();     
    $result = $stmt->get_result();          

    if ($result->num_rows > 0) {         
        // User found in registration table         
        $row = $result->fetch_assoc();         
        $_SESSION["login"] = true;         
        $_SESSION["id"] = $row["id"];                  

        // Also ensure user exists in login_users table         
        $check_login = $conn->prepare("SELECT * FROM registration WHERE email = ?");         
        $check_login->bind_param("s", $email);         
        $check_login->execute();         
        $login_result = $check_login->get_result();                  

        if ($login_result->num_rows == 0) {             
            // If user doesn't exist in login_users, add them             
            $insert = $conn->prepare("INSERT INTO login_users (email, password) VALUES (?, ?)");             
            $insert->bind_param("ss", $email, $password);             
            $insert->execute();             
            $insert->close();         
        }         
        $check_login->close();                  

        // Set success message in session
        $_SESSION['success'] = 'Login successful! Redirecting to your portal...';
        
        // Redirect to user portal
        header("Location: user-portal.php");
        exit();     
    } else {         
        // Set error message in session
        $_SESSION['error'] = 'Invalid email or password.';
        
        // Redirect back to login page
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();     
    }          

    $stmt->close();     
    $conn->close(); 
} 

?>

<?php 
if(isset($_SESSION['success'])) {
    echo "<div class='success'>" . $_SESSION['success'] . "</div>";
    unset($_SESSION['success']);
}

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
    <link rel="stylesheet" href="../css/login-user.css">
    <title>OCC Lost and Found System</title>
</head>

<body>
    <nav class="navbar">
        <div class="left-side">
            <img src="../img/LAF-LOGO-2.png" alt="">
            <h1>Lost and Found System</h1>
        </div>
        <div class="right-side">
            <a href="login-admin.php">Login as Admin</a>
        </div>
    </nav>
    
    <div class="main">
        <div class="login-container">
            <div class="first-block">
                <img src="../img/user.png" alt="">
                <h1>User</h1>
        <form class="login" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
          <input type="email" name="email" placeholder="Email" required>
            <div class="password-container">
              <input type="password" name="password" placeholder="Password" required>
                <span class="password-toggle" onclick="togglePassword(this)">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="eye-icon">
                      <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                  </svg>
              </span>
            </div>
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

<script>
function togglePassword(element) {
    const passwordInput = element.previousElementSibling;
    const type = passwordInput.getAttribute('type');
    passwordInput.setAttribute('type', type === 'password' ? 'text' : 'password');
    element.classList.toggle('show-password');
}
</script>