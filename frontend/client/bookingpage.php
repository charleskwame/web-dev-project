<?php
// Attempt to connect to MySQL server (without specifying database yet)
$server = "localhost";
$user = "root";
$password = "";
$database = "nacom_database";
$connection = mysqli_connect($server, $user, $password);

// SQL query to check if the specified database exists
$sqlDatabaseCheck = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$database'";
$result = mysqli_query($connection, $sqlDatabaseCheck);

// If database does not exist, create it
if ($result && mysqli_num_rows($result) == 0) {
    $sqlCreateDatabase = "CREATE DATABASE IF NOT EXISTS $database";
    mysqli_query($connection, $sqlCreateDatabase);
}

// Reconnect, this time specifying the newly created or existing database
$connectionToDatabase = mysqli_connect($server, $user, $password, $database);

// SQL query to check if the 'bookings' table exists
$sqlTableCheckQuery = "SHOW TABLES LIKE 'bookings'";
$sqlTableCheckResult = mysqli_query($connectionToDatabase, $sqlTableCheckQuery);

// If the 'bookings' table does not exist, create it
if (!$sqlTableCheckResult || mysqli_num_rows($sqlTableCheckResult) == 0) {
    $sqlCreateBookingTable = "CREATE TABLE `bookings` (
        `bookingID` int(3) NOT NULL AUTO_INCREMENT,
        `customerName` varchar(30) NOT NULL,
        `customerEmail` varchar(50) NOT NULL,
        `serviceBooked` varchar(30) NOT NULL,
        `bookingDate` DATE NOT NULL,
        `bookingStatus` varchar(20) NOT NULL DEFAULT 'Pending',
        PRIMARY KEY (`bookingID`)
    )";
    mysqli_query($connectionToDatabase, $sqlCreateBookingTable);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta tags for character encoding and responsive design -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Stylesheet with cache-busting query string -->
    <link rel="stylesheet" href="./bookingpage.css?v=<?php echo date('his'); ?>">

    <!-- Toastify styles for notification popups -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <title>Book Consultation</title>
</head>

<body>
    <!-- Site header/navigation bar -->
    <div class="header">
        <nav class="nav">
            <!-- Logo -->
            <a href="../client/index.html">
                <img src="../assets/images/logo.png" alt="">
            </a>

            <!-- Navigation links -->
            <div>
                <a href="bookingpage.php" class="active">Book Us</a>
                <a href="enquiriespage.php">Enquiries</a>
                <a href="contactuspage.html">Contact Us</a>
            </div>
        </nav>
    </div>

    <!-- Main content section -->
    <main>
        <!-- Section displaying available services -->
        <div class="our-services">
            <h2>Our Services</h2>
            <div class="services">
                <!-- Each service is represented by an image -->
                <div class="service">
                    <img src="../assets/images/web development.png" alt="">
                </div>
                <div class="service">
                    <img src="../assets/images/mobileapp.png" alt="">
                </div>
                <div class="service">
                    <img src="../assets/images/network.png" alt="">
                </div>
                <div class="service">
                    <img src="../assets/images/server.png" alt="">
                </div>
            </div>
        </div>

        <!-- Booking form section -->
        <div class="booking">
            <h2>Book a consultation with us</h2>
            <div class="booking-form">
                <!-- Form to capture user booking data -->
                <form action="../../backend/client/bookingpage.php" method="post" id="bookingForm" onsubmit="toast()">
                    <!-- Name input -->
                    <div>
                        <label for="">Enter your name</label>
                        <input type="text" name="customerName" onkeydown="return /[a-zA-Z ]/i.test(event.key)"
                            placeholder="Enter your name"
                            minlength="3" maxlength="50" required>
                    </div>

                    <!-- Email input -->
                    <div>
                        <label for="">Enter your email</label>
                        <input type="text" name="customerEmail" pattern="[^@]+@[^\.]+\..+" placeholder="aba@gmail.com"
                            minlength="10" maxlength="50" required>
                    </div>

                    <!-- Dropdown for service selection -->
                    <div>
                        <label for="">Choose Service</label>
                        <select name="serviceBooked" id="" required>
                            <option value="">--Select Service--</option>
                            <option value="Web Design and Development">Web Design And Development</option>
                            <option value="Mobile Application Development">Mobile Application Design</option>
                            <option value="Network Infastructure Set Up">Network Set Up</option>
                            <option value="Server Architecture Set Up">Server Set Up</option>
                        </select>
                    </div>

                    <!-- Date selection (restricted to today and future dates) -->
                    <div>
                        <label for="">Book Consultation day</label>
                        <input type="date" name="bookingDate" id="dateInput" min="<?php echo date("Y-m-d"); ?>" required>
                    </div>

                    <!-- Submit button -->
                    <button type="submit" name="bookButton">Submit Appointment</button>
                </form>
            </div>
        </div>
    </main>
</body>

<!-- Toastify JavaScript for notification popups -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<!-- Function to show toast on form submission -->
<script>
    const toast = () => {
        Toastify({
            text: 'Booking Completed. Waiting Confirmation From Administrator',
            position: 'center',
            duration: 3000,
        }).showToast();
    }
</script>

</html>