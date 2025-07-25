<!-- php script to connect to the database -->
<?php
// establishing connection to database
$server = "localhost";
$user = "root";
$password = "";
$database = "nacom_database";
$connection = "";
try {
    $connection = mysqli_connect($server, $user, $password, $database);
    $sqlQueryToViewAdminUsers = "SELECT * FROM admin_users";
    $bookingsResponse = $connection->query($sqlQueryToViewAdminUsers);

    if ($adminUsersResponse->num_rows > 0) {

        // bookings table display
        echo "<table cellpadding = '0' cellspacing='0' id='bookingTable'>";
        echo "<tr>
                <th>Admin ID</th>
                <th>Admin Name</th>
                <th>Admin Email</th>
                <th>Admin Password</th>
                <th>Admin Role</th>
             </tr>";

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
        echo "<script>
        const noAdminUsersHeading = document.getElementById('noAdminUserHeading');
        noAdminUserHeading.innerHTML = 'No Admin Users Have Been Added Yet';
        </script>";
    }
    $connection->close();
} catch (\Throwable $th) {
    echo "Cannot connect to the database<br>";
}
