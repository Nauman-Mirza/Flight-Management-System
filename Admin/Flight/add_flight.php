<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Flight</title>
</head>
<body>
    <h2>Add Flight</h2>
    <form action="add_flight.php" method="post" id="flightForm">
        <label for="flightnum">Flight Number:</label><br>
        <input type="text" id="flightnum" name="flightnum" required><br>
        
        <label for="origin">Origin:</label><br>
        <input type="text" id="origin" name="origin" required><br>
        
        <label for="dest">Destination:</label><br>
        <input type="text" id="dest" name="dest" required><br>
        
        <label for="date">Date:</label><br>
        <input type="date" id="date" name="date" required><br>
        
        <label for="arr_time">Arrival Time:</label><br>
        <input type="time" id="arr_time" name="arr_time" required><br>
        
        <label for="dep_time">Departure Time:</label><br>
        <input type="time" id="dep_time" name="dep_time" required><br>
        
        <label for="plane">Select Plane:</label><br>
        <select id="plane" name="plane" required onchange="showPilots()">
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "flight_management_system";

            // Database connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Retrieve available planes
            $sql_planes = "SELECT SerNum FROM air_planes WHERE Booked = 0";
            $result_planes = $conn->query($sql_planes);

            if ($result_planes->num_rows > 0) {
                // Output data of each row
                while ($row_plane = $result_planes->fetch_assoc()) {
                    echo "<option value='" . $row_plane['SerNum'] . "'>" . $row_plane['SerNum'] . "</option>";
                }
            } else {
                echo "<option value=''>No available planes</option>";
            }

            // Close connection
            $conn->close();
            ?>
        </select><br>

        <div id="pilotsDiv" style="display: none;">
            <label for="pilot">Select Pilot:</label><br>
            <select id="pilot" name="pilot" required>
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "flight_management_system";

                // Database connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Retrieve available pilots
                $sql_pilots = "SELECT EmpNum, Name, Surname FROM staffs WHERE Designation = 'Pilot' AND Booked = 0";
                $result_pilots = $conn->query($sql_pilots);

                if ($result_pilots->num_rows > 0) {
                    // Output data of each row
                    while ($row_pilot = $result_pilots->fetch_assoc()) {
                        echo "<option value='" . $row_pilot['EmpNum'] . "'>" . $row_pilot['Name'] . " " . $row_pilot['Surname'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No available pilots</option>";
                }

                // Close connection
                $conn->close();
                ?>
            </select><br><br>
        </div>

        <input type="submit" value="Add Flight">
    </form>

    <script>
        function showPilots() {
            var planeDropdown = document.getElementById("plane");
            var pilotsDiv = document.getElementById("pilotsDiv");

            if (planeDropdown.value !== "") {
                pilotsDiv.style.display = "block";
            } else {
                pilotsDiv.style.display = "none";
            }
        }
    </script>
</body>
</html>
