<?php
// Database connection
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password
$dbname = "etow_db"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'];
    $token = $_POST['token'];

    // Update the password in the database
    $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL WHERE reset_token = ?");
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT); // Hash the new password
    $stmt->bind_param("ss", $hashed_password, $token);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Password has been reset successfully.";
    } else {
        echo "Invalid token or password reset failed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form method="POST" action="">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
        <label for="new_password">Enter your new password:</label>
        <input type="password" id="new_password" name="new_password" required>
        <button type="submit">Update Password</button>
    </form>
</body>
</html>