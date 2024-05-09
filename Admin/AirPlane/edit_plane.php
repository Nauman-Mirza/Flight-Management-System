<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to login page if session is not set
    header("Location: ../admin_login.html");
    exit();
}

// Check if ID is provided
if (!isset($_GET['id'])) {
    // Redirect to all_plane_listing.php if ID is not provided
    header("Location: all_plane_listing.php");
    exit();
}

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "flight_management_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get ID from URL parameter
$id = $_GET['id'];

// Retrieve current plane details based on ID
$sql_current = "SELECT * FROM air_planes WHERE ID = $id";
$result_current = $conn->query($sql_current);

if ($result_current->num_rows > 0) {
    $row_current = $result_current->fetch_assoc();
} else {
    // No plane found with the provided ID
    echo "No plane found with ID: $id";
    exit();
}

// Handle update form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $ser_num = $_POST['ser_num'];
    $manufacture = $_POST['manufacture'];
    $model = $_POST['model'];

    // Check if the updated serial number already exists (excluding current plane)
    $sql_check = "SELECT * FROM air_planes WHERE SerNum = '$ser_num' AND ID != $id";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        // Serial number already exists for another plane
        echo "Error: Serial Number already exists for another plane.";
    } else {
        // Update plane details in the database
        $sql_update = "UPDATE air_planes SET SerNum = '$ser_num', Manufacture = '$manufacture', Model = '$model' WHERE ID = $id";

        if ($conn->query($sql_update) === TRUE) {
            header("Location: all_plane_listing.php");
        } else {
            echo "Error updating plane details: " . $conn->error;
        }
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Plane</title>
</head>
<body>
    <form action="all_plane_listing.php" method="post">
        <input type="submit" value="Back">
    </form>
    <h2>Update Plane</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $id; ?>" method="post">
        <label for="ser_num">Serial Number:</label><br>
        <input type="text" id="ser_num" name="ser_num" value="<?php echo $row_current['SerNum']; ?>" required><br>
        
        <label for="manufacture">Manufacture:</label><br>
        <input type="text" id="manufacture" name="manufacture" value="<?php echo $row_current['Manufacture']; ?>" required><br>
        
        <label for="model">Model:</label><br>
        <input type="text" id="model" name="model" value="<?php echo $row_current['Model']; ?>" required><br><br>
        
        <input type="submit" value="Update">
    </form>
</body>
</html>
