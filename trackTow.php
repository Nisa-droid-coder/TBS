<?php
// Include database connection
include 'db.php';

// Initialize variables
$towProviderId = $_GET['tow_provider_id'] ?? '';
$bookingId = $_GET['booking_id'] ?? '';
$towProviderDetails = [];
$jobDetails = [];

// Fetch tow provider details based on tow_provider_id
if ($towProviderId) {
    $stmt = $conn->prepare("SELECT * FROM tow_providers WHERE tow_provider_id = ?");
    $stmt->bind_param("i", $towProviderId);
    $stmt->execute();
    $result = $stmt->get_result();
    $towProviderDetails = $result->fetch_assoc();
    $stmt->close();
}

// Fetch job details based on booking_id
if ($bookingId) {
    $stmt = $conn->prepare("SELECT mj.job_status, mj.estimated_arrival FROM manageJob mj WHERE mj.book_id = ?");
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();
    $result = $stmt->get_result();
    $jobDetails = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Your Tow Vehicle - E-Tow</title>
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
                <h2>Track Your Tow Vehicle</h2>
                <p>Enter your Tow Provider ID and Booking ID below:</p>
                <form id="trackingForm" method="GET" action="">
                    <label for="tow_provider_id">Tow Provider ID:</label>
                    <input type="number" id="tow_provider_id" name="tow_provider_id" required placeholder="Enter your Tow Provider ID" value="<?php echo htmlspecialchars($towProviderId); ?>">

                    <label for="booking_id">Booking ID:</label>
                    <input type="number" id="booking_id" name="booking_id" required placeholder="Enter your Booking ID" value="<?php echo htmlspecialchars($bookingId); ?>">

                    <button type="submit">Track Now</button>
                </form>

                <?php if ($towProviderDetails): ?>
                    <div id="trackingResult" style="margin-top: 20px;">
                        <h3>Tow Provider Details</h3>
                        <p><strong>Provider Name:</strong> <?php echo htmlspecialchars($towProviderDetails['provider_name']); ?></p>
                        <p><strong>Company Name:</strong> <?php echo htmlspecialchars($towProviderDetails['company_name']); ?></p>
                        <p><strong>Vehicle Plate:</strong> <?php echo htmlspecialchars($towProviderDetails['vehiclePlate']); ?></p>
                        <p><strong>Location:</strong> <?php echo htmlspecialchars($towProviderDetails['locationTow']); ?></p>
                        <p><strong>Time Available:</strong> <?php echo htmlspecialchars($towProviderDetails['timeAvailable']); ?></p>
                    </div>
                <?php elseif ($towProviderId): ?>
                    <p style="color: red;">No Tow Provider found for this ID.</p>
                <?php endif; ?>

                <?php if ($jobDetails): ?>
                    <div id="jobDetails" style="margin-top: 20px;">
                        <h3>Job Details</h3>
                        <p><strong>Job Status:</strong> <?php echo htmlspecialchars($jobDetails['job_status']); ?></p>
                        <p><strong>Estimated Arrival:</strong> <?php echo htmlspecialchars($jobDetails['estimated_arrival']); ?></p>
                    </div>
                <?php elseif ($bookingId): ?>
                    <p style="color: red;">No job details found for this Booking ID.</p>
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

<?php
// Close the database connection
$conn->close();
?>