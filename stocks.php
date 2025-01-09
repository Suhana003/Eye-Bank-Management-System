<?php
// Database connection credentials
$host = 'localhost';  // Database host (usually 'localhost')
$dbname = 'eyebank';  // Database name
$username = 'root';   // Database username
$password = '';       // Database password

// Create a connection to the database
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully to the database<br>";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to display stocks in a table
function displayStocks($conn) {
    $sql = "SELECT * FROM stockss";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Fetch all rows as an associative array
    $stocks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($stocks) > 0) {
        echo "<h2>Stocks List</h2>";
        echo "<table border='1' cellpadding='10'>";
        echo "<tr>
                <th>Stock ID</th>
                <th>Stock Name</th>
                <th>Quantity</th>
                <th>Received Date</th>
                <th>Expiry Date</th>
                <th>Supplier</th>
                <th>Description</th>
                <th>Status</th>
            </tr>";
        
        foreach ($stockss as $stock) {
            echo "<tr>";
            echo "<td>" . $stock['stock_id'] . "</td>";
            echo "<td>" . $stock['stock_name'] . "</td>";
            echo "<td>" . $stock['quantity'] . "</td>";
            echo "<td>" . $stock['received_date'] . "</td>";
            echo "<td>" . $stock['expiry_date'] . "</td>";
            echo "<td>" . $stock['supplier'] . "</td>";
            echo "<td>" . $stock['description'] . "</td>";
            echo "<td>" . $stock['status'] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No stocks available.";
    }
}

// Function to add a new stock
function addStock($conn, $stock_name, $quantity, $received_date, $expiry_date, $supplier, $description, $status) {
    $sql = "INSERT INTO stockss (stock_name, quantity, received_date, expiry_date, supplier, description, status)
            VALUES (:stock_name, :quantity, :received_date, :expiry_date, :supplier, :description, :status)";
    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':stock_name', $stock_name);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':received_date', $received_date);
    $stmt->bindParam(':expiry_date', $expiry_date);
    $stmt->bindParam(':supplier', $supplier);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':status', $status);

    if ($stmt->execute()) {
        echo "New stock added successfully!";
    } else {
        echo "Failed to add new stock.";
    }
}

// Call function to display stocks
displayStocks($conn);

?>

<!-- HTML form to insert new stock into the database -->
<h2>Add New Stock</h2>
<form method="post" action="">
    <label>Stock Name:</label><br>
    <input type="text" name="stock_name" required><br>
    
    <label>Quantity:</label><br>
    <input type="number" name="quantity" required><br>
    
    <label>Received Date:</label><br>
    <input type="date" name="received_date" required><br>
    
    <label>Expiry Date:</label><br>
    <input type="date" name="expiry_date"><br>
    
    <label>Supplier:</label><br>
    <input type="text" name="supplier"><br>
    
    <label>Description:</label><br>
    <textarea name="description"></textarea><br>
    
    <label>Status:</label><br>
    <select name="status" required>
        <option value="available">Available</option>
        <option value="used">Used</option>
        <option value="expired">Expired</option>
    </select><br><br>
    
    <input type="submit" name="submit" value="Add Stock">
</form>

<?php
// If the form is submitted, add a new stock to the database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stock_name = $_POST['stock_name'];
    $quantity = $_POST['quantity'];
    $received_date = $_POST['received_date'];
    $expiry_date = $_POST['expiry_date'];
    $supplier = $_POST['supplier'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    // Call function to add stock
    addStock($conn, $stock_name, $quantity, $received_date, $expiry_date, $supplier, $description, $status);
    
    // Refresh the page to show the updated stock list
    echo "<meta http-equiv='refresh' content='0'>";
}
?>

