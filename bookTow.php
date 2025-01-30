<?php
// Include database connection
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $vehicleType = $_POST['vehicleType'];
    $licensePlate = $_POST['licensePlate'];
    $location = $_POST['location'];
    $description = $_POST['description'];

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO bookings (name, phone, email, vehicleType, licensePlate, location, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $phone, $email, $vehicleType, $licensePlate, $location, $description);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to confirmation page or show success message
        header("Location: confirmation.php?status=accepted");
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
    <title>Book Tow Service</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .dropdown {
            display: none; /* Hidden by default */
            position: absolute; /* Position it below the user icon */
            right: 0; /* Align to the right */
            background-color: white; /* Background color for dropdown */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Shadow for dropdown */
            border-radius: 5px; /* Rounded corners */
            z-index: 1; /* Ensure it appears above other elements */
        }

        .user-icon:hover .dropdown {
            display: block; /* Show dropdown on hover */
        }

        .dropdown button {
            padding: 10px; /* Padding for button */
            border: none; /* No border */
            background-color: #007bff; /* Button background color */
            color: white; /* Button text color */
            cursor: pointer; /* Pointer cursor on hover */
            width: 100%; /* Full width */
            text-align: left; /* Align text to the left */
        }

        .dropdown button:hover {
            background-color: #0056b3; /* Darker background on hover */
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo"><span class="bold">E-</span>Tow</div>
            <ul>
                <li><a href="mainpage.php">About Us</a></li>
                <li><a href="service.php">Services</a></li>
                <li><a href="booklogs.php">Booking Logs</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
        <div class="user-icon">
            <img src="pics/profile.png" alt="User Icon" class="user-image">
            <div class="dropdown">
                <button onclick="logout()">Logout</button>
            </div>
        </div>
    </header>
    <main>
        <div id="content">
            <div class="containerForm">
                <h2>Book a Tow Service</h2>
                <form id="towBookingForm" method="POST" action="">
                    <label for="name">Full Name:</label>
                    <input type="text" id="name" name="name" required>

                    <label for="phone">Phone Number:</label>
                    <input type="tel" id="phone" name="phone" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="vehicleType">Vehicle Type:</label>
                    <input type="text" id="vehicleType" name="vehicleType" required>

                    <label for="licensePlate">License Plate:</label>
                    <input type="text" id="licensePlate" name="licensePlate" required>

                    <label for="location">Location for Tow Service:</label>
                    <input type="text" id="location" name="location" required>

                    <label for="description">Additional Details:</label>
                    <textarea id="description" name="description" rows="4" placeholder="Any additional information or special requests..."></textarea>

                    <button type="submit">Book Now</button>
                </form>
            </div>
        </div>
    </main>
    <script>
        function logout() {
            window.location.href = 'index.php'; // Redirect to index.php on logout
        }
    </script>
</body>
</html>