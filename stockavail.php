<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "eye_bank";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);                                          
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $gender = $_POST['gender'];

    $stock_date = date('Y-m-d');  // Current date
$expiration_date = date('Y-m-d', strtotime($stock_date . ' + 14 days'));
$donor_name = $_POST['donor_name'];
$donor_blood_group = $_POST['donor_blood_group'];
$insert_sql = "INSERT INTO avail (stock_id,donor_name,donor_blood_group, gender, stock_date, expiration_date) 
               VALUES (?, ?, ?, ?,?,? )";

$stmt = $conn->prepare($insert_sql);
$stmt->bind_param("isssss", $stock_id,$donor_name,$donor_blood_group, $gender, $stock_date, $expiration_date);

if ($stmt->execute()) {
    echo "Stock added successfully!";
} else {
    echo "Error: " . $stmt->error;
}
$stmt->close();
}

// Fetch stocks logic
$sql = "SELECT * FROM avail";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stocks Available</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
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
    <h2>Add Stock</h2>
    <form method="POST" action="">
<input type="text" name="donor_name" placeholder="Donor Name" required>
<input type="text" name="donor_blood_group" placeholder="Blood Group" required>
<input type="text" name="gender" placeholder="Gender" required>
<button type="submit">Add Stock</button>
    </form>

    <h2>Stocks Available</h2>
    
    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Stock ID</th>
                
<th>Donor Name</th>
<th>Blood_Group</th>
<th>Gender</th>
<th>Stock Date</th>
<th>Expiration Date</th>
<th>Status</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['stock_id']; ?></td>
                    <td><?php echo $row['donor_name']; ?></td>
<td><?php echo $row['donor_blood_group']; ?></td>
<td><?php echo $row['gender']; ?></td>
<td><?php echo $row['stock_date']; ?></td>
<td><?php echo $row['expiration_date']; ?></td>
<!-- Availability status display -->
                        <td><?php if ($row['availability_status'] == 'available'): ?>
                            Available
                        <?php else: ?>
                            Unavailable
                        <?php endif; ?>
                        </td>

                </tr>
            <?php endwhile; ?>
        </table>
<?php else: ?>
        <p>No stocks available.</p>
    <?php endif; ?>
</body>
</html>

<?php
$conn->close();
?>
