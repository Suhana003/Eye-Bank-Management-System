<?php
// Get the donor details from the URL
if (isset($_GET['donor_id']) && isset($_GET['donor_name'])) {
    $donor_id = $_GET['donor_id'];
    $donor_name = htmlspecialchars($_GET['donor_name']); // To prevent XSS
} else {
    // Redirect back to registration if data is not set
    header("Location: register.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .success-message {
            text-align: center;
            margin-top: 50px;
            padding: 20px;
            border: 1px solid #0073e6;
            border-radius: 10px;
            background-color: rgba(173, 216, 230, 0.8); /* Light blue background */
        }
        .view-certificate-btn {
            background-color: #0073e6;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 20px;
        }
        .view-certificate-btn:hover {
            background-color: #005bb5;
        }
    </style>
</head>
<body>
    <div class="success-message">
        <h2>Registration Successful!</h2>
        <p>Thank you, <strong><?php echo $donor_name; ?></strong>, for registering as an eye donor. Your registration was successful.</p>
        <a href="certificate.php?donor_id=<?php echo $donor_id; ?>&donor_name=<?php echo urlencode($donor_name); ?>">
            <button class="view-certificate-btn">View Your Certificate</button>
        </a>
        <br>
        <a href="index.html" class="back-button">Back to Home</a>
    </div>
</body>
</html>
