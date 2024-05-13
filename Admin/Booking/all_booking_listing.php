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

// Fetch all bookings with passenger and flight details
$sql = "SELECT b.*, p.Name AS PassengerName, p.SurName AS PassengerSurname, p.PhoneNumber, p.Address,
                f.origin AS FlightOrigin, f.dest AS FlightDestination, 
                DATE_FORMAT(f.date, '%Y-%m-%d') AS FlightDate,
                TIME_FORMAT(f.arr_time, '%h:%i %p') AS FlightArrivalTime, 
                TIME_FORMAT(f.dep_time, '%h:%i %p') AS FlightDepartureTime
        FROM bookings b
        INNER JOIN passengers p ON b.passengerid = p.ID
        INNER JOIN flight f ON b.flightnum = f.flightnum";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Booking Listing</title>
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
    <h2>All Booking Listing</h2>
    <table>
        <thead>
            <tr>
                <th>Passenger Name</th>
                <th>Passenger Surname</th>
                <th>Phone Number</th>
                <th>Address</th>
                <th>Flight Origin</th>
                <th>Flight Destination</th>
                <th>Flight Date</th>
                <th>Flight Arrival Time</th>
                <th>Flight Departure Time</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['PassengerName'] . "</td>";
                    echo "<td>" . $row['PassengerSurname'] . "</td>";
                    echo "<td>" . $row['PhoneNumber'] . "</td>";
                    echo "<td>" . $row['Address'] . "</td>";
                    echo "<td>" . $row['FlightOrigin'] . "</td>";
                    echo "<td>" . $row['FlightDestination'] . "</td>";
                    echo "<td>" . $row['FlightDate'] . "</td>";
                    echo "<td>" . $row['FlightArrivalTime'] . "</td>";
                    echo "<td>" . $row['FlightDepartureTime'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No Bookings found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
