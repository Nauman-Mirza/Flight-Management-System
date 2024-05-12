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
    // Redirect to all_staff_listing.php if ID is not provided
    header("Location: all_staff_listing.php");
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

// Retrieve current staff details based on ID
$sql_current = "SELECT * FROM staffs WHERE ID = $id";
$result_current = $conn->query($sql_current);

if ($result_current->num_rows > 0) {
    $row_current = $result_current->fetch_assoc();
} else {
    // No staff found with the provided ID
    echo "No staff found with ID: $id";
    exit();
}

// Handle update form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $emp_num = $_POST['emp_num'];
    $surname = $_POST['surname'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $salary = $_POST['salary'];
    $designation = $_POST['designation'];

    // Set default rating
    if ($designation === 'Pilot') {
        $rating = $_POST['rating']; // Use the selected rating value for Pilots
    } else {
        $rating = 'N/A'; // Set rating to "N/A" for Crew Members
    }

    // Update staff details in the database
    $sql_update = "UPDATE staffs SET
            EmpNum = '$emp_num', 
            SurName = '$surname', 
            Name = '$name', 
            Address = '$address', 
            PhoneNumber = '$phone', 
            Salary = '$salary', 
            Designation = '$designation', 
            Rating = '$rating'
            WHERE ID = $id";

    if ($conn->query($sql_update) === TRUE) {
        header("Location: all_staff_listing.php");
    } else {
        echo "Error updating staff details: " . $conn->error;
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
    <title>Update Staff</title>
</head>
<body>
    <form action="all_staff_listing.php" method="post">
        <input type="submit" value="Back">
    </form>
    <h2>Update Staff</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $id; ?>" method="post">
        <label for="emp_num">Employee Number:</label><br>
        <input type="text" id="emp_num" name="emp_num" value="<?php echo $row_current['EmpNum']; ?>" required><br>
        
        <label for="surname">Surname:</label><br>
        <input type="text" id="surname" name="surname" value="<?php echo $row_current['SurName']; ?>" required><br>
        
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" value="<?php echo $row_current['Name']; ?>" required><br>
        
        <label for="address">Address:</label><br>
        <input type="text" id="address" name="address" value="<?php echo $row_current['Address']; ?>" required><br>
        
        <label for="phone">Phone Number:</label><br>
        <input type="text" id="phone" name="phone" value="<?php echo $row_current['PhoneNumber']; ?>" required><br>
        
        <label for="salary">Salary:</label><br>
        <input type="text" id="salary" name="salary" value="<?php echo $row_current['Salary']; ?>" required><br>
        
        <label for="designation">Designation:</label><br>
        <select id="designation" name="designation" required onchange="showAdditionalDropdown()">
            <option value="Crew Member" <?php if ($row_current['Designation'] === 'Crew Member') echo 'selected'; ?>>Crew Member</option>
            <option value="Pilot" <?php if ($row_current['Designation'] === 'Pilot') echo 'selected'; ?>>Pilot</option>
        </select><br>
        
        <div id="pilotDropdown" <?php if ($row_current['Designation'] !== 'Pilot') echo 'style="display:none;"'; ?>>
            <label for="rating">Rating:</label><br>
            <select id="rating" name="rating">
                <option value="N/A" <?php if ($row_current['rating'] === 'N/A') echo 'selected'; ?>>N/A</option>
                <option value="1" <?php if ($row_current['rating'] === '1') echo 'selected'; ?>>Level 1</option>
                <option value="2" <?php if ($row_current['rating'] === '2') echo 'selected'; ?>>Level 2</option>
                <option value="3" <?php if ($row_current['rating'] === '3') echo 'selected'; ?>>Level 3</option>
                <option value="4" <?php if ($row_current['rating'] === '4') echo 'selected'; ?>>Level 4</option>
                <option value="5" <?php if ($row_current['rating'] === '5') echo 'selected'; ?>>Level 5</option>
            </select><br>
        </div>
        
        <input type="submit" value="Update">
    </form>

    <script>
        function showAdditionalDropdown() {
            var designation = document.getElementById("designation").value;
            var pilotDropdown = document.getElementById("pilotDropdown");

            if (designation === "Pilot") {
                pilotDropdown.style.display = "block";
            } else {
                pilotDropdown.style.display = "none";
            }
        }
    </script>
</body>
</html>
