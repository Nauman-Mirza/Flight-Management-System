<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to login page if session is not set
    header("Location: ../admin_login.html");
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

// Fetch all staff members from the staffs table
$sql = "SELECT * FROM staffs";
$result = $conn->query($sql);

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Staff Listing</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <form action="../admin_dashboard.php" method="post">
        <input type="submit" value="Back">
    </form>
    <form action="add_staff.php" method="post">
        <input type="submit" value="Add Staff">
    </form>
    <form action="booked_staff_listing.php" method="post">
        <input type="submit" value="View Booked Staff">
    </form>
    <form action="available_staff_listing.php" method="post">
        <input type="submit" value="View Available Staff">
    </form>
    <h2>All Staff Listing</h2>
    <table>
        <thead>
            <tr>
                <th>Employee Number</th>
                <th>Surname</th>
                <th>Name</th>
                <th>Address</th>
                <th>Phone Number</th>
                <th>Salary</th>
                <th>Designation</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['EmpNum'] . "</td>";
                    echo "<td>" . $row['SurName'] . "</td>";
                    echo "<td>" . $row['Name'] . "</td>";
                    echo "<td>" . $row['Address'] . "</td>";
                    echo "<td>" . $row['PhoneNumber'] . "</td>";
                    echo "<td>" . $row['Salary'] . "</td>";
                    echo "<td>" . $row['Designation'] . "</td>";
                    echo "<td>" . ($row['Booked'] ? "Booked" : "Available") . "</td>";
                    echo "<td><a href='update_staff.php?id=" . $row['ID'] . "'>Update</a> | <a href='delete_staff.php?id=" . $row['ID'] . "'>Delete</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No staff members found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
