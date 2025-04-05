<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.html");
    exit();
}

include "db_connect.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $offer_title = $_POST['offer_title'];
    $offer_description = $_POST['offer_description'];
    $offer_start_date = $_POST['offer_start_date'];
    $offer_end_date = $_POST['offer_end_date'];
    $discount_percentage = $_POST['discount_percentage'];
    // Insert special offer record
    $sql = "INSERT INTO special_offers (offer_title, offer_description, offer_start_date, offer_end_date, discount_percentage) 
            VALUES ('$offer_title', '$offer_description', '$offer_start_date', '$offer_end_date', '$discount_percentage')";
    if ($conn->query($sql) === TRUE) {
        echo "Special offer added successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Special Offer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Garage System - Admin</a>
</nav>

<div class="container mt-5">
    <h2>Add Special Offer</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="offer_title" class="form-label">Offer Title</label>
            <input type="text" class="form-control" id="offer_title" name="offer_title" required>
        </div>
        <div class="mb-3">
            <label for="offer_description" class="form-label">Offer Description</label>
            <textarea class="form-control" id="offer_description" name="offer_description" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="offer_start_date" class="form-label">Start Date</label>
            <input type="date" class="form-control" id="offer_start_date" name="offer_start_date" required>
        </div>
        <div class="mb-3">
            <label for="offer_end_date" class="form-label">End Date</label>
            <input type="date" class="form-control" id="offer_end_date" name="offer_end_date" required>
        </div>
        <div class="mb-3">
            <label for="discount_percentage" class="form-label">Discount Percentage</label>
            <input type="number" class="form-control" id="discount_percentage" name="discount_percentage" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Offer</button>
    </form>
</div>

</body>
</html>
