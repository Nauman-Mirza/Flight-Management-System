<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to login page if session is not set
    header("Location: admin_login.html");
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
    <title>Admin Dashboard</title>
</head>
<body>
    <h2>Welcome Admin <?php echo $name . ' ' . $surname; ?></h2>
    <form action="logout.php" method="post">
        <input type="submit" value="Logout">
    </form>
    <h3>Airplace Feature : </h3>
    <form action="AirPlane/all_plane_listing.php" method="post">
        <input type="submit" value="View All Planes">
    </form>
    <h3>Staff Feature : </h3>
    <form action="Staff/all_staff_listing.php" method="post">
        <input type="submit" value="View All Staff">
    </form>
</body>
</html>
