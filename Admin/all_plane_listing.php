<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to login page if session is not set
    header("Location: login.html");
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

// Fetch all planes from the air_planes table
$sql = "SELECT * FROM air_planes";
$result = $conn->query($sql);

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Planes Listing</title>
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
    <form action="admin_dashboard.php" method="post">
        <input type="submit" value="Back">
    </form>
    <form action="add_plane.php" method="post">
        <input type="submit" value="Add Plane">
    </form>
    <form action="booked_plane_listing.php" method="post">
        <input type="submit" value="View Booked Plane">
    </form>
    <form action="available_plane_listing.php" method="post">
        <input type="submit" value="Available Booked Plane">
    </form>
    <h2>All Planes Listing</h2>
    <table>
        <thead>
            <tr>
                <th>Serial Number</th>
                <th>Manufacture</th>
                <th>Model</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['SerNum'] . "</td>";
                    echo "<td>" . $row['Manufacture'] . "</td>";
                    echo "<td>" . $row['Model'] . "</td>";
                    echo "<td>" . ($row['Booked'] ? "Booked" : "Available") . "</td>";
                    echo "<td><a href='edit_plane.php?id=" . $row['ID'] . "'>Edit</a> | <a href='delete_plane.php?id=" . $row['ID'] . "'>Delete</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No planes found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
