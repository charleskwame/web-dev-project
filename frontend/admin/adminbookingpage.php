<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./adminbookingpage.css?v= <?php echo date('his'); ?>">
    <title>Admin Booking Dashboard</title>
</head>

<body>
    <nav>
        <img src="../assets/images/logo.png" alt="logo">
    </nav>
    <p>booking table goes here</p>
</body>

</html>


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
    $sqlQueryToViewTable = "SELECT * FROM bookings";

    $response = $connection->query($sqlQueryToViewTable);

    if ($response->num_rows > 0) {
        //echo "<h3>Registered Users</h3>";
        echo "<table border='1' cellpadding = '10'>";
        echo "<tr>
                <th>Booking ID</th>
                <th>Customer Name</th>
                <th>Customer Email</th>
                <th>Service Booked</th>
                <th>Password</th>
                <th>Gender</th>
                <th>Date of birth</th>
                <th>Phone number</th>
             </tr>";

        //while ($row = $response -> fetch_assoc())
        while ($row = mysqli_fetch_assoc($response)) {
            echo "<tr>
                        <td>" . htmlspecialchars($row["memberID"]) . "</td>
                        <td>" . htmlspecialchars($row["fullname"]) . "</td>
                        <td>" . htmlspecialchars($row["email"]) . "</td>
                        <td>" . htmlspecialchars($row["username"]) . "</td>
                        <td>" . htmlspecialchars($row["password"]) . "</td>
                        <td>" . htmlspecialchars($row["gender"]) . "</td>
                        <td>" . htmlspecialchars($row["dateofbirth"]) . "</td>
                        <td>" . htmlspecialchars($row["mobile"]) . "</td>
                        </tr>";
        }
        echo "</table>";
    } else {
        echo "No data found.";
    }

    $connection->close();
    //echo "Connection success";
} catch (\Throwable $th) {
    echo "Cannot connect to the database<br>";
}

// getting data from booking database and displaying it
// $connection = new mysqli("localhost", "root", "", "club");

// if ($connection->connect_error) {
//     die("Connection failed: " . $connection->connect_error);
// }
