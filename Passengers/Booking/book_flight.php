<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to login page if session is not set
    header("Location: login.html");
    exit();
}

// Check if flight ID is provided in the URL
if (isset($_GET['flightnum'])) {
    $flightnum = $_GET['flightnum'];

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

    // Retrieve flight details
    $sql = "SELECT * FROM flight WHERE flightnum = '$flightnum'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Flight details
        $origin = $row['origin'];
        $dest = $row['dest'];
        $date = $row['date'];
        $arr_time = $row['arr_time'];
        $dep_time = $row['dep_time'];

        // Close connection
        $conn->close();
    } else {
        echo "Flight not found.";
        exit();
    }
} else {
    echo "Flight ID not provided.";
    exit();
}

// If the user submits the form to confirm the booking
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input and perform booking process
    $passenger_id = $_SESSION['user_id']; // Assuming user ID is used as passenger ID
    $flight_id = $flightnum; // Assuming flight number is used as flight ID

    // Database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert booking into the database
    $sql = "INSERT INTO bookings (passengerid, flightnum) VALUES ('$passenger_id', '$flight_id')";

    if ($conn->query($sql) === TRUE) {
        echo "Booking successful!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Flight</title>
</head>
<body>
    <form action="../dashboard.php" method="post">
        <input type="submit" value="Back">
    </form>
    <h2>Confirm Booking</h2>
    <p>You are about to book the following flight:</p>
    <p><strong>Flight Number:</strong> <?php echo $flightnum; ?></p>
    <p><strong>Origin:</strong> <?php echo $origin; ?></p>
    <p><strong>Destination:</strong> <?php echo $dest; ?></p>
    <p><strong>Date:</strong> <?php echo $date; ?></p>
    <p><strong>Arrival Time:</strong> <?php echo $arr_time; ?></p>
    <p><strong>Departure Time:</strong> <?php echo $dep_time; ?></p>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?flightnum=' . $flightnum; ?>" method="post">
        <input type="submit" value="Confirm Booking">
    </form>
</body>
</html>
