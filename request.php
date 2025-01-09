<?php 
// Database connection
$servername = "localhost";
$username = "root";
$password = "";  // Default MySQL password for root user in XAMPP
$dbname = "eye_bank";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_name = $_POST['patient_name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $blood_group = $_POST['blood_group'];
    $contact = $_POST['contact'];
    $eye_required = $_POST['eye_required'];
    
    if (preg_match('/^\d{10}$/', $contact)) {
        // Check if a matching donor is available
        $sql = "SELECT * FROM avail WHERE donor_blood_group = ? AND gender = ? LIMIT 1";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $blood_group, $gender);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Donor available, insert request into requests table
            $avail = $result->fetch_assoc();
            $insert_sql = "INSERT INTO requests (patient_name, age, gender, blood_group, contact, eye_required) 
                           VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("sissss", $patient_name, $age, $gender, $blood_group, $contact, $eye_required);

            if ($stmt->execute()) {
                // Update the donor's availability to 'unavailable'
                $update_sql = "UPDATE avail SET availability_status = 'unavailable' WHERE stock_id = ?";
                $stmt_update = $conn->prepare($update_sql);
                $stmt_update->bind_param("i", $avail['stock_id']);
                if ($stmt_update->execute()) {
                    echo "<p>Eye request submitted successfully! Matching donor found and marked as unavailable.</p>";
$event_sql = "INSERT INTO events (event_type, description) VALUES (?, ?)";
        $event_stmt = $conn->prepare($event_sql);
        $event_type = 'Request Added';
        $description = "New request for blood group " . $blood_group . " added by " . $patient_name;
        $event_stmt->bind_param("ss", $event_type, $description);
        $event_stmt->execute();
        $event_stmt->close();
        
                } else {
                    echo "<p>Error updating donor status: " . $stmt_update->error . "</p>";
                }
                $stmt_update->close();
            } else {
                echo "<p>Error: " . $stmt->error . "</p>";
            }

        } else {
            // No matching donor available
            echo "<script>
                    window.onload = function() {
                        let messageBox = document.createElement('div');
                        messageBox.style.position = 'fixed';
                        messageBox.style.top = '50%';
                        messageBox.style.left = '50%';
                        messageBox.style.transform = 'translate(-50%, -50%)';
                        messageBox.style.padding = '20px';
                        messageBox.style.backgroundColor = '#ffcccb';
                        messageBox.style.color = 'black';
                        messageBox.style.border = '2px solid red';
                        messageBox.style.borderRadius = '10px';
                        messageBox.style.textAlign = 'center';
                        messageBox.innerHTML = 'Sorry, no matching donor is currently available. Please try again later.';
                        
                        let closeButton = document.createElement('button');
                        closeButton.innerHTML = 'OK';
                        closeButton.style.marginTop = '10px';
                        closeButton.style.padding = '5px 10px';
                        closeButton.style.border = 'none';
                        closeButton.style.backgroundColor = '#0073e6';
                        closeButton.style.color = 'white';
                        closeButton.style.cursor = 'pointer';
                        closeButton.onclick = function() {
                            document.body.removeChild(messageBox);
                        };
                        messageBox.appendChild(closeButton);
                        
                        document.body.appendChild(messageBox);
                    };
                </script>";
        }

        $stmt->close();
    } else {
        // Invalid contact number
        echo "<script>
            window.onload = function() {
                let messageBox = document.createElement('div');
                messageBox.style.position = 'fixed';
                messageBox.style.top = '50%';
                messageBox.style.left = '50%';
                messageBox.style.transform = 'translate(-50%, -50%)';
                messageBox.style.padding = '20px';
                messageBox.style.backgroundColor = '#ffcccb';
                messageBox.style.color = 'black';
                messageBox.style.border = '2px solid red';
                messageBox.style.borderRadius = '10px';
                messageBox.style.textAlign = 'center';
                messageBox.innerHTML = 'Contact number must be exactly 10 digits.';
                
                let closeButton = document.createElement('button');
                closeButton.innerHTML = 'OK';
                closeButton.style.marginTop = '10px';
                closeButton.style.padding = '5px 10px';
                closeButton.style.border = 'none';
                closeButton.style.backgroundColor = '#0073e6';
                closeButton.style.color = 'white';
                closeButton.style.cursor = 'pointer';
                closeButton.onclick = function() {
                    document.body.removeChild(messageBox);
                };
                messageBox.appendChild(closeButton);
                
                document.body.appendChild(messageBox);
            };
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request an Eye</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
            background-color: rgba(255, 255, 255, 0.8); /* Add a translucent background */
            padding: 20px;
            border-radius: 10px;
        }
        .back-button {
            background-color: #0073e6;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            display: inline-block;
        }
        .back-button:hover {
            background-color: #005bb5;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Request an Eye</h2>
        <form method="post" action="">
            <input type="text" name="patient_name" placeholder="Patient Name" required>
            <input type="number" name="age" placeholder="Age" required>
            <select name="gender" required>
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
            <input type="text" name="blood_group" placeholder="Blood Group" required>
            <input type="text" name="contact" placeholder="Contact" required>
            <select name="eye_required" required>
                <option value="">Select Eye Required</option>
                <option value="Left">Left</option>
                <option value="Right">Right</option>
                <option value="Both">Both</option>
            </select>
            <input type="submit" value="Submit Request">
        </form>
        
        <!-- Back to Home Button -->
        <a href="index.html" class="back-button">Back to Home</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>
