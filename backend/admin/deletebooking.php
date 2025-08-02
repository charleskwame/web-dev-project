<?php
// Database connection details
$server = "localhost";
$user = "root";
$password = "";
$database = "nacom_database";
$connection = "";

try {
    // Connect to MySQL database
    $connection = mysqli_connect($server, $user, $password, $database);

    // Confirm connection (for debugging - you might want to remove in production)
    echo "Connected";

    // Check if delete button was pressed via POST
    if (isset($_POST["deleteIDButton"])) {

        // Sanitize and convert bookingID to integer
        $inputID = htmlspecialchars(intval($_POST['bookingID']));

        // Prepare SQL query to delete booking by ID
        $sqlQueryToDeleteBooking = "DELETE FROM bookings WHERE bookingID = $inputID";

        // Execute deletion query and check if successful
        if (mysqli_query($connection, $sqlQueryToDeleteBooking) === TRUE) {
            // Redirect to admin dashboard after successful deletion
            header("Location: ../../frontend/admin/admindashboard.php");

            // Stop further execution after redirect
            exit();
        } else {
            // Show error if deletion failed
            echo "Cannot Delete User at the moment<br>";
        }
    }

    // Redirect to admin dashboard if no delete was requested or after processing
    header("Location: ../../frontend/admin/admindashboard.php");
    exit();
} catch (\Throwable $th) {
    // Catch and show error if connection fails
    echo "Cannot connect to the database<br>";
}
