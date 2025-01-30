<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Tow</title>
    <link rel="stylesheet" href="css/styles.css">
    <styles>
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
    </styles>
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
    </header>
    <header>
        <div class="user-icon">
            <img src="pics/profile.png" alt="User  Icon" class="user-image">
            <div class="dropdown">
                <button onclick="logout()">Logout</button>
            </div>
        </div>
    </header>

    <section class="hero">
        <h1>Online Booking Tow Service</h1>
        <p>We are here to help! Anytime. Anywhere.</p>
    </section>

    <section class="services">
        <div class="service-box">
            <h3>24/7 TOWING ASSISTANCE</h3>
            <p>Our team of professionals is dedicated to providing 24/7 towing assistance, ensuring that help is just a call away whenever you need it.</p>
        </div>
        <div class="service-box">
            <h3>ROADSIDE ASSISTANCE</h3>
            <p>In addition to towing services, we also offer reliable roadside assistance to get you back on the road as quickly as possible.</p>
        </div>
        <div class="service-box">
            <h3>EMERGENCY RECOVERY</h3>
            <p>We specialize in emergency vehicle recovery, providing prompt and efficient assistance during unexpected breakdowns or accidents.</p>
        </div>
    </section>
    <script>
        function logout() {
            window.location.href = 'index.php'; // Redirect to index.php on logout
        }
    </script>
</body>
</html>
