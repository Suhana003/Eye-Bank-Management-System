<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eye Bank Management System</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('path/to/your/background-image.jpg'); /* Use your background image here */
            background-size: cover;
            color: #333;
        }

        nav {
            background-color: rgba(0, 115, 230, 0.8); /* Transparent blue */
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
            padding: 15px;
            text-align: center;
        }

        nav a {
            color: white;
            padding: 14px 20px;
            text-decoration: none;
            text-transform: uppercase;
        }

        nav a:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }

        .section {
            padding: 100px 20px; /* Add top padding for fixed nav */
            text-align: center;
            background-color: white;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            color: #0073e6;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: rgba(0, 115, 230, 0.8);
            color: white;
            position: relative;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <nav>
        <a href="#home">Home</a>
        <a href="#register">Register as a Donor</a>
        <a href="#search">Search for Donors</a>
        <a href="#request">Request an Eye</a>
        <a href="#contact">Contact Us</a>
    </nav>

    <div class="section" id="home">
        <h1>Welcome to the Eye Bank Management System</h1>
        <p>Your journey to donate or receive eye donations starts here. Together, we can give the gift of sight.</p>
    </div>

    <div class="section" id="register">
        <h2>Register as a Donor</h2>
        <p>Become a donor and help save lives. Click below to register.</p>
        <a href="register.php">Register Now</a>
    </div>

    <div class="section" id="search">
        <h2>Search for Donors</h2>
        <p>Looking for potential donors? Use our search feature to find available donors.</p>
        <a href="search.php">Search Donors</a>
    </div>

    <div class="section" id="request">
        <h2>Request an Eye</h2>
        <p>If you or someone you know needs an eye donation, click below to make a request.</p>
        <a href="request.php">Request Now</a>
    </div>

    <div class="section" id="contact">
        <h2>Contact Us</h2>
        <p>If you have any questions, feel free to reach out to us.</p>
        <a href="contact.html">Contact Us</a>
    </div>

    <footer>
        <p>© 2024 Eye Bank Management System. All Rights Reserved.</p>
</footer>
</body>
</html>
