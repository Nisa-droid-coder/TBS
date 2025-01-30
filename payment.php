<?php
// Include database connection
include 'db.php';

// Initialize variables
$bookingId = $_GET['booking_id'] ?? '';
$amount = $_GET['amount'] ?? 0;
$paymentStatus = 'pending'; // Default payment status

// Fetch booking and job details based on booking_id
$jobDetails = [];
if ($bookingId) {
    $stmt = $conn->prepare("SELECT b.name, b.phone, b.email, b.vehicleType, b.licensePlate, 
                                    b.location, b.description, iv.price, iv.insurance_status, 
                                    mj.job_status, mj.estimated_arrival, mj.tow_provider_id 
                             FROM bookings b 
                             LEFT JOIN insurance_verification iv ON b.book_id = iv.book_id 
                             LEFT JOIN manageJob mj ON b.book_id = mj.book_id 
                             WHERE b.book_id = ?");
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();
    $result = $stmt->get_result();
    $jobDetails = $result->fetch_assoc();
    $stmt->close();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $paymentMethod = $_POST['payment_method'];
    $cardNumber = $_POST['cardNumber'];
    $expiryDate = $_POST['expiryDate'];
    $cvv = $_POST['cvv'];

    // Validate payment details
    $errors = [];
    
    // Validate card number (must be 16 digits)
    if (!preg_match('/^\d{16}$/', $cardNumber)) {
        $errors[] = "Card number must be 16 digits.";
    }

    // Validate expiry date (MM/YY format)
    if (!preg_match('/^(0[1-9]|1[0-2])\/?([0-9]{2})$/', $expiryDate)) {
        $errors[] = "Expiry date must be in MM/YY format.";
    } else {
        // Check if the card is expired
        $currentDate = new DateTime();
        $expiryDateParts = explode('/', $expiryDate);
        $expiryMonth = (int)$expiryDateParts[0];
        $expiryYear = (int)$expiryDateParts[1] + 2000; // Convert YY to YYYY
        $expiryDateTime = new DateTime();
        $expiryDateTime->setDate($expiryYear, $expiryMonth, 1);
        $expiryDateTime->modify('last day of this month');

        if ($currentDate > $expiryDateTime) {
            $errors[] = "Card has expired.";
        }
    }

    // Validate CVV (must be 3 digits)
    if (!preg_match('/^\d{3}$/', $cvv)) {
        $errors[] = "CVV must be 3 digits.";
    }

    // If there are no errors, process the payment
    if (empty($errors)) {
        // Here you would typically process the payment with a payment gateway
        // For demonstration, we will assume the payment is successful

        // Insert payment record into the database
        $stmt = $conn->prepare("INSERT INTO payments (book_id, amount, payment_status, payment_method) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("idss", $bookingId, $amount, $paymentStatus, $paymentMethod);

        if ($stmt->execute()) {
            // Update payment status to completed
            $paymentStatus = 'completed';
            
            // Update the payment status in the payments table
            $stmt = $conn->prepare("UPDATE payments SET payment_status = ? WHERE book_id = ?");
            $stmt->bind_param("si", $paymentStatus, $bookingId);
            $stmt->execute();
            $stmt->close();

            // Redirect to receipt page
            header("Location: receipt.php?booking_id=" . urlencode($bookingId) . "&amount=" . htmlspecialchars($amount));
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        // Display errors
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - E-Tow</title>
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
            <img src="pics/profile.png" alt="User   Icon" class="user-image">
            <div class="dropdown">
                <button onclick="logout()">Logout</button>
            </div>
        </div>
    </header>
    <main>
        <div id="content">
            <div class="container">
                <h2>Payment</h2>
                <p>Please enter your payment details below:</p>

                <!-- Display Job Information -->
                <?php if ($jobDetails): ?>
                    <h3>Booking Tow Information</h3>
                    <p><strong>Full Name:</strong> <?php echo htmlspecialchars($jobDetails['name']); ?></p>
                    <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($jobDetails['phone']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($jobDetails['email']); ?></p>
                    <p><strong>Vehicle Type:</strong> <?php echo htmlspecialchars($jobDetails['vehicleType']); ?></p>
                    <p><strong>License Plate:</strong> <?php echo htmlspecialchars($jobDetails['licensePlate']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($jobDetails['location']); ?></p>
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($jobDetails['description']); ?></p>
                    <p><strong>Insurance Status:</strong> <?php echo htmlspecialchars($jobDetails['insurance_status']); ?></p>
                    <p><strong>Job Status:</strong> <?php echo htmlspecialchars($jobDetails['job_status']); ?></p>
                    <p><strong>Estimated Arrival:</strong> <?php echo htmlspecialchars($jobDetails['estimated_arrival']); ?></p>
                    <p><strong>Tow Provider ID:</strong> <?php echo htmlspecialchars($jobDetails['tow_provider_id']); ?></p> <!-- Display Tow Provider ID -->
                    <p><strong>Amount:</strong> $<?php echo htmlspecialchars($amount); ?></p>
                <?php else: ?>
                    <p>No job details found for this booking.</p>
                <?php endif; ?>

                <form id="paymentForm" method="POST" action="">
                    <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($bookingId); ?>">
                    <input type="hidden" name="amount" value="<?php echo htmlspecialchars($amount); ?>">
                    <label for="cardNumber">Card Number:</label>
                    <input type="text" id="cardNumber" name="cardNumber" required>

                    <label for="expiryDate">Expiry Date (MM/YY):</label>
                    <input type="text" id="expiryDate" name="expiryDate" required>

                    <label for="cvv">CVV:</label>
                    <input type="text" id="cvv" name="cvv" required>

                    <label for="payment_method">Payment Method:</label>
                    <select name="payment_method" required>
                        <option value="credit_card">Credit Card</option>
                        <option value="debit_card">Debit Card</option>
                        <option value="paypal">PayPal</option>
                        <option value="bank_transfer">Bank Transfer</option>
                    </select>

                    <button type="submit">Pay Now</button>
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