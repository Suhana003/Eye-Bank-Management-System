<?php 
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";  // Default username for XAMPP
$password = "";      // Default password (usually blank)
$dbname = "eye_bank"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $donor_name = $_POST['donor_name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $blood_group = $_POST['blood_group'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $consent = $_POST['consent'];
if ($age >= 80) {
        echo "<script>alert('Donors aged 80 or above cannot donate.');</script>";
    } 

    // Check consent
    if ($consent !== "Yes") {
        echo "<script>alert('The consent must be Yes to register.');</script>";
    } else {
        // Insert donor data into the database
        $insert_sql = "INSERT INTO donors (name, age, gender, blood_group, email, contact) 
                       VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("sissss", $donor_name, $age, $gender, $blood_group, $email, $contact);

        if ($stmt->execute()) {
$donor_id = $stmt->insert_id;
$event_sql = "INSERT INTO events (event_type, description) VALUES (?, ?)";
            $event_stmt = $conn->prepare($event_sql);
            $event_type = 'Donor Registered';
            $description = "Donor named " . $donor_name . " with blood group " . $blood_group . " has been registered.";
            $event_stmt->bind_param("ss", $event_type, $description);
            $event_stmt->execute();
            $event_stmt->close();
// Redirect to the success page
        header("Location: registration_success.php?donor_id=$donor_id&donor_name=" . urlencode($name));
        exit();
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
// Prepare the SQL statement to include registration_date
    $sql = "INSERT INTO donors (name, age, gender, blood_group, contact, email, registration_date) VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisssss", $name, $age, $gender, $blood_group, $contact, $email);

    if ($stmt->execute()) {
 
        // Registration successful, handle success message here
        // Redirect to certificate page or show success message
    } else {
        echo "Error: " . $stmt->error;
    }

            $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register as a Donor</title>
    <link rel="stylesheet" href="style.css">
    <style>
         body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .main-container {
            display: flex;
            margin: 20px;
        }

        .form-container {
            flex: 3; /* Takes up 75% of the space */
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            margin-right: 20px;
        }

        .image-quote-container {
            flex: 1; /* Takes up 25% of the space */
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
        }

        .quote {
            font-style: italic;
            font-size: 1.2em;
            color: #0073e6;
            margin: 20px 0;
        }

        .form-container form {
            display: flex;
            flex-direction: column;
        }

        .form-container input,
        .form-container select,
        .form-container button {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 100%; /* Set width to 100% */
        }

        .form-container button {
            background-color: #0073e6;
            color: white;
            border: none;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #005bb5;
        }
	.image-quote-container {
            flex: 1; /* Takes up 25% of the space */
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
        }

        img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            display: block;
            margin: 0 auto; /* Center the image */
        }

        .back-button {
            background-color: #0073e6;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            text-decoration: none;
            border-radius: 5px;
            margin-left: 10px;
        }

        .back-button:hover {
            background-color: #005bb5;
        }

        .form-buttons {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }
        

    </style>
</head>

<body>
    <div class="main-container">
        <div class="form-container">
            <h2>Register as a Donor</h2>
            <form method="post" action="">
                <input type="text" name="donor_name" placeholder="Donor Name" required>
                <input type="number" name="age" placeholder="Age" required>
                <select name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
                <input type="text" name="blood_group" placeholder="Blood Group" required>
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="text" name="contact" placeholder="Contact Number" required>

                <label>Do you give your consent to be an eye donor?</label>
                <div>
                    <input type="radio" name="consent" value="Yes" required> Yes
                    <input type="radio" name="consent" value="No"> No
                </div>

                <button type="submit">Register Now</button>
            </form>
        </div>
        <div class="image-quote-container">
            <img src="eye.jpg" alt="Eye Donation Awareness"><br>
            <div class="quote">"The eyes are the window to the soul, and you can give someone the chance to see the world again."</div>
            <div class="quote">"Donate your eyes and live twice."</div>
            <div class="quote">"Be a donor, be a hero, give the gift of sight."</div>
        </div>
        <a href="index.html" class="back-button">Back to Home</a>
    </div>
</body>
</html>
