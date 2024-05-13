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

// Retrieve user ID
$user_id = $_SESSION['user_id'];

// Retrieve all flights booked by the logged-in passenger
$sql = "SELECT b.id, b.flightnum, f.status, f.origin, f.Intermediate, f.dest, f.date, f.arr_time, f.dep_time FROM flight f INNER JOIN bookings b ON b.flightnum = f.flightnum WHERE b.passengerid = '$user_id' and f.status='finished'";
$result = $conn->query($sql);

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight History</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
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
    <form action="../dashboard.php" method="post">
        <input type="submit" value="Back">
    </form>
    <h3>Flight History</h3>
    <table>
        <tr>
            <th>Booking ID</th>
            <th>Flight Number</th>
            <th>Origin</th>
            <th>Intermediate Location</th>
            <th>Destination</th>
            <th>Date</th>
            <th>Arrival Time</th>
            <th>Departure Time</th>
            <th>Flight Status</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['flightnum'] . "</td>";
                echo "<td>" . $row['origin'] . "</td>";
                echo "<td>" . $row['Intermediate'] . "</td>";
                echo "<td>" . $row['dest'] . "</td>";
                echo "<td>" . $row['date'] . "</td>";
                echo "<td>" . date("h:i A", strtotime($row['arr_time'])) . "</td>";
                echo "<td>" . date("h:i A", strtotime($row['dep_time'])) . "</td>";
                echo "<td>" . $row['status'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No flight history found</td></tr>";
        }
        ?>
    </table>
</body>
</html>
