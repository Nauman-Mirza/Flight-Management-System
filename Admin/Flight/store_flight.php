<?php
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $flightnum = $_POST['flightnum'];
    $origin = $_POST['origin'];
    $dest = $_POST['dest'];
    $date = $_POST['date'];
    $arr_time = $_POST['arr_time'];
    $dep_time = $_POST['dep_time'];
    $planeid = $_POST['plane'];
    $pilotid = $_POST['pilot'];

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "flight_management_system";
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement to insert data into flight table
    $sql = "INSERT INTO flight (flightnum, origin, dest, date, arr_time, dep_time, planeid, pilotid)
            VALUES ('$flightnum', '$origin', '$dest', '$date', '$arr_time', '$dep_time', '$planeid', '$pilotid')";

    // Execute SQL statement
    if ($conn->query($sql) === TRUE) {
         header("Location: all_flight_listing.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close connection
    $conn->close();
} else {
    // Redirect back to form page if accessed directly
    echo "Error: " . $sql . "<br>" . $conn->error;
    exit();
}
?>
