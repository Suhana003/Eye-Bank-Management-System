<?php 
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

// Handle deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM requests WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        echo "<p>Request deleted successfully!</p>";
        
        // Renumber the remaining requests
        $conn->query("SET @new_id = 0");
        $update_sql = "UPDATE requests SET id = (@new_id := @new_id + 1) ORDER BY id";
        if ($conn->query($update_sql) === TRUE) {
            echo "<p>Request IDs updated successfully!</p>";
        } else {
            echo "<p>Error updating request IDs: " . $conn->error . "</p>";
        }
 $event_sql = "INSERT INTO events (event_type, description) VALUES (?, ?)";
        $event_stmt = $conn->prepare($event_sql);
        $event_type = 'Request Deleted';
        $description = "Request with ID " . $delete_id . " has been deleted.";
        $event_stmt->bind_param("ss", $event_type, $description);
        $event_stmt->execute();
        $event_stmt->close();

    } else {
        echo "<p>Error deleting request: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

// Retrieve all requests
$sql = "SELECT * FROM requests ORDER BY id"; // Ensure we order by ID to reflect changes
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Requests</title>
    <link rel="stylesheet" href="style.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #0073e6;
            color: white;
            text-align: left;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .delete-btn {
            color: red;
            text-decoration: none;
            border: 1px solid red;
            padding: 4px 8px;
            border-radius: 4px;
        }
        .delete-btn:hover {
            background-color: #ffdddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Requests List</h2>
        <?php
        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Blood Group</th>
                    <th>Contact</th>
                    <th>Reason</th>
                    <th>Request Date</th>
                    <th>Action</th>
                  </tr>";
            // Output data of each row
            while($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>" . $row["id"] . "</td>
            <td>" . $row["patient_name"] . "</td> <!-- Make sure this matches your DB -->
            <td>" . $row["age"] . "</td>
            <td>" . $row["gender"] . "</td>
            <td>" . $row["blood_group"] . "</td>
            <td>" . $row["contact"] . "</td>
            <td>" . $row["eye_required"] . "</td> <!-- Updated from reason -->
            <td>" . $row["request_date"] . "</td>
            <td><a class='delete-btn' href='view_requests.php?delete_id=" . $row["id"] . "' onclick=\"return confirm('Are you sure you want to delete this request?');\">Delete</a></td>
          </tr>";
}

            echo "</table>";
        } else {
            echo "<p>No requests found.</p>";
        }

        // Close connection
        $conn->close();
        ?>
        <a href="index.html">Back to Home</a>
    </div>
</body>
</html>
