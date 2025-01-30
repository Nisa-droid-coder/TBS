<?php
// Include database connection
include 'db.php';

$book_id = $_GET['book_id'] ?? '';
$existingData = [];

// Fetch existing insurance verification data
if ($book_id) {
    $stmt = $conn->prepare("SELECT insurance_status, price FROM insurance_verification WHERE book_id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $existingData = $result->fetch_assoc();
    $stmt->close();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $insurance_status = $_POST['insurance_status'];
    $price = $_POST['price'];

    // Update or insert insurance verification data
    $stmt = $conn->prepare("REPLACE INTO insurance_verification (book_id, insurance_status, price) VALUES (?, ?, ?)");
    $stmt->bind_param("isd", $book_id, $insurance_status, $price);
    $stmt->execute();
    $stmt->close();

    // Redirect back to insuranceAgent.php
    header("Location: insuranceAgent.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Insurance - E-Tow</title>
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
            <main>
                <h2>Verify Insurance for Booking ID: <?php echo htmlspecialchars($book_id); ?></h2>
                <form method="POST" action="">
                    <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($book_id); ?>">
                    <div class="form-group">
                        <label for="insurance_status">Insurance Status:</label>
                        <select name="insurance_status" required>
                            <option value="verified" <?php echo (isset($existingData['insurance_status']) && $existingData['insurance_status'] == 'verified') ? 'selected' : ''; ?>>Verified</option>
                            <option value="not_verified" <?php echo (isset($existingData['insurance_status']) && $existingData['insurance_status'] == 'not_verified') ? 'selected' : ''; ?>>Not Verified</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="price">Price:</label>
                        <input type="number" name="price" step="0.01" required value="<?php echo isset($existingData['price']) ? htmlspecialchars($existingData['price']) : ''; ?>">
                    </div>
                    <button type="submit">Submit Verification</button>
                </form>
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
