<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "eye_bank";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch donor's details using their ID or email
$donor_id = $_GET['donor_id']; // Assuming you pass the donor's ID as a parameter in the URL
$sql = "SELECT name, registration_date FROM donors WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $donor_id);
$stmt->execute();
$result = $stmt->get_result();
$donor = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eye Donation Certificate</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f9;
        }
        .certificate {
            width: 80%;
            padding: 40px;
            text-align: center;
            background-color: white;
            border: 5px solid #0073e6;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .certificate h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #0073e6;
        }
        .certificate h2 {
            font-size: 2em;
            margin-bottom: 20px;
        }
        .certificate p {
            font-size: 1.2em;
            line-height: 1.6;
        }
        .certificate .recipient {
            font-weight: bold;
            font-size: 1.8em;
            color: #333;
        }
        .certificate .details {
            margin-top: 40px;
        }
        .certificate .signatures {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            padding: 0 50px;
        }
        .certificate .signatures div {
            text-align: center;
        }
        .certificate .signatures div p {
            margin: 0;
            font-size: 1em;
        }
        .certificate .signatures div .line {
            margin-top: 10px;
            width: 200px;
            height: 1px;
            background-color: #333;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <h1>Certificate of Appreciation</h1>
        <h2>This certificate is presented to</h2>
        <p class="recipient"><?php echo $donor['name']; ?></p>
        <p>In recognition of your generous pledge to donate your eyes.</p>
        <p>Your selfless contribution will help restore sight and change lives.</p>
        <p class="details">We deeply appreciate your commitment to making a difference.</p>

        <div class="signatures">
            <div>
                <img src="logo1.png" alt="Signature" style="width: 200px; margin-top: 50px;">

                <p>Authorized Signatory</p>
            </div>
            <div>
                <p style="position: relative; top: -15px; font-size: 1.1em;">
            <?php echo date("F j, Y", strtotime($donor['registration_date'])); ?>
        </p>
    </div>
        </div>
    </div>
</body>
</html>
