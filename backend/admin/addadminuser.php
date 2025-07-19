<?php
$server = "localhost";
$user = "root";
$password = "";
$database = "nacom_database";
$connection = "";
try {
    $connection = mysqli_connect($server, $user, $password, $database);
    // updating booking info here
    if (isset($_POST['addAdminUser'])) {
        $adminName = $_POST['adminName'];
        $adminEmail = $_POST['adminEmail'];
        $adminPassword = $_POST['adminPassword'];
        $adminRole = $_POST['adminRole'];

        // prepare and bind
        $sqlInsertAdminQueryStatement = $connection->prepare("INSERT INTO admin_users (adminName, adminEmail, adminPassword, adminRole) VALUES (?, ?, ?, ?)");
        $sqlInsertAdminQueryStatement->bind_param("ssss", $adminName, $adminEmail, $adminPassword, $adminPassword);
        $sqlInsertAdminQueryStatement->execute();

        //$sqlInsertAdminQuery = "INSERT INTO `admin_users`(`adminID`, `adminName`, `adminEmail`, `adminPassword`, `adminRole`) VALUES ('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]')";
    }

    header("Location: ../../frontend/admin/addusers.php");
} catch (\Throwable $th) {
    echo "<h2>Cannot connect to the database</h2>";
}
