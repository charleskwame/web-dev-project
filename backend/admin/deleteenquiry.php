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
    $inputID = intval($_GET['enquiryID']);
    $sqlQueryToDeleteEnquiry = "DELETE FROM enquiries WHERE enquiryID = $inputID";
    if (mysqli_query($connection, $sqlQueryToDeleteEnquiry) === TRUE) {
        header("Location: ../../frontend/admin/adminbookingpage.php");
        sleep(1);
    } else {
        echo "Cannot Delete Enquiry at the moment<br>";
    }
}
