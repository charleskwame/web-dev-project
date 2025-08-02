<?php
// establishing connection to database
$server = "localhost";
$user = "root";
$password = "";
$database = "nacom_database";
$connection = "";

try {
    // connect to MySQL database
    $connection = mysqli_connect($server, $user, $password, $database);

    // check if connection was successful
    if ($connection) {

        // check if form was submitted via POST with "bookButton" name
        if (isset($_POST["bookButton"])) {

            // sanitize input data to prevent XSS attacks
            $customerName = htmlspecialchars($_POST['customerName']);
            $customerEmail = htmlspecialchars($_POST['customerEmail']);
            $serviceBooked = htmlspecialchars($_POST['serviceBooked']);
            $bookingDate = htmlspecialchars($_POST['bookingDate']);

            // prepare an SQL statement to insert booking data
            $sqlQueryToInsertBookingToDatabase = $connection->prepare("INSERT INTO bookings (customerName, customerEmail, serviceBooked, bookingDate) VALUES (?, ?, ?, ?)");

            // bind parameters to the SQL statement (all strings)
            $sqlQueryToInsertBookingToDatabase->bind_param("ssss", $customerName, $customerEmail, $serviceBooked, $bookingDate);

            // execute the prepared statement
            $sqlQueryToInsertBookingToDatabase->execute();

            // redirect user after successful booking insertion and added the toast parameter to make sure we display a success message
            header("Location: ../../frontend/client/bookingpage.php?toast=booking_success");

            // stopping execution of code
            exit();
        }
    }
} catch (\Throwable $th) {
    // catch any errors and display a connection error message
    echo "Cannot Connect To Database";
}
