<?php
$server = "localhost";
$user = "root";
$password = "";
$database = "nacom_database";
$connection = "";
try {
    $connection = mysqli_connect($server, $user, $password, $database);
    //$connection->close();
    if (isset($_GET["deleteIDButton"])) {
        $inputID = intval($_GET['adminID']);
        $sqlQueryToDeleteAdminUser = "DELETE FROM admin_users WHERE adminID = $inputID";
        if (mysqli_query($connection, $sqlQueryToDeleteAdminUser) === TRUE) {
            header("Location: ../../frontend/admin/addusers.php");
        } else {
            echo "<h2>Cannot Delete Admin User at the moment</h2>";
        }
    }
} catch (\Throwable $th) {
    echo "<h2>Cannot connect to the database</h2>";
}
