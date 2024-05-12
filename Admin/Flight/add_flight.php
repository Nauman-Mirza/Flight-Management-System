<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Flight</title>
</head>
<body>
    <h2>Add Flight</h2>
    <form action="add_flight.php" method="post">
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
        <select id="plane" name="plane" required>
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
            $sql_planes = "SELECT SerNum, Rating FROM air_planes WHERE Booked = 0";
            $result_planes = $conn->query($sql_planes);

            if ($result_planes->num_rows > 0) {
                // Output data of each row
                while ($row_plane = $result_planes->fetch_assoc()) {
                    echo "<option value='" . $row_plane['SerNum'] . "' data-rating='" . $row_plane['Rating'] . "'>" . $row_plane['SerNum'] . "</option>";
                }
            } else {
                echo "<option value=''>No available planes</option>";
            }

            // Close connection
            $conn->close();
            ?>
        </select><br>

        <!-- Pilot Dropdown will be added here dynamically -->

        <input type="submit" value="Add Flight">
    </form>

    <script>
        document.getElementById('plane').addEventListener('change', function() {
            var selectedPlane = this.value;
            var selectedPlaneRating = this.options[this.selectedIndex].getAttribute('data-rating');

            // AJAX request to get available pilots based on plane rating
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'get_available_pilots.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.getElementById('pilot-dropdown').innerHTML = xhr.responseText;
                }
            };
            xhr.send('rating=' + selectedPlaneRating);
        });
    </script>
</body>
</html>
