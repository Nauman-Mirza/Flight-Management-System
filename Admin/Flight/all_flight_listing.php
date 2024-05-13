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

// Fetch all flights from the flight table
$sql = "SELECT f.*, a.SerNum AS planeSerNum, a.Model AS planeModel, s.Name AS pilotName FROM flight f
        INNER JOIN air_planes a ON f.planeid = a.SerNum
        INNER JOIN staffs s ON f.pilotid = s.EmpNum";
$result = $conn->query($sql);

// Function to fetch crew members for a flight
function getCrewMembers($conn, $flightnum) {
    $crewMembers = "";
    $sql = "SELECT crewmembers FROM flight WHERE flightnum = '$flightnum'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $crewMemberList = json_decode($row['crewmembers'], true);
        if (!empty($crewMemberList)) {
            $crewMembers = implode(", ", $crewMemberList);
        } else {
            $crewMembers = "No Crew Members";
        }
    } else {
        $crewMembers = "No Crew Members";
    }
    return $crewMembers;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Flight Listing</title>
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
    <form action="add_flight.php" method="post">
        <input type="submit" value="Add New Flight">
    </form>
    <h2>All Flight Listing</h2>
    <table>
        <thead>
            <tr>
                <th>Flight Num</th>
                <th>Origin</th>
                <th>Dest</th>
                <th>Date</th>
                <th>Arrival Time</th>
                <th>Departure Time</th>
                <th>Plane</th>
                <th>Pilot</th>
                <th>Crew Members</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['flightnum'] . "</td>";
                    echo "<td>" . $row['origin'] . "</td>";
                    echo "<td>" . $row['dest'] . "</td>";
                    echo "<td>" . $row['date'] . "</td>";
                    echo "<td>" . $row['arr_time'] . "</td>";
                    echo "<td>" . $row['dep_time'] . "</td>";
                    echo "<td>" . $row['planeSerNum'] . " (" . $row['planeModel'] . ")" . "</td>";
                    echo "<td>" . $row['pilotName'] . "</td>";
                    echo "<td>" . getCrewMembers($conn, $row['flightnum']) . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo "<td>
                            
                            <a href='delete_flight.php?flightnum=" . $row['flightnum'] . "'>Delete</a> | 
                            <a href='mark_flight.php?flightnum=" . $row['flightnum'] . "'>Finish Flight</a>
                          </td>";

                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='10'>No Flights found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
