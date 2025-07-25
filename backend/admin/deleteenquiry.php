<?php
$server = "localhost";
$user = "root";
$password = "";
$database = "nacom_database";
$connection = "";
try {
    $connection = mysqli_connect($server, $user, $password, $database);
    if (isset($_POST["deleteIDButton"])) {
        $inputID = htmlspecialchars(intval($_POST['enquiryID']));
        $sqlQueryToDeleteEnquiry = "DELETE FROM enquiries WHERE enquiryID = $inputID";
        if (mysqli_query($connection, $sqlQueryToDeleteEnquiry) === TRUE) {
            header("Location: ../../frontend/admin/admindashboard.php");
            sleep(1);
        } else {
            echo "Cannot Delete Enquiry at the moment<br>";
        }
    }
    //$connection->close();
    header("Location: ../../frontend/admin/admindashboard.php");
} catch (\Throwable $th) {
    echo "Cannot connect to the database<br>";
}
