<!-- php script to connect to the database -->
<?php
// Establishing connection to database
$server = "localhost";
$user = "root";
$password = "";
$database = "nacom_database";
$connection = "";

try {
    // Connect to MySQL database using mysqli
    $connection = mysqli_connect($server, $user, $password, $database);

    // Query to fetch all admin users
    $sqlQueryToViewAdminUsers = "SELECT * FROM admin_users";
    $adminUsersResponse = $connection->query($sqlQueryToViewAdminUsers); // Corrected variable name

    // Check if any rows returned
    if ($adminUsersResponse && $adminUsersResponse->num_rows > 0) {

        // Start table output
        echo "<table cellpadding='0' cellspacing='0' id='bookingTable'>";
        echo "<tr>
                <th>Admin ID</th>
                <th>Admin Name</th>
                <th>Admin Email</th>
                <th>Admin Password</th>
                <th>Admin Role</th>
              </tr>";

        // Loop through each admin user row and display in table
        while ($row = mysqli_fetch_assoc($adminUsersResponse)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row["adminID"]) . "</td>
                    <td>" . htmlspecialchars($row["adminName"]) . "</td>
                    <td>" . htmlspecialchars($row["adminEmail"]) . "</td>
                    <td>" . htmlspecialchars($row["adminPassword"]) . "</td>
                    <td>" . htmlspecialchars($row["adminRole"]) . "</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        // If no admin users found, output JavaScript to update HTML element with message
        echo "<script>
            const noAdminUserHeading = document.getElementById('noAdminUserHeading');
            if (noAdminUserHeading) {
                noAdminUserHeading.innerHTML = 'No Admin Users Have Been Added Yet';
            }
        </script>";
    }

    // Close database connection
    $connection->close();
} catch (\Throwable $th) {
    // Display error message if connection or query fails
    echo "Cannot connect to the database<br>";
}
?>