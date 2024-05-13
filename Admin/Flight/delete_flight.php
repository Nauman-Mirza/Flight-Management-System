<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to login page if session is not set
    header("Location: ../admin_login.html");
    exit();
}

// Check if flight ID is provided in the URL
if (isset($_GET['flightnum'])) {
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

    $flight_id = $_GET['flightnum'];

    // Delete flight record from the database
    $sql = "DELETE FROM flight WHERE flightnum = $flight_id";

    if ($conn->query($sql) === TRUE) {
        // Redirect to flight listing page after successful deletion
        header("Location: all_flight_listing.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    // Close connection
    $conn->close();
} else {
    echo "Flight ID not provided.";
    exit();
}
?>
