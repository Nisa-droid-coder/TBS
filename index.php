<?php
// Include database connection
include 'db.php';

session_start(); // Start a session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    // Check if user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashedPassword, $role);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashedPassword)) {
            // Password is correct, set session variables
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;

            // Redirect based on user role
            switch ($role) {
                case 'customer':
                    header("Location: mainpage.php");
                    break;
                case 'serviceProvider':
                    header("Location: towserviceprovider.php");
                    break;
                case 'insuranceAgent':
                    header("Location: insuranceAgent.php");
                    break;
                case 'admin':
                    header("Location: admin.php");
                    break;
                default:
                    echo "Invalid role.";
                    exit();
            }
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that email.";
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
    <title>Mobile Tow Booking System - Login</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <h1>Mobile Tow Booking System</h1>
    </header>
    <main>
        <div id="content">
            <div class="container">
                <h2>Login</h2>
                <form id="loginForm" method="POST" action="">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <button type="submit">Login</button>
                    <div id="links">
                        <br>Forget Password?<a href="forgot_password.php">Click here</a><br>
                        <br>No account yet?<a href="register.php">Click here</a><br>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>