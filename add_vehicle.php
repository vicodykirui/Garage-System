<?php
session_start();

// Check if the user is an admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.html");
    exit();
}

include "db_connect.php"; // Include your database connection

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $user_id = $_POST['user_id'];
    $plate_number = $_POST['plate_number'];
    $model = $_POST['model'];
    $make = $_POST['make'];
    $year = $_POST['year'];

    // Prepare the SQL query to insert the vehicle
    $query = "INSERT INTO vehicles (user_id, plate_number, model, make, year) 
              VALUES (?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("isssi", $user_id, $plate_number, $model, $make, $year);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $message = "Vehicle added successfully!";
        } else {
            $message = "Failed to add vehicle.";
        }
        $stmt->close();
    } else {
        $message = "Error preparing query: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Vehicle</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Garage System - Admin</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <h2 class="text-center">Add Vehicle</h2>

        <?php if (isset($message)) : ?>
            <div class="alert alert-info"><?= $message; ?></div>
        <?php endif; ?>

        <form action="add_vehicle.php" method="POST">
            <div class="mb-3">
                <label for="user_id" class="form-label">Customer</label>
                <select class="form-select" id="user_id" name="user_id" required>
                    <option value="">Select a customer</option>
                    <?php
                    // Fetch all users (customers)
                    $query = "SELECT * FROM users WHERE role = 'customer'";
                    $result = $conn->query($query);
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="plate_number" class="form-label">Plate Number</label>
                <input type="text" class="form-control" id="plate_number" name="plate_number" required>
            </div>

            <div class="mb-3">
                <label for="model" class="form-label">Model</label>
                <input type="text" class="form-control" id="model" name="model" required>
            </div>

            <div class="mb-3">
                <label for="make" class="form-label">Make</label>
                <input type="text" class="form-control" id="make" name="make" required>
            </div>

            <div class="mb-3">
                <label for="year" class="form-label">Year</label>
                <input type="number" class="form-control" id="year" name="year" min="1900" max="2100" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Add Vehicle</button>
        </form>
    </div>
</div>

</body>
</html>
