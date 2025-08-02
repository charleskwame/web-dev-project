<?php
$server = "localhost";
$user = "root";
$password = "";
$database = "nacom_database";
$connection = "";

try {
    // Connect to database
    $connection = mysqli_connect($server, $user, $password, $database);

    // Check if delete button was triggered via GET request
    if (isset($_GET["deleteIDButton"])) {
        // Sanitize and cast adminID to integer
        $inputID = htmlspecialchars(intval($_GET['adminID']));

        // Prepare DELETE SQL statement
        $sqlQueryToDeleteAdminUser = "DELETE FROM admin_users WHERE adminID = $inputID";

        // Execute query and check if successful
        if (mysqli_query($connection, $sqlQueryToDeleteAdminUser) === TRUE) {
            // Redirect immediately on success
            header("Location: ../../frontend/admin/addusers.php");
            exit();  // Stop further script execution
        } else {
            // Optional: handle deletion failure here
            echo "<h2>Failed to delete admin user</h2>";
            exit();
        }
    }
} catch (\Throwable $th) {
    // Display connection error message
    echo "<h2>Cannot connect to the database</h2>";
    exit();
}

// Redirect to add users page if no delete action triggered
header("Location: ../../frontend/admin/addusers.php");
exit();
