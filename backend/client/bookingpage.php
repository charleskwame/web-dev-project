<?php
// establishing connection to database
$server = "localhost";
$user = "root";
$password = "";
$database = "nacom_database";
$connection = "";

try {
    $connection = mysqli_connect($server, $user, $password, $database);
    if ($connection) {
        if (isset($_POST["bookButton"])) {
            $customerName = htmlspecialchars($_POST['customerName']);
            $customerEmail = htmlspecialchars($_POST['customerEmail']);
            $serviceBooked = htmlspecialchars($_POST['serviceBooked']);
            $bookingDate = htmlspecialchars($_POST['bookingDate']);

            // prepare and bind
            $sqlQueryToInsertBookingToDatabase = $connection->prepare("INSERT INTO bookings (customerName, customerEmail, serviceBooked, bookingDate) VALUES (?, ?, ?, ?)");
            $sqlQueryToInsertBookingToDatabase->bind_param("ssss", $customerName, $customerEmail, $serviceBooked, $bookingDate);
            $sqlQueryToInsertBookingToDatabase->execute();
            header("Location: ../../frontend/client/bookingpage.php");
        }
    }
} catch (\Throwable $th) {
    echo "Cannot Connect To Database";
}
