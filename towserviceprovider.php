<?php
// Include database connection
include 'db.php';

// Handle form submission for adding a new provider
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addProvider'])) {
    $provider_name = $_POST['provider_name'];
    $company_name = $_POST['company_name'];
    $vehiclePlate = $_POST['vehiclePlate'];
    $locationTow = $_POST['locationTow'];
    $timeAvailable = $_POST['timeAvailable']; // This will now be a string

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO tow_providers (provider_name, company_name, vehiclePlate, locationTow, timeAvailable) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $provider_name, $company_name, $vehiclePlate, $locationTow, $timeAvailable);
    $stmt->execute();
    $stmt->close();
}

// Handle form submission for editing a provider
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editProvider'])) {
    $tow_provider_id = $_POST['tow_provider_id'];
    $provider_name = $_POST['provider_name'];
    $company_name = $_POST['company_name'];
    $vehiclePlate = $_POST['vehiclePlate'];
    $locationTow = $_POST['locationTow'];
    $timeAvailable = $_POST['timeAvailable']; // This will now be a string

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("UPDATE tow_providers SET provider_name = ?, company_name = ?, vehiclePlate = ?, locationTow = ?, timeAvailable = ? WHERE tow_provider_id = ?");
    $stmt->bind_param("sssssi", $provider_name, $company_name, $vehiclePlate, $locationTow, $timeAvailable, $tow_provider_id);
    $stmt->execute();
    $stmt->close();
}

// Handle deletion of a provider
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM tow_providers WHERE tow_provider_id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch existing providers
$result = $conn->query("SELECT * FROM tow_providers");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tow Service Provider Management - E-Tow</title>
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
        /* New styles for the form */
        form {
            display: flex;
            flex-direction: column;
            gap: 15px; /* Space between form elements */
            margin-bottom: 20px; /* Space below the form */
        }
        label {
            font-weight: bold; /* Make labels bold */
        }
        input[type="text"],
        input[type="number"] {
            padding: 10px; /* Padding for input fields */
            border: 1px solid #ccc; /* Border for input fields */
            border-radius: 4px; /* Rounded corners for input fields */
            width: 100%; /* Full width */
            box-sizing: border-box; /* Include padding in width */
        }
        button {
            padding: 10px; /* Padding for buttons */
            background-color: #007bff; /* Button background color */
            color: white; /* Button text color */
            border: none; /* No border */
            border-radius: 4px; /* Rounded corners for buttons */
            cursor: pointer; /* Pointer cursor on hover */
        }
        button:hover {
            background-color: #0056b3; /* Darker background on hover */
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
                <img src="pics/profile.png" alt="User   Icon" class="user-image">
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
                <h2>Manage Tow Service Providers</h2>
                <button onclick="document.getElementById('popupForm').style.display='flex'">Add Provider</button>
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
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['tow_provider_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['provider_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['company_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['vehiclePlate']); ?></td>
                                    <td><?php echo htmlspecialchars($row['locationTow']); ?></td>
                                    <td><?php echo htmlspecialchars($row['timeAvailable']); ?></td>
                                    <td>
                                        <a href="towserviceprovider.php?delete_id=<?php echo $row['tow_provider_id']; ?>" onclick="return confirm('Are you sure you want to delete this provider?');">Delete</a>
                                        <button onclick="editProvider(<?php echo htmlspecialchars($row['tow_provider_id']); ?>, '<?php echo htmlspecialchars($row['provider_name']); ?>', '<?php echo htmlspecialchars($row['company_name']); ?>', '<?php echo htmlspecialchars($row['vehiclePlate']); ?>', '<?php echo htmlspecialchars($row['locationTow']); ?>', '<?php echo htmlspecialchars($row['timeAvailable']); ?>')">Edit</button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7">No records found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>

    <!-- Popup Form -->
    <div id="popupForm" class="popup" style="display:none;">
        <div class="popup-content">
            <span onclick="document.getElementById('popupForm').style.display='none'" style="cursor:pointer; float:right;">&times;</span>
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

    <script>
        // Close the popup if the user clicks outside of it
        window.onclick = function(event) {
            var popup = document.getElementById('popupForm');
            if (event.target == popup) {
                popup.style.display = "none";
            }
            var editPopup = document.getElementById('editPopupForm');
            if (event.target == editPopup) {
                editPopup.style.display = "none";
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