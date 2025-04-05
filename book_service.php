<?php
// Start session to access session variables (user data)
session_start();
// Include database connection
include 'db_connect.php';

// Check if user is logged in (user_id should be set in session)
if (!isset($_SESSION['user_id'])) {
    // If user is not logged in, redirect to login page
    header("Location: login.php");
    exit();
}
// Get user ID from session
$user_id = $_SESSION['user_id'];
// Get form data from POST request
$vehicle_id = $_POST['vehicle_id'];
$service_id = $_POST['service_id'];
$service_date = $_POST['service_date'];

// Validate form data (basic validation)
if (empty($vehicle_id) || empty($service_id) || empty($service_date)) {
    echo "All fields are required!";
    exit();
}
// Prepare SQL query to insert the booking into the database
$sql = "INSERT INTO bookings (user_id, vehicle_id, service_id, booking_date, service_date, status)
        VALUES ('$user_id', '$vehicle_id', '$service_id', CURDATE(), '$service_date', 'Pending')";
// Execute the query
if ($conn->query($sql) === TRUE) {
    // If insertion is successful, display success message
    echo "Booking successful! <a href='dashboard.php'>Go back to Dashboard</a>";
} else {
    // If there's an error, display the error message
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the database connection
$conn->close();
?>
