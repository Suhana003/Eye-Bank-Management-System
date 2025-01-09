<?php 
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "eye_bank";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$search_blood_group = "";
$search_gender = "";

// Handle delete action 
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM donors WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        echo "<p>Donor deleted successfully!</p>";
$conn->query("SET @new_id = 0");

        // 2. Update IDs based on the existing order
        $update_sql = "UPDATE donors SET id = (@new_id := @new_id + 1) ORDER BY id";
        if ($conn->query($update_sql) === TRUE) {
            echo "<p>Donor IDs updated successfully!</p>";
        } else {
            echo "<p>Error updating donor IDs: " . $conn->error . "</p>";
        }
$event_sql = "INSERT INTO events (event_type, description) VALUES (?, ?)";
        $event_stmt = $conn->prepare($event_sql);
        $event_type = 'Donor Deleted';
        $description = "Donor with ID " . $delete_id . " has been deleted.";
        $event_stmt->bind_param("ss", $event_type, $description);
        $event_stmt->execute();
        $event_stmt->close();
    } else {
        echo "<p>Error deleting donor: " . $conn->error . "</p>";
    }
    $stmt->close();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_blood_group = $_POST['blood_group'];
    $search_gender = $_POST['gender'];

    // Check if user selected any filter
    if (!empty($search_blood_group) && !empty($search_gender)) {
        $sql = "SELECT id, name, age, gender, blood_group, contact, email, availability_status FROM donors WHERE blood_group = ? AND gender = ? AND availability_status = 'available'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $search_blood_group, $search_gender);
    } elseif (!empty($search_blood_group)) {
        $sql = "SELECT id, name, age, gender, blood_group, contact, email, availability_status FROM donors WHERE blood_group = ? AND availability_status = 'available'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $search_blood_group);
    } elseif (!empty($search_gender)) {
        $sql = "SELECT id, name, age, gender, blood_group, contact, email, availability_status FROM donors WHERE gender = ? AND availability_status = 'available'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $search_gender);
    } else {
        // If no criteria are provided, show all available donors
        $sql = "SELECT id, name, age, gender, blood_group, contact, email, availability_status FROM donors WHERE availability_status = 'available'";
        $stmt = $conn->prepare($sql);
    }

    $stmt->execute();
    $result = $stmt->get_result();
$event_sql = "INSERT INTO events (event_type, description) VALUES (?, ?)";
    $event_stmt = $conn->prepare($event_sql);
    $event_type = 'Donor Search';
    $description = "A donor search was performed with blood group: " . ($search_blood_group ?: 'Any') . " and gender: " . ($search_gender ?: 'Any') . ".";
    $event_stmt->bind_param("ss", $event_type, $description);
    $event_stmt->execute();
    $event_stmt->close();
} else {
    // When the page loads, show all available donors
    $sql = "SELECT id, name, age, gender, blood_group, contact, email, availability_status FROM donors WHERE availability_status = 'available'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Donors</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #333;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #0073e6;
            color: white;
        }
        td {
            background-color: #f2f2f2;
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
        .delete-button {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        .delete-button:hover {
            background-color: #e60000;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Search for Donors</h2>
        <form method="post" action="">
            <select name="blood_group">
                <option value="">Select Blood Group (optional)</option>
                <option value="A+" <?php if ($search_blood_group == "A+") echo 'selected'; ?>>A+</option>
                <option value="A-" <?php if ($search_blood_group == "A-") echo 'selected'; ?>>A-</option>
                <option value="B+" <?php if ($search_blood_group == "B+") echo 'selected'; ?>>B+</option>
                <option value="B-" <?php if ($search_blood_group == "B-") echo 'selected'; ?>>B-</option>
                <option value="AB+" <?php if ($search_blood_group == "AB+") echo 'selected'; ?>>AB+</option>
                <option value="AB-" <?php if ($search_blood_group == "AB-") echo 'selected'; ?>>AB-</option>
                <option value="O+" <?php if ($search_blood_group == "O+") echo 'selected'; ?>>O+</option>
                <option value="O-" <?php if ($search_blood_group == "O-") echo 'selected'; ?>>O-</option>
            </select>

            <select name="gender">
                <option value="">Select Gender (optional)</option>
                <option value="Male" <?php if ($search_gender == "Male") echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if ($search_gender == "Female") echo 'selected'; ?>>Female</option>
                <option value="Other" <?php if ($search_gender == "Other") echo 'selected'; ?>>Other</option>
            </select>
            <input type="submit" value="Search">
        </form>

        <?php if (isset($result) && $result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Blood Group</th>
                    <th>Contact</th>
                    <th>Email</th>
                    
                    <th>Action</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['age']; ?></td>
                        <td><?php echo $row['gender']; ?></td>
                        <td><?php echo $row['blood_group']; ?></td>
                        <td><?php echo $row['contact']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        
                        <td>
                            <a href="search.php?delete_id=<?php echo $row['id']; ?>" 
                               onclick="return confirm('Are you sure you want to delete this donor?');">
                               <button class="delete-button">Delete</button>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php elseif (isset($result)): ?>
            <p>No donors found matching the criteria.</p>
        <?php endif; ?>

        <!-- Back to Home Button -->
        <a href="index.html" class="back-button">Back to Home</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>
