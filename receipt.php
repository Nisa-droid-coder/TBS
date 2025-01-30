<?php
// Include database connection
include 'db.php';

// Initialize variables
$bookingId = $_GET['booking_id'] ?? '';
$amount = $_GET['amount'] ?? 0;

// Fetch booking details based on booking_id
$bookingDetails = [];
if ($bookingId) {
    $stmt = $conn->prepare("SELECT b.name, b.phone, b.email, b.vehicleType, b.licensePlate, 
                                    b.location, b.description, iv.insurance_status, 
                                    mj.job_status, mj.estimated_arrival 
                             FROM bookings b 
                             LEFT JOIN insurance_verification iv ON b.book_id = iv.book_id 
                             LEFT JOIN manageJob mj ON b.book_id = mj.book_id 
                             WHERE b.book_id = ?");
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();
    $result = $stmt->get_result();
    $bookingDetails = $result->fetch_assoc();
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
    <title>Receipt - E-Tow</title>
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
            <img src="pics/profile.png" alt="User  Icon" class="user-image">
            <div class="dropdown">
                <button onclick="logout()">Logout</button>
            </div>
        </div>
    </header>
    <main>
        <div id="content">
            <div class="container">
                <h2>Payment Receipt</h2>
                <?php if ($bookingDetails): ?>
                    <h3>Booking Details</h3>
                    <p><strong>Booking ID:</strong> <?php echo htmlspecialchars($bookingId); ?></p>
                    <p><strong>Full Name:</strong> <?php echo htmlspecialchars($bookingDetails['name']); ?></p>
                    <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($bookingDetails['phone']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($bookingDetails['email']); ?></p>
                    <p><strong>Vehicle Type:</strong> <?php echo htmlspecialchars($bookingDetails['vehicleType']); ?></p>
                    <p><strong>License Plate:</strong> <?php echo htmlspecialchars($bookingDetails['licensePlate']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($bookingDetails['location']); ?></p>
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($bookingDetails['description']); ?></p>
                    <p><strong>Insurance Status:</strong> <?php echo htmlspecialchars($bookingDetails['insurance_status']); ?></p>
                    <p><strong>Job Status:</strong> <?php echo htmlspecialchars($bookingDetails['job_status']); ?></p>
                    <p><strong>Estimated Arrival:</strong> <?php echo htmlspecialchars($bookingDetails['estimated_arrival']); ?></p>
                    <p><strong>Amount Paid:</strong> $<?php echo htmlspecialchars($amount); ?></p>
                <?php else: ?>
                    <p>No booking details found for this receipt.</p>
                <?php endif; ?>
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