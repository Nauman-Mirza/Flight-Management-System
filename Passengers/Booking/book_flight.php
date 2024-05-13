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


        /* Toast message styles */
        .toast {
            visibility: hidden;
            min-width: 250px;
            margin-left: -125px;
            background-color: #4CAF50;
            color: #fff;
            text-align: center;
            border-radius: 2px;
            padding: 16px;
            position: fixed;
            z-index: 1;
            left: 50%;
            bottom: 30px;
        }

        .toast.show {
            visibility: visible;
            -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
            animation: fadein 0.5s, fadeout 0.5s 2.5s;
        }

        @-webkit-keyframes fadein {
            from {bottom: 0; opacity: 0;}
            to {bottom: 30px; opacity: 1;}
        }

        @keyframes fadein {
            from {bottom: 0; opacity: 0;}
            to {bottom: 30px; opacity: 1;}
        }

        @-webkit-keyframes fadeout {
            from {bottom: 30px; opacity: 1;}
            to {bottom: 0; opacity: 0;}
        }

        @keyframes fadeout {
            from {bottom: 30px; opacity: 1;}
            to {bottom: 0; opacity: 0;}
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="#">FMS</a>
        <a href="../logout.php" style="float: right;">Logout</a>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="../dashboard.php">Upcoming Flights</a>
        <a href="view_bookings.php">Passenger Bookings</a>
        <a href="../History/view_flight_history.php">Flight History</a>
    </div>

    <!-- Content -->
    <div class="content">
        
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

    </div>

    <!-- Toast message -->
    <div id="toast" class="toast">Flight booked successfully!</div>

    <script>
        // Show toast message
        function showToast() {
            var toast = document.getElementById("toast");
            toast.className = "toast show";
            setTimeout(function(){
                toast.className = toast.className.replace("show", ""); 
            }, 3000);
        }

        // Check if booking is successful and show toast message
        <?php if ($_SERVER["REQUEST_METHOD"] == "POST") { ?>
            showToast();
        <?php } ?>
    </script>
</body>
</html>

