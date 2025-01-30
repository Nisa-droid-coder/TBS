<?php
// Include database connection
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $email = $_POST['regEmail'];
    $password = password_hash($_POST['regPassword'], PASSWORD_DEFAULT); // Hash the password
    $role = $_POST['role'];

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $password, $role);

    // Execute the statement
    if ($stmt->execute()) {
        // Registration successful, redirect to login page
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Tow Booking System - Register</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <main>
        <div id="content">
            <div class="container">
                <h2>Register</h2>
                <form id="regForm" method="POST" action="">
                    <label for="regEmail">Email:</label>
                    <input type="email" id="regEmail" name="regEmail" required>
                    <label for="regPassword">Password:</label>
                    <input type="password" id="regPassword" name="regPassword" required>
                    <label for="role">Role:</label>
                    <select id="role" name="role" required>
                        <option value="customer">Customer</option>
                        <option value="admin">Admin</option>
                        <option value="insuranceAgent">Insurance Agent</option>
                        <option value="serviceProvider">Service Provider</option>
                    </select>
                    <br><button type="submit">Register</button></br>
                </form>
            </div>
        </div>
    </main>
</body>
</html>