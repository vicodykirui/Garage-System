<?php
session_start();

// Check if the user is an admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.html");
    exit();
}

include "db_connect.php"; // Include your database connection

// Fetch all bookings
$query = "SELECT b.id, u.name AS customer_name, v.plate_number, v.model, v.make, v.year, 
                 s.service_name, b.booking_date 
          FROM service_bookings b
          JOIN users u ON b.user_id = u.id
          JOIN vehicles v ON b.vehicle_id = v.id
          JOIN services s ON b.service_id = s.id";

$bookings = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bookings</title>
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
                <li class="nav-item"><a class="nav-link" href="add_vehicle.php">Add Vehicle</a></li>
                <li class="nav-item"><a class="nav-link" href="view_booking.php">View Bookings</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="card p-4 shadow">
        <h2 class="text-center">View Service Bookings</h2>

        <!-- Booking Records Table -->
        <table class="table table-bordered mt-4">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Customer Name</th>
                    <th>Vehicle Plate Number</th>
                    <th>Vehicle Model</th>
                    <th>Vehicle Make</th>
                    <th>Service Name</th>
                    <th>Booking Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $bookings->fetch_assoc()) : ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= $row['customer_name']; ?></td>
                        <td><?= $row['plate_number']; ?></td>
                        <td><?= $row['model']; ?></td>
                        <td><?= $row['make']; ?></td>
                        <td><?= $row['service_name']; ?></td>
                        <td><?= $row['booking_date']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
