<?php
// Database connection parameters
$server = "localhost";
$user = "root";
$password = "";
$database = "nacom_database";
$connection = "";

try {
    // Establish a connection to the database
    $connection = mysqli_connect($server, $user, $password, $database);

    // Check if the 'admin_users' table exists in the database
    $connectionToDatabase = mysqli_connect($server, $user, $password, $database);
    $sqlTableCheckQuery = "SHOW TABLES LIKE 'admin_users'";
    $sqlTableCheckResult = mysqli_query($connectionToDatabase, $sqlTableCheckQuery);

    if (!$sqlTableCheckResult || mysqli_num_rows($sqlTableCheckResult) == 0) {
        // Table 'admin_users' does not exist, so create it
        $sqlCreateAdminUsersTable = "CREATE TABLE `admin_users` (
            `adminID` int(3) NOT NULL AUTO_INCREMENT,
            `adminName` varchar(30) NOT NULL,
            `adminEmail` varchar(50) NOT NULL,
            `adminPassword` varchar(30) NOT NULL,
            `adminRole` varchar(30) NOT NULL,
            PRIMARY KEY (`adminID`)
        )";
        // Execute the table creation query
        mysqli_query($connectionToDatabase, $sqlCreateAdminUsersTable);
    }

    // Check if the form to add a new admin user was submitted
    if (isset($_POST['addAdminUser'])) {
        // Sanitize the input values to prevent XSS
        $adminName = htmlspecialchars($_POST['adminName']);
        $adminEmail = htmlspecialchars($_POST['adminEmail']);
        $adminPassword = htmlspecialchars($_POST['adminPassword']);
        $adminRole = htmlspecialchars($_POST['adminRole']);

        // Prepare an SQL statement to insert new admin user data securely
        $sqlInsertAdminQueryStatement = $connection->prepare(
            "INSERT INTO admin_users (adminName, adminEmail, adminPassword, adminRole) VALUES (?, ?, ?, ?)"
        );

        // Bind the parameters to the prepared statement
        $sqlInsertAdminQueryStatement->bind_param("ssss", $adminName, $adminEmail, $adminPassword, $adminRole);

        // Execute the statement to insert the new admin user
        $sqlInsertAdminQueryStatement->execute();
    }

    // Redirect back to the admin user management page after insertion
    header("Location: ../../frontend/admin/addusers.php");

    exit();
} catch (\Throwable $th) {
    // Handle any errors during the connection or query execution
    echo "<h2>Cannot connect to the database</h2>";
}
