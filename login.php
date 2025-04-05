<?php
session_start();
include 'db_connect.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check the user's credentials
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: admin_dashboard.php");
                exit();
            } elseif ($user['role'] === 'customer') {
                header("Location: customer_dashboard.php"); // Correct redirect for customer
                exit();
            } else {
                echo "Invalid role.";
            }
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "No user found with that email!";
    }
}
?>

