<?php

include 'db_connect.php';

$feedback = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form inputs
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    // Check if all fields are filled
    if (!empty($name) && !empty($email) && !empty($message)) {
        // Prepare the SQL query to insert message into the database
        $sql = "INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Bind parameters to the query
        $stmt->bind_param("sss", $name, $email, $message);

        // Execute the query and check if it was successful
        if ($stmt->execute()) {
            $feedback = "<div class='alert alert-success'>Message sent successfully!</div>";
        } else {
            $feedback = "<div class='alert alert-danger'>Error sending message. Please try again.</div>";
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    } else {
        // Display a warning if any field is empty
        $feedback = "<div class='alert alert-warning'>All fields are required!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us - Veetech Garage</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f6fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            margin-top: 60px;
        }

        .contact-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            padding: 40px 30px;
        }

        h2 {
            color: #333;
            font-weight: 600;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 12px;
        }

        .form-control:focus {
            box-shadow: 0 0 5px #007bff;
        }

        .btn-primary {
            border-radius: 8px;
            padding: 12px;
            background-color: #007bff;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .info-section {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05);
        }

        .info-section p {
            margin-bottom: 12px;
        }

        .alert {
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .contact-card, .info-section {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">

        <!-- Contact Info Section -->
        <div class="col-md-5 mb-4">
            <div class="info-section">
                <h2>Contact Information</h2>
                <p><strong>Address:</strong><br>123 Nairobi City, 56789</p>
                <p><strong>Phone:</strong><br>+254720432805</p>
                <p><strong>Email:</strong><br>kiruivicody60@gmail.com</p>
                <p>Need help or have questions? Drop us a message and we'll get back to you!</p>
            </div>
        </div>

        <!-- Contact Form Section -->
        <div class="col-md-7">
            <div class="contact-card">
                <h2 class="mb-4">Send Us a Message</h2>

                <?= $feedback ?>

                <form action="contact_us.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" placeholder="Your name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Your email" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea class="form-control" name="message" rows="5" placeholder="Your message..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Send Message</button>
                </form>
            </div>
        </div>

    </div>
</div>


</body>
</html>
