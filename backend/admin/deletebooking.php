<?php
$server = "localhost";
$user = "root";
$password = "";
$database = "nacom_database";
$connection = "";
try {
    $connection = mysqli_connect($server, $user, $password, $database);
    echo "Connected";
    //$connection->close();
} catch (\Throwable $th) {
    echo "Cannot connect to the database<br>";
}

if (isset($_GET["deleteIDButton"])) {
    $inputID = intval($_GET['bookingID']);
    $sqlQueryToDeleteBooking = "DELETE FROM bookings WHERE bookingID = $inputID";
    if (mysqli_query($connection, $sqlQueryToDeleteBooking) === TRUE) {
        header("Location: ../../frontend/admin/adminbookingpage.php");
        sleep(1);
    } else {
        echo "Cannot Delete User at the moment<br>";
    }
}
