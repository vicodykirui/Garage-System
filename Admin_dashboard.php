<?php
session_start();

// Check if the user is an admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    // Redirect to login page if not an admin
    header("Location: login.html");
    exit();
}

include "db_connect.php"; // Include your database connection

// Fetch total vehicles, users, and service bookings
$vehicles_query = "SELECT COUNT(*) as total_vehicles FROM vehicles";
$vehicles_result = $conn->query($vehicles_query);
$vehicles_data = $vehicles_result->fetch_assoc();

$users_query = "SELECT COUNT(*) as total_users FROM users";
$users_result = $conn->query($users_query);
$users_data = $users_result->fetch_assoc();

$bookings_query = "SELECT COUNT(*) as total_bookings FROM service_bookings";
$bookings_result = $conn->query($bookings_query);
$bookings_data = $bookings_result->fetch_assoc();

// Fetch service history
$service_history_query = "SELECT * FROM service_bookings ORDER BY booking_date DESC";
$service_history_result = $aconn->query($service_history_query);

// Fetch payment history
$payment_history_query = "SELECT * FROM payment_history ORDER BY payment_date DESC";
$payment_history_result = $conn->query($payment_history_query);

// Fetch upcoming services or special offers
$upcoming_services_query = "SELECT * FROM special_offers WHERE offer_end_date >= CURDATE() ORDER BY offer_start_date ASC";
$upcoming_services_result = $conn->query($upcoming_services_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garage Admin Dashboard</title>
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
                <li class="nav-item"><a class="nav-link active" href="#">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Profile</a></li>
                <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="row">
        <!-- Dashboard Summary -->
        <div class="col-md-4">
            <div class="card p-3 shadow">
                <h4>Total Vehicles: <?= $vehicles_data['total_vehicles']; ?></h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 shadow">
                <h4>Total Users: <?= $users_data['total_users']; ?></h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 shadow">
                <h4>Total Service Bookings: <?= $bookings_data['total_bookings']; ?></h4>
            </div>
        </div>
    </div>

    <!-- Vehicle Management Section -->
    <div class="card p-4 shadow mt-4">
        <h2 class="text-center">Vehicle Management</h2>
        <a href="add_vehicle.php" class="btn btn-success mb-3">Add New Vehicle</a>

        <!-- View Vehicles -->
        <h3>All Vehicles</h3>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Vehicle ID</th>
                    <th>Plate Number</th>
                    <th>Model</th>
                    <th>Make</th>
                    <th>Year</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $vehicles_query = "SELECT * FROM vehicles";
                $vehicles_result = $conn->query($vehicles_query);
                while ($vehicle = $vehicles_result->fetch_assoc()) :
                ?>
                    <tr>
                        <td><?= htmlspecialchars($vehicle['id']); ?></td>
                        <td><?= htmlspecialchars($vehicle['plate_number']); ?></td>
                        <td><?= htmlspecialchars($vehicle['model']); ?></td>
                        <td><?= htmlspecialchars($vehicle['make']); ?></td>
                        <td><?= htmlspecialchars($vehicle['year']); ?></td>
                        <td>
                            <a href="edit_vehicle.php?id=<?= $vehicle['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_vehicle.php?id=<?= $vehicle['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Service Booking Management Section -->
    <div class="card p-4 shadow mt-4">
        <h2 class="text-center">Service Bookings</h2>
        <a href="add_services.php" class="btn btn-success mb-3">Add New services</a>

        <h3>All Service Bookings</h3>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Booking ID</th>
                    <th>Vehicle Plate</th>
                    <th>Service Type</th>
                    <th>Booking Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($booking = $service_history_result->fetch_assoc()) :
                ?>
                    <tr>
                        <td><?= htmlspecialchars($booking['id']); ?></td>
                        <td><?= htmlspecialchars($booking['vehicle_plate']); ?></td>
                        <td><?= htmlspecialchars($booking['service_type']); ?></td>
                        <td><?= htmlspecialchars($booking['booking_date']); ?></td>
                        <td><?= htmlspecialchars($booking['status']); ?></td>
                        <td>
                            <a href="view_booking.php?id=<?= $booking['id']; ?>" class="btn btn-info btn-sm">View</a>
                            <a href="delete_booking.php?id=<?= $booking['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Payment History Section -->
    <div class="card p-4 shadow mt-4">
        <h2 class="text-center">Payment History</h2>
        <a href="add_payment.php" class="btn btn-success mb-3">Add payment</a>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Payment ID</th>
                    <th>Customer Name</th>
                    <th>Amount</th>
                    <th>Payment Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($payment = $payment_history_result->fetch_assoc()) :
                ?>
                    <tr>
                        <td><?= htmlspecialchars($payment['id']); ?></td>
                        <td><?= htmlspecialchars($payment['customer_name']); ?></td>
                        <td><?= htmlspecialchars($payment['amount']); ?></td>
                        <td><?= htmlspecialchars($payment['payment_date']); ?></td>
                        <td><?= htmlspecialchars($payment['status']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Upcoming Services or Special Offers Section -->
    <div class="card p-4 shadow mt-4">
        <h2 class="text-center">Upcoming Services & Special Offers</h2>
        <a href="add_upcomingoffers.php" class="btn btn-success mb-3">Add upcoming offers</a>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Offer ID</th>
                    <th>Offer Title</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($offer = $upcoming_services_result->fetch_assoc()) :
                ?>
                    <tr>
                        <td><?= htmlspecialchars($offer['id']); ?></td>
                        <td><?= htmlspecialchars($offer['offer_title']); ?></td>
                        <td><?= htmlspecialchars($offer['offer_start_date']); ?></td>
                        <td><?= htmlspecialchars($offer['offer_end_date']); ?></td>
                        <td><?= htmlspecialchars($offer['offer_details']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
