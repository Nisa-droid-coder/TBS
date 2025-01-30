<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History of Services - E-Tow</title>
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
                <button class="menu-btn" onclick="location.href='historyService.php'">History Service</button>
            </aside>
            <main>
                <h2>History of Services</h2>
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
                            <th>Status</th>
                            <th>Job Status</th> <!-- New column for Job Status -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($history as $record): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($record['id']); ?></td>
                                <td><?php echo htmlspecialchars($record['name']); ?></td>
                                <td><?php echo htmlspecialchars($record['phone']); ?></td>
                                <td><?php echo htmlspecialchars($record['email']); ?></td>
                                <td><?php echo htmlspecialchars($record['vehicleType']); ?></td>
                                <td><?php echo htmlspecialchars($record['licensePlate']); ?></td>
                                <td><?php echo htmlspecialchars($record['location']); ?></td>
                                <td><?php echo htmlspecialchars($record['status']); ?></td>
                                <td><?php echo htmlspecialchars($record['job_status']); ?></td> <!-- Display Job Status -->
                            </tr>
                        <?php endforeach; ?>
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