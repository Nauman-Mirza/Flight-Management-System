
<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to login page if session is not set
    header("Location: ../admin_login.html");
    exit();
}

$email = $_SESSION['email'];
$name = $_SESSION['name'];
$user_id = $_SESSION['user_id'];
$surname = $_SESSION['surname'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Staff Member</title>
</head>
<body>
    <form action="all_staff_listing.php" method="post">
        <input type="submit" value="Back">
    </form>
    <h2>Add Staff Member</h2>
    <form action="store_staff.php" method="post" id="staffForm">
        <label for="emp_num">Employee Number:</label><br>
        <input type="text" id="emp_num" name="emp_num" required><br>
        
        <label for="surname">Surname:</label><br>
        <input type="text" id="surname" name="surname" required><br>
        
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br>
        
        <label for="address">Address:</label><br>
        <input type="text" id="address" name="address" required><br>
        
        <label for="phone">Phone Number:</label><br>
        <input type="text" id="phone" name="phone" required><br>

        <label for="designation">Designation:</label><br>
        <select id="designation" name="designation" required onchange="showAdditionalDropdown()">
            <option value="Crew Member">Crew Member</option>
            <option value="Pilot">Pilot</option>
        </select><br>
        
        <div id="pilotDropdown" style="display:none;">
            <label for="rating">Pilot Rating:</label><br>
            <select id="rating" name="rating">
                <option value="N/A">N/A</option>
                <option value="1">Level 1</option>
                <option value="2">Level 2</option>
                <option value="3">Level 3</option>
                <option value="4">Level 4</option>
                <option value="5">Level 5</option>
            </select><br>
        </div>
        
        <label for="salary">Salary:</label><br>
        <input type="text" id="salary" name="salary" required><br><br>
        
        <input type="submit" value="Add Staff Member">
    </form>

    <script>
        function showAdditionalDropdown() {
            var designation = document.getElementById("designation").value;
            var pilotDropdown = document.getElementById("pilotDropdown");

            if (designation === "Pilot") {
                pilotDropdown.style.display = "block";
            } else {
                pilotDropdown.style.display = "none";
            }
        }
    </script>
</body>
</html>
