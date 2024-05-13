<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Flight</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
</head>
<body>
    <form action="all_flight_listing.php" method="post">
        <input type="submit" value="Back">
    </form>
    <h2>Add Flight</h2>
    <form action="store_flight.php" method="post" id="flightForm">
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
                echo "<option value=''>Select</option>";
                while ($row_plane = $result_planes->fetch_assoc()) {
                    echo "<option value='" . $row_plane['SerNum'] . "' data-rating='" . $row_plane['Rating'] . "'>" . $row_plane['SerNum'] . "</option>";
                }
            } else {
                echo "<option value='' disabled>No available planes</option>";
            }

            // Close connection
            $conn->close();
            ?>
        </select><br>

        <div id="pilotsDiv" style="display: none;">
            <label for="pilot">Select Pilot:</label><br>
            <select id="pilot" name="pilot" required></select><br><br>
        </div>

        <div id="crewMembersDiv">
    <label for="crew">Select Crew Members:</label><br>
    <select id="crew" name="crew[]" multiple required>
        <?php
        // Database connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Retrieve all available crew members
        $sql_crew = "SELECT EmpNum FROM staffs WHERE Designation = 'Crew Member' AND Booked = 0";
        $result_crew = $conn->query($sql_crew);

        if ($result_crew->num_rows > 0) {
            // Output data of each row
            while ($row_crew = $result_crew->fetch_assoc()) {
                echo "<option value='" . $row_crew['EmpNum'] . "'>" . $row_crew['EmpNum'] . "</option>";
            }
        } else {
            echo "<option value='' disabled>No available crew members</option>";
        }

        // Close connection
        $conn->close();
        ?>
    </select><br><br>
</div>


        <input type="submit" value="Add Flight">
    </form>

    <script>
    $(document).ready(function(){
        // Initialize Select2 for crew members select element
        $('#crew').select2();
        
        $('#plane').change(function(){
            if ($(this).val() === "") {
                $('#pilotsDiv').hide();
                $('#crewMembersDiv').hide();
            } else {
                $('#pilotsDiv').show();
                $('#crewMembersDiv').show();
                var selectedRating = $(this).find(':selected').data('rating');
                $.ajax({
                    type: 'POST',
                    url: 'get_pilots.php',
                    data: { rating: selectedRating },
                    dataType: 'json',
                    success: function(response){
                        $('#pilot').empty();
                        if(response.length > 0){
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
            }
        });

        // Client-side validation for date and time
        $('#flightForm').submit(function(e) {
            var selectedDate = new Date($('#date').val());
            var selectedTime = new Date('1970-01-01T' + $('#dep_time').val());
            var currentDateTime = new Date();

            if (selectedDate < new Date().setHours(0,0,0,0)) {
                alert('Please select a date equal to or later than today.');
                e.preventDefault();
            } else if (selectedDate === new Date().setHours(0,0,0,0) && selectedTime < currentDateTime) {
                alert('Please select a departure time later than the current time.');
                e.preventDefault();
            }
        });
    });
</script>

</body>
</html>
