<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Service</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('book.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            font-family: Arial, sans-serif;
        }
        .container {
            background: rgba(0, 0, 0, 0.6);
            padding: 20px;
            border-radius: 10px;
            width: 350px;
            color: white;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h2 class="text-center">Book a Service</h2>
            
            <!-- Form to Add Vehicle and Book Service -->
            <form action="book_service.php" method="POST">
                <?php
                // Start session and include the database connection
                session_start();
                include 'db_connect.php';  // Make sure your DB connection is correct

                // Check if the user is logged in
                if (!isset($_SESSION['user_id'])) {
                    echo "<p>You must be logged in to book a service.</p>";
                    exit();
                }

                $user_id = $_SESSION['user_id'];

                // Handle the form submission
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    // Get data from the form
                    $plate_number = $_POST['plate_number'];
                    $model = $_POST['model'];
                    $type = $_POST['type']; // Vehicle type (Car, Motorcycle, etc.)
                    $service_id = $_POST['service_id']; // Selected service
                    $service_date = $_POST['service_date'];

                    // Basic sanitization (to prevent SQL injection)
                    $plate_number = $conn->real_escape_string($plate_number);
                    $model = $conn->real_escape_string($model);
                    $type = $conn->real_escape_string($type);
                    $service_id = (int)$service_id; // Cast to integer
                    $service_date = $conn->real_escape_string($service_date);

                    // Insert vehicle data into the database
                    $sql = "INSERT INTO vehicles (user_id, plate_number, model, type) VALUES ('$user_id', '$plate_number', '$model', '$type')";
                    if ($conn->query($sql) === TRUE) {
                        $vehicle_id = $conn->insert_id; // Get the inserted vehicle ID

                        // Insert service booking for the new vehicle
                        $sql_service = "INSERT INTO service_bookings (vehicle_id, service_id, service_date) VALUES ('$vehicle_id', '$service_id', '$service_date')";
                        if ($conn->query($sql_service) === TRUE) {
                            echo "<p>Service booked successfully for your vehicle.</p>";
                        } else {
                            echo "<p>Error booking service: " . $conn->error . "</p>";
                        }
                    } else {
                        echo "<p>Error adding vehicle: " . $conn->error . "</p>";
                    }
                }
                ?>

                <!-- Vehicle Details Form -->
                <div class="mb-3">
                    <label for="plate_number" class="form-label">Plate Number</label>
                    <input type="text" class="form-control" id="plate_number" name="plate_number" required>
                </div>

                <div class="mb-3">
                    <label for="model" class="form-label">Model</label>
                    <input type="text" class="form-control" id="model" name="model" required>
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Vehicle Type</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="Car">Car</option>
                        <option value="Motorcycle">Motorcycle</option>
                        <option value="Truck">Truck</option>
                    </select>
                </div>

                <!-- Predefined Services (No database fetch) -->
                <div class="mb-3">
                    <label for="service_id" class="form-label">Select Service</label>
                    <select class="form-select" id="service_id" name="service_id" required>
                        <option value="1">Oil Change</option>
                        <option value="2">Tire Replacement</option>
                        <option value="3">Brake Inspection</option>
                        <option value="4">Engine Diagnostics</option>
                        <option value="5">AC Service</option>
                    </select>
                </div>

                <!-- Service Date -->
                <div class="mb-3">
                    <label for="service_date" class="form-label">Service Date</label>
                    <input type="date" class="form-control" id="service_date" name="service_date" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Book Service</button>
            </form>
        </div>
    </div>

</body>
</html>
