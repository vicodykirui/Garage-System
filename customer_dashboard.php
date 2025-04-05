<?php
session_start();
include 'db_connect.php';
// Check if customer is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: login.php"); // Redirect to login if not logged in as customer
    exit();
}
$user_id = $_SESSION['user_id'];
// Fetch customer details
$customer_sql = "SELECT * FROM users WHERE id = '$user_id'";
$customer_result = $conn->query($customer_sql);
// Check if customer exists
if ($customer_result->num_rows == 0) {
    echo "Customer not found.";
    exit();
}
$customer = $customer_result->fetch_assoc();
// Fetch vehicle information
$vehicle_sql = "SELECT * FROM vehicles WHERE user_id = '$user_id'";
$vehicle_result = $conn->query($vehicle_sql);

// Fetch service history
$service_sql = "SELECT * FROM service_history WHERE user_id = '$user_id' ORDER BY service_date DESC";
$service_result = $conn->query($service_sql);

// Fetch payment history
$payment_sql = "SELECT * FROM payment_history WHERE user_id = '$user_id' ORDER BY payment_date DESC";
$payment_result = $conn->query($payment_sql);

// Fetch notifications & alerts
$notification_sql = "SELECT * FROM notifications WHERE user_id = '$user_id' ORDER BY date_sent DESC";
$notification_result = $conn->query($notification_sql);

// Fetch upcoming services or special offers
$upcoming_service_sql = "SELECT * FROM upcoming_services WHERE user_id = '$user_id' ORDER BY offer_valid_until DESC";
$upcoming_service_result = $conn->query($upcoming_service_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <span class="navbar-brand">Customer Dashboard</span>
    <div class="ms-auto">
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</nav>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm p-3">
                <h4 class="text-primary">👤 Profile Info</h4>
                <p><strong>Name:</strong> <?= htmlspecialchars($customer['name']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($customer['email']) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($customer['phone']) ?></p>
                <p><strong>Membership:</strong> <?= htmlspecialchars($customer['membership_start']) ?> → <?= htmlspecialchars($customer['membership_end']) ?></p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm p-3">
                <h4 class="text-success">🚗 Vehicle Information</h4>
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Model</th>
                            <th>Number</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($vehicle_result->num_rows > 0): ?>
                            <?php while($row = $vehicle_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['vehicle_model']) ?></td>
                                    <td><?= htmlspecialchars($row['vehicle_number']) ?></td>
                                    <td><?= htmlspecialchars($row['vehicle_type']) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center">No vehicles found</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm p-3">
                <h4 class="text-info">🛠 Service History</h4>
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Service</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($service_result->num_rows > 0): ?>
                            <?php while($row = $service_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['service_name']) ?></td>
                                    <td><?= htmlspecialchars($row['service_date']) ?></td>
                                    <td><?= htmlspecialchars($row['service_status']) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center">No service history found</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm p-3">
                <h4 class="text-warning">💳 Payment History</h4>
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($payment_result->num_rows > 0): ?>
                            <?php while($row = $payment_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['amount']) ?></td>
                                    <td><?= htmlspecialchars($row['payment_date']) ?></td>
                                    <td><?= htmlspecialchars($row['status']) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center">No payment history found</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm p-3">
                <h4 class="text-success">📢 Notifications & Alerts</h4>
                <ul class="list-group">
                    <?php if ($notification_result->num_rows > 0): ?>
                        <?php while($row = $notification_result->fetch_assoc()): ?>
                            <li class="list-group-item"><?= htmlspecialchars($row['message']) ?> <small class="text-muted"><?= htmlspecialchars($row['date_sent']) ?></small></li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li class="list-group-item">No notifications</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm p-3">
                <h4 class="text-danger">🎁 Upcoming Services & Special Offers</h4>
                <ul class="list-group">
                    <?php if ($upcoming_service_result->num_rows > 0): ?>
                        <?php while($row = $upcoming_service_result->fetch_assoc()): ?>
                            <li class="list-group-item">
                                <strong><?= htmlspecialchars($row['offer_title']) ?></strong><br>
                                <?= htmlspecialchars($row['offer_details']) ?><br>
                                <small class="text-muted">Valid until: <?= htmlspecialchars($row['offer_valid_until']) ?></small>
                            </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li class="list-group-item">No upcoming offers</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

</body>
</html>
