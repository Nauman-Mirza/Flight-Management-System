<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to login page if session is not set
    header("Location: admin_login.html");
    exit();
}

$email = $_SESSION['email'];
$name = $_SESSION['name'];
$user_id = $_SESSION['user_id'];
$surname = $_SESSION['surname'];

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

// Get current date
$currentDate = date("Y-m-d");

// Retrieve upcoming flights with airplane details and pilot's name
$sql = "SELECT f.*, a.SerNum AS planeSerNum, a.Model, s.Name AS pilotName FROM flight f
        INNER JOIN air_planes a ON f.planeid = a.SerNum
        INNER JOIN staffs s ON f.pilotid = s.EmpNum
        WHERE f.date >= '$currentDate' AND f.status = 'pending' ORDER BY f.date ASC";

$result = $conn->query($sql);

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Welcome Admin <?php echo $name . ' ' . $surname; ?></h2>
    <form action="logout.php" method="post">
        <input type="submit" value="Logout">
    </form>
    <h3>Upcoming Flights:</h3>
    <table>
        <tr>
            <th>Flight Number</th>
            <th>Origin</th>
            <th>Intermediate Location</th>
            <th>Destination</th>
            <th>Date</th>
            <th>Arrival Time</th>
            <th>Departure Time</th>
            <th>Plane</th>
            <th>Pilot</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['flightnum'] . "</td>";
                echo "<td>" . $row['origin'] . "</td>";
                echo "<td>" . $row['Intermediate'] . "</td>";
                echo "<td>" . $row['dest'] . "</td>";
                echo "<td>" . $row['date'] . "</td>";
                // Format arrival time
                $arrivalTime = date("h:i a", strtotime($row['arr_time']));
                echo "<td>" . $arrivalTime . "</td>";
                // Format departure time
                $departureTime = date("h:i a", strtotime($row['dep_time']));
                echo "<td>" . $departureTime . "</td>";
                echo "<td>" . $row['planeSerNum'] . " (" . $row['Model'] . ")" . "</td>";
                echo "<td>" . $row['pilotName'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No upcoming flights</td></tr>";
        }
        ?>
    </table>
    <h3>Airplane Feature : </h3>
    <form action="AirPlane/all_plane_listing.php" method="post">
        <input type="submit" value="View All Planes">
    </form>
    <h3>Staff Feature : </h3>
    <form action="Staff/all_staff_listing.php" method="post">
        <input type="submit" value="View All Staff">
    </form>
    <h3>Create Flights : </h3>
    <form action="Flight/all_flight_listing.php" method="post">
        <input type="submit" value="Flights">
    </form>
</body>
</html>
