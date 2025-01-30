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

// Fetch tow providers
$towProvidersQuery = "SELECT * FROM tow_providers";
$towProvidersResult = $conn->query($towProvidersQuery);

// Handle form submission for adding a new provider
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addProvider'])) {
    $provider_name = $_POST['provider_name'];
    $company_name = $_POST['company_name'];
    $vehiclePlate = $_POST['vehiclePlate'];
    $locationTow = $_POST['locationTow'];
    $timeAvailable = $_POST['timeAvailable'];

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO tow_providers (provider_name, company_name, vehiclePlate, locationTow, timeAvailable) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $provider_name, $company_name, $vehiclePlate, $locationTow, $timeAvailable);
    $stmt->execute();
    $stmt->close();
    header("Location: monitorLogs.php"); // Redirect to avoid resubmission
    exit();
}

// Handle form submission for editing a provider
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editProvider'])) {
    $tow_provider_id = $_POST['tow_provider_id'];
    $provider_name = $_POST['provider_name'];
    $company_name = $_POST['company_name'];
    $vehiclePlate = $_POST['vehiclePlate'];
    $locationTow = $_POST['locationTow'];
    $timeAvailable = $_POST['timeAvailable'];

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("UPDATE tow_providers SET provider_name = ?, company_name = ?, vehiclePlate = ?, locationTow = ?, timeAvailable = ? WHERE tow_provider_id = ?");
    $stmt->bind_param("sssssi", $provider_name, $company_name, $vehiclePlate, $locationTow, $timeAvailable, $tow_provider_id);
    $stmt->execute();
    $stmt->close();
    header("Location: monitorLogs.php"); // Redirect to avoid resubmission
    exit();
}

// Handle deletion of a provider
if (isset($_GET['delete_provider_id'])) {
    $delete_id = $_GET['delete_provider_id'];
    $stmt = $conn->prepare("DELETE FROM tow_providers WHERE tow_provider_id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: monitorLogs.php"); // Redirect to avoid resubmission
    exit();
}

// Handle form submission for adding a new booking
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addBooking'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $vehicleType = $_POST['vehicleType'];
    $licensePlate = $_POST['licensePlate'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $status = 'pending'; // Default status

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO bookings (name, phone, email, vehicleType, licensePlate, location, description, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $name, $phone, $email, $vehicleType, $licensePlate, $location, $description, $status);
    $stmt->execute();
    $stmt->close();
    header("Location: monitorLogs.php"); // Redirect to avoid resubmission
    exit();
}

// Handle form submission for editing a booking
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editBooking'])) {
    $book_id = $_POST['book_id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $vehicleType = $_POST['vehicleType'];
    $licensePlate = $_POST['licensePlate'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("UPDATE bookings SET name = ?, phone = ?, email = ?, vehicleType = ?, licensePlate = ?, location = ?, description = ?, status = ? WHERE book_id = ?");
    $stmt->bind_param("ssssssssi", $name, $phone, $email, $vehicleType, $licensePlate, $location, $description, $status, $book_id);
    $stmt->execute();
    $stmt->close();
    header("Location: monitorLogs.php"); // Redirect to avoid resubmission
    exit();
}

// Handle deletion of a booking
if (isset($_GET['delete_booking_id'])) {
    $delete_id = $_GET['delete_booking_id'];
    $stmt = $conn->prepare("DELETE FROM bookings WHERE book_id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: monitorLogs.php"); // Redirect to avoid resubmission
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Tow Admin Panel - Monitor Logs</title>
    <link rel="stylesheet" href="css/styles1.css">
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
        /* Popup Form Styles */
        .popup {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }
        .popup-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 300px; /* Could be more or less, depending on screen size */
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1><span class="e-tow">E-Tow</span></h1>
            <div class="user-icon">
                <img src="pics/profile.png" alt="User   Icon" class="user-image">
                <div class="dropdown">
                <button onclick="logout()">Logout</button>
            </div>
            </div>
        </header>
        <div class="content">
            <aside>
                <button class="menu-btn" onclick="location.href='admin.php'">Manage Users</button>
                <button class="menu-btn" onclick="location.href='monitorLogs.php'">Monitor Logs</button>
            </aside>
            <main>
                <h2>Booking Logs</h2>

                <!-- Booking Logs Table -->
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
                            <th>Tow Provider ID</th>
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
                                    <td><?php echo htmlspecialchars($row['tow_provider_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['job_status']); ?></td>
                                    <td><?php echo htmlspecialchars($row['estimated_arrival']); ?></td>
                                    <td><?php echo htmlspecialchars($row['payment_status']); ?></td>
                                    <td>
                                        <button onclick="editBooking(<?php echo htmlspecialchars($row['book_id']); ?>, '<?php echo htmlspecialchars($row['name']); ?>', '<?php echo htmlspecialchars($row['phone']); ?>', '<?php echo htmlspecialchars($row['email']); ?>', '<?php echo htmlspecialchars($row['vehicleType']); ?>', '<?php echo htmlspecialchars($row['licensePlate']); ?>', '<?php echo htmlspecialchars($row['location']); ?>', '<?php echo htmlspecialchars($row['description']); ?>', '<?php echo htmlspecialchars($row['status']); ?>')">Edit</button>
                                        <a href="?delete_booking_id=<?php echo $row['book_id']; ?>" onclick="return confirm('Are you sure you want to delete this booking?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="16">No records found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Tow Provider Information Table -->
                <br><h2>Tow Provider Information</h2></br>
                <button onclick="document.getElementById('addProviderForm').style.display='block'">Add Provider</button>
                <table>
                    <thead>
                        <tr>
                            <th>Provider ID</th>
                            <th>Provider Name</th>
                            <th>Company Name</th>
                            <th>Vehicle Plate</th>
                            <th>Location</th>
                            <th>Time Available</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($towProvidersResult->num_rows > 0): ?>
                            <?php while ($towRow = $towProvidersResult->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($towRow['tow_provider_id']); ?></td>
                                    <td><?php echo htmlspecialchars($towRow['provider_name']); ?></td>
                                    <td><?php echo htmlspecialchars($towRow['company_name']); ?></td>
                                    <td><?php echo htmlspecialchars($towRow['vehiclePlate']); ?></td>
                                    <td><?php echo htmlspecialchars($towRow['locationTow']); ?></td>
                                    <td><?php echo htmlspecialchars($towRow['timeAvailable']); ?></td>
                                    <td>
                                        <button onclick="editProvider(<?php echo htmlspecialchars($towRow['tow_provider_id']); ?>, '<?php echo htmlspecialchars($towRow['provider_name']); ?>', '<?php echo htmlspecialchars($towRow['company_name']); ?>', '<?php echo htmlspecialchars($towRow['vehiclePlate']); ?>', '<?php echo htmlspecialchars($towRow['locationTow']); ?>', '<?php echo htmlspecialchars($towRow['timeAvailable']); ?>')">Edit</button>
                                        <a href="?delete_provider_id=<?php echo $towRow['tow_provider_id']; ?>" onclick="return confirm('Are you sure you want to delete this provider?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7">No tow providers found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>

    <!-- Add Provider Popup Form -->
    <div id="addProviderForm" class="popup">
        <div class="popup-content">
            <span onclick="document.getElementById('addProviderForm').style.display='none'" style="cursor:pointer; float:right;">&times;</span>
            <h2>Add Tow Service Provider</h2>
            <form method="POST" action="">
                <label for="provider_name">Provider Name:</label>
                <input type="text" id="provider_name" name="provider_name" required>

                <label for="company_name">Company Name:</label>
                <input type="text" id="company_name" name="company_name" required>

                <label for="vehiclePlate">Vehicle Plate:</label>
                <input type="text" id="vehiclePlate" name="vehiclePlate" required>

                <label for="locationTow">Location:</label>
                <input type="text" id="locationTow" name="locationTow" required>

                <label for="timeAvailable">Time Available:</label>
                <input type="text" id="timeAvailable" name="timeAvailable" required>

                <button type="submit" name="addProvider">Add Provider</button>
            </form>
        </div>
    </div>

    <!-- Edit Provider Popup Form -->
    <div id="editPopupForm" class="popup" style="display:none;">
        <div class="popup-content">
            <span onclick="document.getElementById('editPopupForm').style.display='none'" style="cursor:pointer; float:right;">&times;</span>
            <h2>Edit Tow Service Provider</h2>
            <form method="POST" action="">
                <input type="hidden" id="edit_provider_id" name="tow_provider_id">
                <label for="edit_provider_name">Provider Name:</label>
                <input type="text" id="edit_provider_name" name="provider_name" required>

                <label for="edit_company_name">Company Name:</label>
                <input type="text" id="edit_company_name" name="company_name" required>

                <label for="edit_vehiclePlate">Vehicle Plate:</label>
                <input type="text" id="edit_vehiclePlate" name="vehiclePlate" required>

                <label for="edit_locationTow">Location:</label>
                <input type="text" id="edit_locationTow" name="locationTow" required>

                <label for="edit_timeAvailable">Time Available:</label>
                <input type="text" id="edit_timeAvailable" name="timeAvailable" required>

                <button type="submit" name="editProvider">Update Provider</button>
            </form>
        </div>
    </div>

    <!-- Add Booking Popup Form -->
    <div id="addBookingForm" class="popup">
        <div class="popup-content">
            <span onclick="document.getElementById('addBookingForm').style.display='none'" style="cursor:pointer; float:right;">&times;</span>
            <h2>Add Booking</h2>
            <form method="POST" action="">
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

                <label for="location">Location:</label>
                <input type="text" id="location" name="location" required>

                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>

                <button type="submit" name="addBooking">Add Booking</button>
            </form>
        </div>
    </div>

    <!-- Edit Booking Popup Form -->
    <div id="editBookingForm" class="popup" style="display:none;">
        <div class="popup-content">
            <span onclick="document.getElementById('editBookingForm').style.display='none'" style="cursor:pointer; float:right;">&times;</span>
            <h2>Edit Booking</h2>
            <form method="POST" action="">
                <input type="hidden" id="edit_book_id" name="book_id">
                <label for="edit_name">Full Name:</label>
                <input type="text" id="edit_name" name="name" required>

                <label for="edit_phone">Phone Number:</label>
                <input type="tel" id="edit_phone" name="phone" required>

                <label for="edit_email">Email:</label>
                <input type="email" id="edit_email" name="email" required>

                <label for="edit_vehicleType">Vehicle Type:</label>
                <input type="text" id="edit_vehicleType" name="vehicleType" required>

                <label for="edit_licensePlate">License Plate:</label>
                <input type="text" id="edit_licensePlate" name="licensePlate" required>

                <label for="edit_location">Location:</label>
                <input type="text" id="edit_location" name="location" required>

                <label for="edit_description">Description:</label>
                <textarea id="edit_description" name="description" required></textarea>

                <label for="edit_status">Status:</label>
                <select id="edit_status" name="status" required>
                    <option value="pending">Pending</option>
                    <option value="accepted">Accepted</option>
                    <option value="rejected">Rejected</option>
                </select>

                <button type="submit" name="editBooking">Update Booking</button>
            </form>
        </div>
    </div>

    <script>
        // Close the popup if the user clicks outside of it
        window.onclick = function(event) {
            var popup = document.getElementById('addProviderForm');
            if (event.target == popup) {
                popup.style.display = "none";
            }
            var editPopup = document.getElementById('editPopupForm');
            if (event.target == editPopup) {
                editPopup.style.display = "none";
            }
            var addBookingPopup = document.getElementById('addBookingForm');
            if (event.target == addBookingPopup) {
                addBookingPopup.style.display = "none";
            }
            var editBookingPopup = document.getElementById('editBookingForm');
            if (event.target == editBookingPopup) {
                editBookingPopup.style.display = "none";
            }
        }

        function editProvider(id, name, company, vehiclePlate, location, timeAvailable) {
            document.getElementById('edit_provider_id').value = id;
            document.getElementById('edit_provider_name').value = name;
            document.getElementById('edit_company_name').value = company;
            document.getElementById('edit_vehiclePlate').value = vehiclePlate;
            document.getElementById('edit_locationTow').value = location;
            document.getElementById('edit_timeAvailable').value = timeAvailable;
            document.getElementById('editPopupForm').style.display = 'flex';
        }

        function editBooking(book_id, name, phone, email, vehicleType, licensePlate, location, description, status) {
            document.getElementById('edit_book_id').value = book_id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_phone').value = phone;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_vehicleType').value = vehicleType;
            document.getElementById('edit_licensePlate').value = licensePlate;
            document.getElementById('edit_location').value = location;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_status').value = status;
            document.getElementById('editBookingForm').style.display = 'flex';
        }

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
