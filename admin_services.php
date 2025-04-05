<?php
// Start session to check if user is logged in as admin
session_start();

// Include the database connection
include 'db_connect.php';

// Check if user is logged in and has admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Add new service logic
if (isset($_POST['add_service'])) {
    $service_name = $_POST['service_name'];
    $description = $_POST['description'];

    // Insert the new service into the database
    $sql = "INSERT INTO services (service_name, description) VALUES ('$service_name', '$description')";
    if ($conn->query($sql) === TRUE) {
        echo "New service added successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Delete service logic
if (isset($_GET['delete_service'])) {
    $service_id = $_GET['delete_service'];

    // Delete the service from the database
    $sql = "DELETE FROM services WHERE id = '$service_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Service deleted successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Fetch all services for displaying
$sql = "SELECT * FROM services";
$result = $conn->query($sql);

// Display services
echo "<h2>Manage Services</h2>";
echo "<table class='table'>";
echo "<thead><tr><th>ID</th><th>Service Name</th><th>Description</th><th>Actions</th></tr></thead><tbody>";
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . $row['service_name'] . "</td>";
    echo "<td>" . $row['description'] . "</td>";
    echo "<td><a href='?delete_service=" . $row['id'] . "' class='btn btn-danger'>Delete</a></td>";
    echo "</tr>";
}
echo "</tbody></table>";

$conn->close();
?>

<!-- Form to add new service -->
<h3>Add New Service</h3>
<form action="admin_services.php" method="POST">
    <div class="mb-3">
        <label for="service_name" class="form-label">Service Name</label>
        <input type="text" class="form-control" id="service_name" name="service_name" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" required></textarea>
    </div>
    <button type="submit" name="add_service" class="btn btn-primary">Add Service</button>
</form>
