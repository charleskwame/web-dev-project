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
        if (isset($_POST["enquiryButton"])) {
            $customerName = htmlspecialchars($_POST['customerName']);
            $customerEmail = htmlspecialchars($_POST['customerEmail']);
            $customerPhoneNumber = htmlspecialchars($_POST['customerPhoneNumber']);
            $enquiryQuestion = htmlspecialchars($_POST['enquiryQuestion']);

            // prepare and bind
            $sqlQueryToInsertBookingToDatabase = $connection->prepare("INSERT INTO enquiries (customerName, customerEmail, customerPhoneNumber, enquiryQuestion) VALUES (?, ?, ?, ?)");
            $sqlQueryToInsertBookingToDatabase->bind_param("ssss", $customerName, $customerEmail, $customerPhoneNumber, $enquiryQuestion);
            $sqlQueryToInsertBookingToDatabase->execute();
            header("Location: ../../frontend/client/enquiriespage.php");
        }
    }
} catch (\Throwable $th) {
    echo "Cannot Connect To Database";
}
