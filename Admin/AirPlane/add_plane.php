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
    <title>Add Plane</title>
</head>
<body>
    <form action="all_plane_listing.php" method="post">
        <input type="submit" value="Back">
    </form>
    <h2>Add Plane</h2>
    <form action="store_plane.php" method="post">
        <label for="ser_num">Serial Number:</label><br>
        <input type="text" id="ser_num" name="ser_num" required><br>
        
        <label for="manufacture">Manufacture:</label><br>
        <input type="text" id="manufacture" name="manufacture" required><br>
        
        <label for="model">Model:</label><br>
        <input type="text" id="model" name="model" required><br><br>

        <label for="rating">Rating:</label><br>
        <select id="rating" name="rating">
        <option value="1">1 star</option>
        <option value="2">2 stars</option>
        <option value="3">3 stars</option>
        <option value="4">4 stars</option>
        <option value="5">5 stars</option>
        </select><br><br>
        
        <input type="submit" value="Add">
    </form>
</body>
</html>
