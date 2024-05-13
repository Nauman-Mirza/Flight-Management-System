<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to login page if session is not set
    header("Location: login.html");
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

// Initialize variables
$origin = "";
$destination = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $origin = isset($_POST['origin']) ? $_POST['origin'] : "";
    $destination = isset($_POST['destination']) ? $_POST['destination'] : "";
}

// Retrieve upcoming flights based on search criteria
$currentDateTime = date('Y-m-d H:i:s');
$sql = "SELECT * FROM flight WHERE date >= CURDATE() AND CONCAT(date, ' ', dep_time) > '$currentDateTime' AND status = 'pending'";
if (!empty($origin)) {
    $sql .= " AND origin LIKE '%$origin%'";
}
if (!empty($destination)) {
    $sql .= " AND dest LIKE '%$destination%'";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passenger Dashboard</title>
    <style>
        /* Global styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Navbar styles */
        .navbar {
            background-color: #2196F3;
            overflow: hidden;
            padding: 10px 0;
        }

        .navbar a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
            font-size: 18px;
        }

        /* Sidebar styles */
        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            z-index: 1;
            top: 60px;
            left: 0;
            background-color: #2196F3;
            padding-top: 20px;
            margin-top: 10px;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 16px;
            text-decoration: none;
            font-size: 18px;
        }

        .sidebar a:hover {
            background-color: #ddd;
            color: #333;
        }

        /* Content styles */
        .content {
            margin-left: 250px;
            padding: 20px;
        }

        /* Table styles */
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        .book-btn {
            background-color: #4CAF50;
            color: white;
            padding: 8px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <a href="#">FMS</a>
        <a href="logout.php" style="float: right;">Logout</a>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="dashboard.php">Upcoming Flights</a>
        <a href="Booking/view_bookings.php">Passenger Bookings</a>
        <a href="History/view_flight_history.php">Flight History</a>
    </div>

    <!-- Content -->
    <div class="content">
        <!-- <h2>Welcome <?php echo $name . ' ' . $surname; ?></h2> -->

        <!-- Search Flights Form -->
        <h3>Search Flights</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="origin">Origin:</label>
            <input type="text" id="origin" name="origin" value="<?php echo $origin; ?>">
            <label for="destination">Destination:</label>
            <input type="text" id="destination" name="destination" value="<?php echo $destination; ?>">
            <input type="submit" value="Search">
        </form>

        <!-- Upcoming Flights -->
        <h3 id="upcoming">Upcoming Flights - Book Now</h3>
        <table>
            <tr>
                <th>Flight Number</th>
                <th>Origin</th>
                <th>Intermediate Location</th>
                <th>Destination</th>
                <th>Date</th>
                <th>Arrival Time</th>
                <th>Departure Time</th>
                <th>Action</th>
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
                    echo "<td>" . date("h:i A", strtotime($row['arr_time'])) . "</td>";
                    echo "<td>" . date("h:i A", strtotime($row['dep_time'])) . "</td>";
                    echo "<td><a href='Booking/book_flight.php?flightnum=" . $row['flightnum'] . "' class='book-btn'>Book Now</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No upcoming flights</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
