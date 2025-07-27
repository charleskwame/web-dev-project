<?php
$server = "localhost";
$user = "root";
$password = "";
$database = "nacom_database";
$connection = "";
try {
    $connection = mysqli_connect($server, $user, $password, $database);
    // updating booking info here

    // Check if 'bookings' table exists in the newly created database
    $connectionToDatabase = mysqli_connect($server, $user, $password, $database);
    $sqlTableCheckQuery = "SHOW TABLES LIKE 'admin_users'";
    $sqlTableCheckResult = mysqli_query($connectionToDatabase, $sqlTableCheckQuery);

    if (!$sqlTableCheckResult || mysqli_num_rows($sqlTableCheckResult) == 0) {
        // Table does not exist, create it
        $sqlCreateAdminUsersTable = "CREATE TABLE `admin_users` (
        `adminID` int(3) NOT NULL AUTO_INCREMENT,
        `adminName` varchar(30) NOT NULL,
        `adminEmail` varchar(50) NOT NULL,
        `adminPassword` varchar(30) NOT NULL,
        `adminRole` varchar(30) NOT NULL,
        PRIMARY KEY (`adminID`)
    )";
        mysqli_query($connectionToDatabase, $sqlCreateAdminUsersTable);
    }


    if (isset($_POST['addAdminUser'])) {
        $adminName = htmlspecialchars($_POST['adminName']);
        $adminEmail = htmlspecialchars($_POST['adminEmail']);
        $adminPassword = htmlspecialchars($_POST['adminPassword']);
        $adminRole = htmlspecialchars($_POST['adminRole']);

        // prepare and bind
        $sqlInsertAdminQueryStatement = $connection->prepare("INSERT INTO admin_users (adminName, adminEmail, adminPassword, adminRole) VALUES (?, ?, ?, ?)");
        $sqlInsertAdminQueryStatement->bind_param("ssss", $adminName, $adminEmail, $adminPassword, $adminRole);
        $sqlInsertAdminQueryStatement->execute();

        //$sqlInsertAdminQuery = "INSERT INTO `admin_users`(`adminID`, `adminName`, `adminEmail`, `adminPassword`, `adminRole`) VALUES ('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]')";
    }


    sleep(1);
    header("Location: ../../frontend/admin/addusers.php");
} catch (\Throwable $th) {
    echo "<h2>Cannot connect to the database</h2>";
}
