<?php
// Include database connection
include 'db.php';

// Fetch bookings and job data along with payment status
$query = "SELECT b.book_id, b.name, b.phone, b.email, b.vehicleType, b.licensePlate, 
                 b.location, b.description, b.status, iv.price, iv.insurance_status, 
                 mj.job_status, mj.estimated_arrival, mj.tow_provider_id, 
                 p.payment_status 
          FROM bookings b 
          LEFT JOIN insurance_verification iv ON b.book_id = iv.book_id
          LEFT JOIN manageJob mj ON b.book_id = mj.book_id
          LEFT JOIN payments p ON b.book_id = p.book_id"; 

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* Center the table in the middle of the screen */
        body {
            background-color: #f8d34c; /* Background color for the body */
        }
        table {
            width: 100%;
            max-width: 1200px; /* Maximum width for the table */
            border-collapse: collapse;
            margin: 20px auto; /* Center the table */
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f1f1f1;
            cursor: pointer; /* Change cursor to pointer */
        }
        
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
        <h2 style="text-align: center;">Booking Logs</h2>
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Full Name</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Vehicle Type</th>
                    <th>License Plate</th>
                    <th>Location</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Price</th>
                    <th>Insurance Status</th>
                    <th>Tow Provider ID</th> <!-- New column for Tow Provider ID -->
                    <th>Job Status</th>
                    <th>Estimated Arrival</th>
                    <th>Payment Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['book_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['vehicleType']); ?></td>
                            <td><?php echo htmlspecialchars($row['licensePlate']); ?></td>
                            <td><?php echo htmlspecialchars($row['location']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td><?php echo htmlspecialchars($row['price']); ?></td>
                            <td><?php echo htmlspecialchars($row['insurance_status']); ?></td>
                            <td><?php echo htmlspecialchars($row['tow_provider_id']); ?></td> <!-- Display Tow Provider ID -->
                            <td><?php echo htmlspecialchars($row['job_status']); ?></td>
                            <td><?php echo htmlspecialchars($row['estimated_arrival']); ?></td>
                            <td><?php echo htmlspecialchars($row['payment_status']); ?></td>
                            <td>
                                <a href="payment.php?booking_id=<?php echo urlencode($row['book_id']); ?>&amount=<?php echo htmlspecialchars($row['price']); ?>">Pay Now</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="15">No records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
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