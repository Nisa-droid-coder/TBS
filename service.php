<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tow Service Booking</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<style>
    .container-wrapper {
        display: flex; /* Use flexbox for side-by-side layout */
        justify-content: space-between; /* Space between the containers */
        margin: 20px; /* Margin around the container wrapper */
    }

    .container {
        flex: 1; /* Allow containers to grow equally */
        margin: 0 10px; /* Add margin between containers */
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
            <div class="container-wrapper"> <!-- New wrapper for flex layout -->
                <div class="container">
                    <h2>Book a Tow Service</h2>
                    <img src="pics/booktow.png" alt="Tow Service" style="width:50%; height:auto; margin-bottom: 20px;">
                    <br><a href="bookTow.php" class="button">Book Now</a></br> <!-- Redirect to bookTow.php -->
                </div>

                <div class="container">
                    <h2>Track Your Tow Vehicle</h2>
                    <img src="pics/tracktow.png" alt="Track Vehicle" style="width:50%; height:auto; margin-bottom: 20px;">
                    <br><a href="trackTow.php" class="button">Track Now</a></br> <!-- Redirect to trackTow.php -->
                </div>
            </div> <!-- End of container-wrapper -->
        </div>
    </main>
    <script>
        function logout() {
            window.location.href = 'index.php'; // Redirect to index.php on logout
        }
    </script>
</body>
</html>