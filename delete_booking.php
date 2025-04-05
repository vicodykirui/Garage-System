<?php
session_start();
include 'db_connect.php'; // Include database connection

// Check if user is logged in as customer or admin
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if booking_id is provided in URL
if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];
    
    // Check if the booking belongs to the current user (for customers) or admin
    if ($_SESSION['user_role'] === 'customer') {
        // If user is a customer, check if the booking belongs to the logged-in customer
        $check_sql = "SELECT * FROM service_bookings WHERE id = '$booking_id' AND user_id = '$user_id'";
        $check_result = $conn->query($check_sql);
        
        if ($check_result->num_rows == 0) {
            echo "You are not authorized to delete this booking.";
            exit();
        }
    }
    
    // Proceed to delete the booking
    $delete_sql = "DELETE FROM service_bookings WHERE id = '$booking_id'";
    if ($conn->query($delete_sql)) {
        echo "Booking deleted successfully.";
        header("Location: customer_dashboard.php"); // Redirect back to the customer dashboard or admin dashboard
        exit();
    } else {
        echo "Error: Could not delete booking. Please try again later.";
    }
} else {
    echo "Error: Booking ID is required.";
}

$conn->close(); // Close the database connection
?>
