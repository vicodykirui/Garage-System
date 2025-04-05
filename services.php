<?php
// Start session to access session variables
session_start();

// Include the database connection
include 'db_connect.php';

// Fetch all available services from the database
$sql = "SELECT * FROM services";
$result = $conn->query($sql);

// Check if services are available
if ($result->num_rows > 0) {
    echo "<h2>Avails</h2>";
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>" . $row['service_name'] . " - " . $row['description'] . "</li>";
    }
    echo "</ul>";
} else {
    echo "No services available.";
}

// Close the database connection
$conn->close();
?>
