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

    $flightnum = $_GET['flightnum'];

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
        $planeid = $row['planeid'];
        $pilotid = $row['pilotid'];
    } else {
        echo "Flight not found.";
        exit();
    }

    // Close connection
    $conn->close();
} else {
    echo "Flight ID not provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Flight</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <form action="all_flight_listing.php" method="post">
        <input type="submit" value="Back">
    </form>
    <h2>Edit Flight</h2>
    <form action="update_flight_process.php" method="post" id="flightForm">
        <input type="hidden" name="flightnum" value="<?php echo $flightnum; ?>">
        <label for="origin">Origin:</label><br>
        <input type="text" id="origin" name="origin" value="<?php echo $origin; ?>" required><br>
        
        <label for="dest">Destination:</label><br>
        <input type="text" id="dest" name="dest" value="<?php echo $dest; ?>" required><br>
        
        <label for="date">Date:</label><br>
        <input type="date" id="date" name="date" value="<?php echo $date; ?>" required><br>
        
        <label for="arr_time">Arrival Time:</label><br>
        <input type="time" id="arr_time" name="arr_time" value="<?php echo $arr_time; ?>" required><br>
        
        <label for="dep_time">Departure Time:</label><br>
        <input type="time" id="dep_time" name="dep_time" value="<?php echo $dep_time; ?>" required><br>

       <label for="plane">Select Plane:</label><br>
<select id="plane" name="plane" required>
    <?php
    // Database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve available planes
    $sql_planes = "SELECT SerNum, Rating FROM air_planes WHERE Booked = 0";
    $result_planes = $conn->query($sql_planes);

    if ($result_planes->num_rows > 0) {
        // Output data of each row
        while ($row_plane = $result_planes->fetch_assoc()) {
            echo "<option value='" . $row_plane['SerNum'] . "' data-rating='" . $row_plane['Rating'] . "'";
            if ($row_plane['SerNum'] == $planeid) echo " selected";
            echo ">" . $row_plane['SerNum'] . "</option>";
        }
    } else {
        echo "<option value='' disabled>No available planes</option>";
    }

    // Close connection
    $conn->close();
    ?>
</select><br>

        <div id="pilotsDiv">
            <label for="pilot">Select Pilot:</label><br>
            <select id="pilot" name="pilot" required>
                <?php
                // Database connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Retrieve pilot details
                $sql_pilots = "SELECT EmpNum FROM staffs WHERE Designation = 'Pilot' AND Booked = 0 AND Rating = 
                (SELECT Rating FROM air_planes WHERE SerNum = '$planeid')";
                $result_pilots = $conn->query($sql_pilots);

                if ($result_pilots->num_rows > 0) {
                    // Output data of each row
                    while ($row_pilot = $result_pilots->fetch_assoc()) {
                        echo "<option value='" . $row_pilot['EmpNum'] . "'";
                        if ($row_pilot['EmpNum'] == $pilotid) echo " selected";
                        echo ">" . $row_pilot['EmpNum'] . "</option>";
                    }
                } else {
                    echo "<option value='' disabled>No available pilots</option>";
                }

                // Close connection
                $conn->close();
                ?>
            </select><br><br>
        </div>

        <input type="submit" value="Update Flight">
    </form>

    <script>
        $(document).ready(function(){
            $('#plane').change(function(){
                var selectedRating = $(this).find(':selected').data('rating');
                $.ajax({
                    type: 'POST',
                    url: 'get_pilots.php',
                    data: { rating: selectedRating },
                    dataType: 'json',
                    success: function(response){
                        if(response.length > 0){
                            $('#pilot').empty();
                            $.each(response, function(index, pilot){
                                $('#pilot').append('<option value="' + pilot.EmpNum + '">' + pilot.EmpNum + '</option>');
                            });
                        } else {
                            $('#pilot').html('<option value="" disabled>* Pilots not found *</option>');
                        }
                    },
                    error: function(xhr, status, error){
                        console.error(xhr.responseText);
                        alert('Error occurred while retrieving pilots.');
                    }
                });
            });
        });
    </script>
</body>
</html>
