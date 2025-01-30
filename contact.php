<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - E-Tow</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .contact-details {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .contact-details h3 {
            margin-top: 0;
            color: #333;
        }
        .contact-details p {
            margin: 10px 0;
        }
        .contact-details a {
            color: #007BFF;
            text-decoration: none;
        }
        .contact-details a:hover {
            text-decoration: underline;
        }
        .business-hours {
            background: #e9ecef;
            padding: 10px;
            border-radius: 5px;
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
            <img src="pics/profile.png" alt="User  Icon" class="user-image">
            <div class="dropdown">
                <button onclick="logout()">Logout</button>
            </div>
        </div>
    </header>
    <main>
        <div id="content">
            <div class="contact-details">
                <h3>Contact Details</h3>
                <p><strong>Phone:</strong> <a href="tel:+1234567890">+1 (234) 567-890</a></p>
                <p><strong>Email:</strong> <a href="mailto:info@etow.com">info@etow.com</a></p>
                <p><strong>Address:</strong> 123 Tow Lane, Tow City, TC 12345</p>
                <div class="business-hours">
                    <strong>Business Hours:</strong>
                    <ul>
                        <li>Monday - Friday: 9:00 AM - 5:00 PM</li>
                        <li>Saturday: 10:00 AM - 3:00 PM</li>
                        <li>Sunday: Closed</li>
                    </ul>
                </div>
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