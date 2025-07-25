<?php
// checking if database exists and creating it if not found
$server = "localhost";
$user = "root";
$password = "";
$database = "nacom_database";
$connection = mysqli_connect($server, $user, $password);
$sqlDatabaseCheck = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$database'";
$result = mysqli_query($connection, $sqlDatabaseCheck);
if ($result && mysqli_num_rows($result) == 0) {
    // echo "does not exist";
    $sqlCreateDatabase = "CREATE DATABASE IF NOT EXISTS $database";
    mysqli_query($connection, $sqlCreateDatabase);
}
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

    // automatically adding admin credentials of nacom manager (Charles) to be used for sign in
    $sqlQueryToInsertManager = "INSERT INTO `admin_users`(`adminID`, `adminName`, `adminEmail`, `adminPassword`, `adminRole`) VALUES ('','Charles Tetteh','charles@nacom.com','nacomadmin1','Manager')";
    mysqli_query($connectionToDatabase, $sqlQueryToInsertManager);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./index.css?v=<?php echo date('his'); ?>">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <title>Admin Log In</title>
</head>

<body id="body">
    <nav>
        <a href="../client/index.html">
            <img src="../assets/images/logo.png" alt="logo">
        </a>
    </nav>
    <form action="../../backend/admin/adminlogin.php" method="post" id="adminLogInForm" onsubmit="toast()">
        <h1>Admin Log In</h1>
        <div>

            <label for="adminEmail">Enter Admin Email</label>
            <!-- <input type="text" name="adminEmail" required> -->
            <input type="text" name="adminEmail" onkeydown="return /[a-zA-Z@0-9.]/i.test(event.key)"
                placeholder="Enter Admin Email" pattern="[^@]+@[^\.]+\..+" minlength="3" maxlength="50" required>
        </div>
        <div>

            <label for="adminPassword">Enter Admin Password</label>
            <input type="text" name="adminPassword" placeholder="Enter Admin Password" minlength="8" maxlength="15"
                required>
        </div>
        <input type="submit" value="Log In" name="adminLogInButton" id="adminLogInButton">
    </form>
</body>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js">

</script>

<script>
    if (window.innerWidth < 800) {
        const adminLogInForm = document.getElementById("adminLogInForm")
        adminLogInForm.style.display = "none"
        const screenNotBigParagraphTag = document.createElement("h2")
        screenNotBigParagraphTag.setAttribute("id", "screenNotBigParagraphTag")
        screenNotBigParagraphTag.innerText = "Admin Log In Only Available On Tablets And Desktops"
        document.body.appendChild(screenNotBigParagraphTag)
    }
    const toast = () => {
        Toastify({
            text: 'Loggin In...',
            position: 'center',
            duration: 1000,
        }).showToast();
    }
</script>

</html>