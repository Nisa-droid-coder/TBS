<?php
// Include database connection
include 'db.php';

// Handle form submission for adding a new user
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addUser'])) {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $role = $_POST['role'];

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $password, $role);
    $stmt->execute();
    $stmt->close();
}

// Handle form submission for editing a user
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editUser'])) {
    $user_id = $_POST['user_id'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("UPDATE users SET email = ?, role = ? WHERE id = ?");
    $stmt->bind_param("ssi", $email, $role, $user_id);
    $stmt->execute();
    $stmt->close();
}

// Handle deletion of a user
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch users from the database
$query = "SELECT id, email, role, created_at FROM users";
$result = $conn->query($query);

// Check if the query was successful
if ($result === false) {
    die("Error executing query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Tow Admin Panel</title>
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
            background-color: #0056b3;

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
                <img src="pics/profile.png" alt="User Icon" class="user-image">
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
                <h2>User Management</h2>

                <!-- Add User Button -->
                <button onclick="document.getElementById('addUserForm').style.display='block'">Add User</button>

                <!-- Users Table -->
                <table>
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['role']); ?></td>
                                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                    <td>
                                        <button onclick="document.getElementById('editForm<?php echo $row['id']; ?>').style.display='block'">Edit</button>
                                        <a href="?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                    </td>
                                </tr>

                                <!-- Edit User Form -->
                                <div id="editForm<?php echo $row['id']; ?>" style="display:none;">
                                    <form method="POST" action="">
                                        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                        <label for="email">Email:</label>
                                        <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
                                        <label for="role">Role:</label>
                                        <select name="role" required>
                                            <option value="customer" <?php echo ($row['role'] == 'customer') ? 'selected' : ''; ?>>Customer</option>
                                            <option value="admin" <?php echo ($row['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                                            <option value="insuranceAgent" <?php echo ($row['role'] == 'insuranceAgent') ? 'selected' : ''; ?>>Insurance Agent</option>
                                            <option value="serviceProvider" <?php echo ($row['role'] == 'serviceProvider') ? 'selected' : ''; ?>>Service Provider</option>
                                        </select>
                                        <button type="submit" name="editUser">Update User</button>
                                    </form>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">No users found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>

    <!-- Add User Popup Form -->
    <div id="addUserForm" class="popup">
        <div class="popup-content">
            <span onclick="document.getElementById('addUserForm').style.display='none'" style="cursor:pointer; float:right;">&times;</span>
            <h2>Add New User</h2>
            <form method="POST" action="">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <label for="role">Role:</label>
                <select name="role" required>
                    <option value="customer">Customer</option>
                    <option value="admin">Admin</option>
                    <option value="insuranceAgent">Insurance Agent</option>
                    <option value="serviceProvider">Service Provider</option>
                </select>
                <button type="submit" name="addUser">Add User</button>
            </form>
        </div>
    </div>

    <script>
        // Close the popup if the user clicks outside of it
        window.onclick = function(event) {
            var popup = document.getElementById('addUserForm');
            if (event.target == popup) {
                popup.style.display = "none";
            }
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