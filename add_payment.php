<?php
session_start();
include 'db_connect.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize the form data
    $customer_name = $conn->real_escape_string($_POST['customer_name']); // Sanitize customer_name input
    $amount = $conn->real_escape_string($_POST['amount']); // Sanitize amount input
    $payment_method = $conn->real_escape_string($_POST['payment_method']); // Sanitize payment_method input

    // Prepare the SQL query to insert payment data into the payment_history table
    $query = "INSERT INTO payment_history (amount, payment_method, payment_date, status, customer_name) 
              VALUES ('$amount', '$payment_method', NOW(), 'Pending', '$customer_name')";

    // Execute the query
    if ($conn->query($query) === TRUE) {
        // Payment success message
        header("Location: customer_dashboard.php");
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Garage System</a>
</nav>

<div class="container mt-4">
    <h2>Add Payment</h2>
    <form action="add_payment.php" method="POST">
        <div class="mb-3">
            <label for="customer_name" class="form-label">Customer Name</label>
            <input type="text" class="form-control" id="customer_name" name="customer_name" required>
        </div>

        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="text" class="form-control" id="amount" name="amount" required>
        </div>

        <div class="mb-3">
            <label for="payment_method" class="form-label">Payment Method</label>
            <input type="text" class="form-control" id="payment_method" name="payment_method" required>
        </div>

        <button type="submit" class="btn btn-primary">Add Payment</button>
    </form>
</div>

</body>
</html>
