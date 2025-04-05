<?php
$host = "localhost";       // Server hostname
$username = "root";        // Default username for XAMPP
$password = "";            // Empty password in XAMPP by default
$database = "garage_system";   // Your database name
// Create connection
$conn = new mysqli($host, $username, $password, $database);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
