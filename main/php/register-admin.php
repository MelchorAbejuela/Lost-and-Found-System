<?php
require 'DBcon.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["admin"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];
    
    // Basic input validation
    if (empty($username) || empty($password) || empty($confirmPassword)) {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
        exit;
    }

    // Check if username already exists in admin_registration table
    $stmt = $conn->prepare("SELECT * FROM admin_registration WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "<script>alert('Username already exists.'); window.history.back();</script>";
    } else {
        if ($password === $confirmPassword) {
            // Store the plain text password (not recommended for security)
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
            <img src="../img/LAF-LOGO.png" alt="">
            <h1>Lost and Found System</h1>
        </div>
        <div class="right-side">
            <a href="register-user.php">Register as User</a>
        </div>
    </nav>
    
    <div class="main">
        <div class="lost-found-theme">
            <video src="../img/LAF-LOGO.mp4" autoplay loop muted></video>
        </div>
        
        <div class="login-container">
            <div class="first-block">
                <img src="../img/user.png" alt="">
                <h1>Admin Registration</h1>

                <form class="login" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
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
                    <div class="password-container">
                        <input type="password" name="confirmPassword" placeholder="Confirm Password" required>
                        <span class="password-toggle" onclick="togglePassword(this)">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="eye-icon">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </span>
                    </div>
                    <button type="submit" class="button">Register</button>
                </form>

                <p>Already have an account? <a id="login-link" href="login-admin.php">Sign in here.</a></p>
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