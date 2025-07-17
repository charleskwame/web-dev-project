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
    //echo "Connection success";
} catch (\Throwable $th) {
    echo "Cannot connect to the database<br>";
}
