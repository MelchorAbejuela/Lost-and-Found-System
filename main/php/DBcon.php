<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = mysqli_connect("localhost", "root", "", "user_portal");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}