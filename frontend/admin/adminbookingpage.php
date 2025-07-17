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

    <!-- dialog to edit booking details -->
    <dialog id="editBookingDialog">
        <form action="../../backend/admin/updatebooking.php" method="post">
            <div>
                <label for="">Enter Booking ID</label>
                <input type="text" name="bookingID" />
            </div>
            <div>
                <label for="">Enter New Customer Name</label>
                <input type="text" name="customerName" id="">
            </div>
            <div>
                <label for="">Enter New Customer Email</label>
                <input type="text" name="customerEmail" id="">
            </div>
            <div>
                <label for="serviceBooked">Select New Service</label>
                <select name="serviceBooked" id="">
                    <option value="none">None</option>
                    <option value="webDevelopment">Web Development</option>
                    <option value="networkServices">Network Services</option>
                    <option value="serverServices">Server Services</option>
                </select>
            </div>
            <input type="submit" value="Update Booking" name="updateBooking">
        </form>
    </dialog>

    <!-- dialog to delete booking -->
    <dialog id="deleteBookingDialog">
        <form action="../../backend/admin/deletebooking.php" method="post">
            <label for="">Enter the ID of the booking to delete</label>
            <input type="text" name="bookingID">
            <input type="submit" value="Delete Booking">
        </form>
    </dialog>
</body>

<script>
    window.onload = () => {
        const editBookingButtons = document.querySelectorAll("#editBookingButton")
        const editBookingDialog = document.getElementById("editBookingDialog")
        editBookingButtons.forEach((editBookingButton) => {
            editBookingButton.addEventListener("click", () => {
                editBookingDialog.showModal()
            })
        })


        const deleteBookingButtons = document.querySelectorAll("#deleteBookingButton")
        const deleteBookingDialog = document.getElementById("deleteBookingDialog")
        deleteBookingButtons.forEach((deleteBookingButton) => {
            deleteBookingButton.addEventListener("click", () => {
                deleteBookingDialog.showModal()
            })
        })
    }
</script>

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

    //$row = $response -> fetch_assoc($response));
    if ($response->num_rows > 0) {

        //echo "<h3>Registered Users</h3>";
        echo "<table border='1' cellpadding = '10'>";
        echo "<tr>
                <th>Booking ID</th>
                <th>Customer Name</th>
                <th>Customer Email</th>
                <th>Service Booked</th>
                <th>Booking Date</th>
                <th>Edit Booking</th>
             </tr>";

        while ($row = mysqli_fetch_assoc($response)) {
            echo "<tr>
                        <td>" . htmlspecialchars($row["bookingID"]) . "</td>
                        <td>" . htmlspecialchars($row["customerName"]) . "</td>
                        <td>" . htmlspecialchars($row["customerEmail"]) . "</td>
                        <td>" . htmlspecialchars($row["serviceBooked"]) . "</td>
                        <td>" . htmlspecialchars($row["bookingDate"]) . "</td>
                        <td>
                            <input type='submit' value='Edit' name='edit' id='editBookingButton'/>
                            <input type='submit' value='Delete' name='delete' id='deleteBookingButton'/>
                        </td>
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
