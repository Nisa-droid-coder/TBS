<?php
// Include database connection
include 'db.php';

// Handle form submission for updating job status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateJob'])) {
    $book_id = $_POST['book_id'];
    $status = $_POST['status'];
    $job_status = $_POST['job_status'];
    $estimated_arrival = $_POST['estimated_arrival'];
    $tow_provider_id = $_POST['tow_provider_id'];

    // Fetch the insurance_id for the given book_id
    $stmt = $conn->prepare("SELECT insurance_id FROM insurance_verification WHERE book_id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $stmt->bind_result($insurance_id);
    $stmt->fetch();
    $stmt->close();

    // Check if insurance_id is found
    if ($insurance_id === null) {
        echo "No insurance data found for this booking.";
        exit();
    }

    // Update the bookings status
    $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE book_id = ?");
    $stmt->bind_param("si", $status, $book_id);
    $stmt->execute();
    $stmt->close();

    // Update the manageJob table
    $stmt = $conn->prepare("REPLACE INTO manageJob (book_id, insurance_id, tow_provider_id, job_status, estimated_arrival) 
                            VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiss", $book_id, $insurance_id, $tow_provider_id, $job_status, $estimated_arrival);
    $stmt->execute();
    $stmt->close();

    // Redirect to the same page to see the updated data
    header("Location: manageJob.php");
    exit();
}

// Handle deletion of a job
if (isset($_GET['delete_booking_id'])) {
    $delete_id = $_GET['delete_booking_id'];
    $stmt = $conn->prepare("DELETE FROM manageJob WHERE book_id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manageJob.php"); // Redirect to avoid resubmission
    exit();
}

// Fetch bookings and insurance verification data along with job data
$query = "SELECT b.book_id, b.name, b.phone, b.email, b.vehicleType, b.licensePlate, 
                 b.location, b.description, b.status, iv.price, iv.insurance_status, mj.job_status, mj.estimated_arrival, 
                 mj.tow_provider_id 
          FROM bookings b 
          LEFT JOIN insurance_verification iv ON b.book_id = iv.book_id
          LEFT JOIN manageJob mj ON b.book_id = mj.book_id"; 

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Job - E-Tow</title>
    <link rel="stylesheet" href="css/styles1.css">
    <style>
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
    <div class="container">
        <header>
            <h1><span class="e-tow">E-Tow</span></h1>
            <div class="user-icon">
                <img src="pics/profile.png" alt="User  Icon" class="user-image">
                <div class="dropdown">
                <button onclick="logout()">Logout</button>
            </div>
            </div>
        </header>
        <div class="content">
            <aside>
                <button class="menu-btn" onclick="location.href='towserviceprovider.php'">Availability</button>
                <button class="menu-btn" onclick="location.href='manageJob.php'">Manage Job</button>
            </aside>
            <main>
                <h2>Manage Jobs</h2>
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
                            <th>Job Status</th>
                            <th>Estimated Arrival</th>
                            <th>Tow Provider ID</th>
                            <th>Actions</th>
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
                                    <td><?php echo htmlspecialchars($row['job_status']); ?></td>
                                    <td><?php echo htmlspecialchars($row['estimated_arrival']); ?></td>
                                    <td><?php echo htmlspecialchars($row['tow_provider_id']); ?></td>
                                    <td>
                                        <button onclick="document.getElementById('editForm<?php echo $row['book_id']; ?>').style.display='block'">Edit</button>
                                        <a href="?delete_booking_id=<?php echo $row['book_id']; ?>" onclick="return confirm('Are you sure you want to delete this job?');">Delete</a>
                                    </td>
                                </tr>

                                <!-- Edit Form -->
                                <div id="editForm<?php echo $row['book_id']; ?>" style="display:none;">
                                    <form method="POST" action="">
                                        <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($row['book_id']); ?>">
                                        <label for="status">Status:</label>
                                        <select name="status" required>
                                            <option value="pending" <?php echo ($row['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                            <option value="accepted" <?php echo ($row['status'] == 'accepted') ? 'selected' : ''; ?>>Accepted</option>
                                            <option value="rejected" <?php echo ($row['status'] == 'rejected') ? 'selected' : ''; ?>>Rejected</option>
                                        </select>
                                        <label for="job_status">Job Status:</label>
                                        <select name="job_status" required>
                                            <option value="accepted" <?php echo ($row['job_status'] == 'accepted') ? 'selected' : ''; ?>>Accepted</option>
                                            <option value="in-progress" <?php echo ($row['job_status'] == 'in-progress') ? 'selected' : ''; ?>>In Progress</option>
                                            <option value="completed" <?php echo ($row['job_status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                                            <option value="rejected" <?php echo ($row['job_status'] == 'rejected') ? 'selected' : ''; ?>>Rejected</option>
                                        </select>
                                        <label for="estimated_arrival">Estimated Arrival:</label>
                                        <input type="datetime-local" name="estimated_arrival" value="<?php echo htmlspecialchars($row['estimated_arrival']); ?>" required>
                                        <label for="tow_provider_id">Tow Provider ID:</label>
                                        <input type="number" name="tow_provider_id" value="<?php echo htmlspecialchars($row['tow_provider_id']); ?>" required>
                                        <button type="submit" name="updateJob">Update</button>
                                    </form>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="14">No records found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>
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