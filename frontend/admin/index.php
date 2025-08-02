<?php
// Start the session to handle admin login state
session_start();

// Redirect logged-in admin users to the dashboard
if (!empty($_SESSION['adminLoggedInEmail'])) {
    header("Location: ./admindashboard.php");
    exit();
}

// Database connection details
$server = "localhost";
$user = "root";
$password = "";
$database = "nacom_database";

// Connect to MySQL server (initial connection without specifying a DB)
$connection = mysqli_connect($server, $user, $password);

// SQL query to check if the 'nacom_database' exists
$sqlDatabaseCheck = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$database'";
$result = mysqli_query($connection, $sqlDatabaseCheck);

// If the database does not exist, create it
if ($result && mysqli_num_rows($result) == 0) {
    $sqlCreateDatabase = "CREATE DATABASE IF NOT EXISTS $database";
    mysqli_query($connection, $sqlCreateDatabase);
}

// Now connect to the nacom_database
$connectionToDatabase = mysqli_connect($server, $user, $password, $database);

// Check if the 'admin_users' table exists
$sqlTableCheckQuery = "SHOW TABLES LIKE 'admin_users'";
$sqlTableCheckResult = mysqli_query($connectionToDatabase, $sqlTableCheckQuery);

// If the admin_users table does not exist, create it
if (!$sqlTableCheckResult || mysqli_num_rows($sqlTableCheckResult) == 0) {
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

// Check if default admin (Charles) already exists in the table
$checkAdminExists = "SELECT * FROM `admin_users` WHERE `adminEmail` = 'charles@nacom.com'";
$adminExistsResult = mysqli_query($connectionToDatabase, $checkAdminExists);

// If not, insert the default admin account
if ($adminExistsResult && mysqli_num_rows($adminExistsResult) == 0) {
    $sqlQueryToInsertManager = "INSERT INTO `admin_users`(`adminID`, `adminName`, `adminEmail`, `adminPassword`, `adminRole`) 
        VALUES ('','Charles Tetteh','charles@nacom.com','nacomadmin1','Manager')";
    mysqli_query($connectionToDatabase, $sqlQueryToInsertManager);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta and Title -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Log In</title>

    <!-- External Stylesheets -->
    <link rel="stylesheet" href="./index.css?v=<?php echo date('his'); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
</head>

<body id="body">
    <!-- Top Navigation Bar -->
    <nav>
        <a href="../client/index.html">
            <img src="../assets/images/logo.png" alt="logo">
        </a>
    </nav>

    <!-- Admin Login Form -->
    <form action="../../backend/admin/adminlogin.php" method="post" id="adminLogInForm" onsubmit="toast()">
        <h1>Admin Log In</h1>

        <!-- Email Field -->
        <div>
            <label for="adminEmail">Enter Admin Email</label>
            <input type="text" name="adminEmail"
                placeholder="Enter Admin Email"
                pattern="[^@]+@[^\.]+\..+" minlength="3" maxlength="50" required>
        </div>

        <!-- Password Field -->
        <div>
            <label for="adminPassword">Enter Admin Password</label>
            <input type="text" name="adminPassword"
                placeholder="Enter Admin Password" minlength="8" maxlength="15" required>
        </div>

        <!-- Submit Button -->
        <input type="submit" value="Log In" name="adminLogInButton" id="adminLogInButton">
    </form>
</body>

<!-- Toast Notification Library -->
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<script>
    // If screen width is below 800px, hide login form and show message
    if (window.innerWidth < 800) {
        const adminLogInForm = document.getElementById("adminLogInForm");
        adminLogInForm.style.display = "none";

        const screenNotBigParagraphTag = document.createElement("h2");
        screenNotBigParagraphTag.setAttribute("id", "screenNotBigParagraphTag");
        screenNotBigParagraphTag.innerText = "Admin Log In Only Available On Tablets And Desktops";

        document.body.appendChild(screenNotBigParagraphTag);
    }

    // Show toast message when form is submitted
    const toast = () => {
        Toastify({
            text: 'Loggin In...',
            position: 'center',
            duration: 1000,
        }).showToast();
    }
</script>

</html>