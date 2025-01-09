<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "eye_bank";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all events from the events table
$sql = "SELECT * FROM events ORDER BY event_timestamp DESC"; // Assuming 'event_time' is the timestamp column
$result = $conn->query($sql);

if (!$result) {
    die("Error fetching events: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Events</title>
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
    </style>
</head>
<body>
    <div class="container">
        <h2>System Events</h2>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Event Type</th>
                    <th>Description</th>
                    <th>Timestamp</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['event_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['event_type']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['event_timestamp']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No events found.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>
