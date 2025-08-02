<!-- Ensure admin is logged in; otherwise redirect to login page -->
<?php
session_start();
if (empty($_SESSION['adminLoggedInEmail'])) {
    header("Location: ./index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Link to admin dashboard stylesheet with cache busting -->
    <link rel="stylesheet" href="./admindashboard.css?v= <?php echo date('his'); ?>" />
    <!-- Toastify CSS for notifications -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" />
    <title>Admin Dashboard</title>
</head>

<body>
    <nav>
        <!-- Logo in navigation -->
        <img src="../assets/images/logo.png" alt="logo" />

        <div>
            <!-- Link to manage admin users -->
            <a href="../../frontend/admin/addusers.php">
                <button>Manage Admin Users</button>
            </a>
            <!-- Logout button -->
            <a href="../../backend/admin/logout.php">
                <button id="logOutButton">Log Out</button>
            </a>
        </div>
    </nav>

    <!-- Dialog to edit booking details -->
    <dialog id="editBookingDialog">
        <form action="../../backend/admin/updatebooking.php" method="post" onsubmit="toast('Updating Booking Details')">
            <div>
                <label for="">Enter Booking ID</label>
                <!-- Numeric input validation to only allow digits -->
                <input type="text" name="bookingID" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" minlength="1" maxlength="3" required />
            </div>
            <div>
                <label for="bookingStatus">Select New Booking Status</label>
                <select name="bookingStatus" id="" required>
                    <option value="">None</option>
                    <option value="Pending">Pending</option>
                    <option value="Confirmed">Confirmed</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>
            <div class="dialogDiv">
                <!-- Cancel button to close dialog -->
                <button id="cancelUpdateButton">Cancel Update</button>
                <input type="submit" value="Update Booking Status" name="updateBooking" id="updateBookingButton" />
            </div>
        </form>
    </dialog>

    <!-- Dialog to delete booking -->
    <dialog id="deleteBookingDialog">
        <form action="../../backend/admin/deletebooking.php" method="post" onsubmit="toast('Deleting Booking')">
            <label for="">Enter the ID of the booking to delete</label>
            <input type="text" name="bookingID" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" minlength="1" maxlength="3" required />
            <div class="dialogDiv">
                <button id="cancelDeleteButton">Cancel Delete</button>
                <input type="submit" value="Delete Booking" name="deleteIDButton" id="confirmDeleteButton" />
            </div>
        </form>
    </dialog>

    <!-- Dialog to delete enquiry -->
    <dialog id="deleteEnquiryDialog">
        <form action="../../backend/admin/deleteenquiry.php" method="post" onsubmit="toast('Deleting Enquiry')">
            <label for="">Enter the ID of the enquiry to delete</label>
            <input type="text" name="enquiryID" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" minlength="1" maxlength="3" required />
            <div class="dialogDiv">
                <button id="cancelDeleteEnquiryButton">Cancel Delete</button>
                <input type="submit" value="Delete Enquiry" name="deleteIDButton" id="confirmDeleteButton" />
            </div>
        </form>
    </dialog>

    <section id="bookingSection">
        <!-- Booking section header with action buttons -->
        <div id="bookingHeadingAndButtonsDiv">
            <h1 id="bookingHeading">Current Bookings</h1>
            <div>
                <button id="editBookingButton">Edit Booking Status</button>
                <button id="deleteBookingButton">Delete Booking</button>
            </div>
        </div>

        <!-- PHP block to fetch and display bookings -->
        <?php
        // Database connection variables
        $server = "localhost";
        $user = "root";
        $password = "";
        $database = "nacom_database";
        $connection = "";

        try {
            // Connect to database
            $connection = mysqli_connect($server, $user, $password, $database);
            $sqlQueryToViewBookings = "SELECT * FROM bookings";
            $bookingsResponse = $connection->query($sqlQueryToViewBookings);

            if ($bookingsResponse->num_rows > 0) {
                // If bookings found, create a table and populate it
                echo "<table cellpadding='0' cellspacing='0' id='bookingTable'>";
                echo "<tr>
                    <th>Booking ID</th>
                    <th>Customer Name</th>
                    <th>Customer Email</th>
                    <th>Service Booked</th>
                    <th>Booking Date</th>
                    <th>Booking Status</th>
                </tr>";

                // Loop through each booking and display sanitized data
                while ($row = mysqli_fetch_assoc($bookingsResponse)) {
                    echo "<tr>
                        <td>" . htmlspecialchars($row["bookingID"]) . "</td>
                        <td>" . htmlspecialchars($row["customerName"]) . "</td>
                        <td>" . htmlspecialchars($row["customerEmail"]) . "</td>
                        <td>" . htmlspecialchars($row["serviceBooked"]) . "</td>
                        <td>" . htmlspecialchars($row["bookingDate"]) . "</td>
                        <td>" . htmlspecialchars($row["bookingStatus"]) . "</td>
                    </tr>";
                }
                echo "</table>";
            } else {
                // If no bookings, dynamically show message in the booking section
                echo "<script>
                    const noBookingHeading = document.createElement('h2');
                    noBookingHeading.setAttribute('id', 'noBookingHeading');
                    noBookingHeading.innerHTML = 'No Bookings Have Been Made Yet';
                    const bookingSection = document.getElementById('bookingSection');
                    bookingSection.appendChild(noBookingHeading);
                </script>";
            }
            // Close the connection after use
            $connection->close();
        } catch (\Throwable $th) {
            echo "Cannot connect to the database<br>";
        }
        ?>
    </section>

    <hr id="divider" />

    <section id="enquiriesSection">
        <!-- Enquiries section header with action button -->
        <div id="enquiriesHeadingAndButtonsDiv">
            <h1 id="enquiriesHeading">Current Enquiries</h1>
            <div>
                <button id="deleteEnquiryButton">Delete Enquiry</button>
            </div>
        </div>

        <!-- PHP block to fetch and display enquiries -->
        <?php
        // Database connection variables reused here
        $server = "localhost";
        $user = "root";
        $password = "";
        $database = "nacom_database";
        $connection = "";

        try {
            // Connect to database
            $connection = mysqli_connect($server, $user, $password, $database);
            $sqlQueryToViewEnquiries = "SELECT * FROM enquiries";
            $enquiriesResponse = $connection->query($sqlQueryToViewEnquiries);

            if ($enquiriesResponse->num_rows > 0) {
                // If enquiries found, create a table and populate it
                echo "<table cellpadding='0' cellspacing='0' id='enquiriesTable'>";
                echo "<tr>
                    <th>Enquiry ID</th>
                    <th>Customer Name</th>
                    <th>Customer Email</th>
                    <th>Customer Phone</th>
                    <th>Enquiry Question</th>    
                </tr>";

                // Loop through each enquiry and display sanitized data
                while ($row = mysqli_fetch_assoc($enquiriesResponse)) {
                    echo "<tr>
                        <td>" . htmlspecialchars($row["enquiryID"]) . "</td>
                        <td>" . htmlspecialchars($row["customerName"]) . "</td>
                        <td>" . htmlspecialchars($row["customerEmail"]) . "</td>
                        <td>" . htmlspecialchars($row["customerPhoneNumber"]) . "</td>
                        <td>" . htmlspecialchars($row["enquiryQuestion"]) . "</td>
                    </tr>";
                }
                echo "</table>";
            } else {
                // If no enquiries, dynamically show message in enquiries section
                echo "<script>
                    const noEnquiryHeading = document.createElement('h2');
                    noEnquiryHeading.setAttribute('id', 'noEnquiryHeading');
                    noEnquiryHeading.innerHTML = 'No Enquiries Have Been Made Yet';
                    const enquiriesSection = document.getElementById('enquiriesSection');
                    enquiriesSection.appendChild(noEnquiryHeading);
                </script>";
            }
            // Close connection
            $connection->close();
        } catch (\Throwable $th) {
            echo "Cannot connect to the database<br>";
        }
        ?>
    </section>
</body>

<script>
    window.onload = () => {
        // Responsive behavior: if screen smaller than 800px, hide main sections and show a message
        if (window.innerWidth < 800) {
            const bookingSection = document.getElementById("bookingSection");
            const enquiriesSection = document.getElementById("enquiriesSection");
            const divider = document.getElementById("divider");

            // Create and display warning message
            const screenNotBigParagraphTag = document.createElement("h2");
            screenNotBigParagraphTag.setAttribute("id", "screenNotBigParagraphTag");
            screenNotBigParagraphTag.innerText = "Dashboard Only Available On Tablets And Desktops";
            document.body.appendChild(screenNotBigParagraphTag);

            // Hide sections
            bookingSection.style.display = "none";
            enquiriesSection.style.display = "none";
            divider.style.display = "none";
        } else {
            // Setup event listeners for dialog buttons on larger screens

            // Edit booking dialog open/close
            const editBookingButtons = document.querySelectorAll("#editBookingButton");
            const editBookingDialog = document.getElementById("editBookingDialog");
            editBookingButtons.forEach((button) => {
                button.addEventListener("click", () => {
                    editBookingDialog.showModal();
                });
            });
            const cancelUpdateButton = document.getElementById("cancelUpdateButton");
            cancelUpdateButton.addEventListener("click", () => {
                editBookingDialog.close();
            });

            // Delete booking dialog open/close
            const deleteBookingButtons = document.querySelectorAll("#deleteBookingButton");
            const deleteBookingDialog = document.getElementById("deleteBookingDialog");
            deleteBookingButtons.forEach((button) => {
                button.addEventListener("click", () => {
                    deleteBookingDialog.showModal();
                });
            });
            const cancelDeleteButton = document.getElementById("cancelDeleteButton");
            cancelDeleteButton.addEventListener("click", () => {
                deleteBookingDialog.close();
            });

            // Delete enquiry dialog open/close
            const deleteEnquiryButtons = document.querySelectorAll("#deleteEnquiryButton");
            const deleteEnquiryDialog = document.getElementById("deleteEnquiryDialog");
            deleteEnquiryButtons.forEach((button) => {
                button.addEventListener("click", () => {
                    deleteEnquiryDialog.showModal();
                });
            });
            const cancelDeleteEnquiryButton = document.getElementById("cancelDeleteEnquiryButton");
            cancelDeleteEnquiryButton.addEventListener("click", () => {
                deleteEnquiryDialog.close();
            });

            // Append booking and enquiry tables if they exist, to ensure correct display
            const bookingSection = document.getElementById("bookingSection");
            const bookingTable = document.getElementById("bookingTable");
            if (bookingTable) bookingSection.appendChild(bookingTable);

            const enquiriesSection = document.getElementById("enquiriesSection");
            const enquiriesTable = document.getElementById("enquiriesTable");
            if (enquiriesTable) enquiriesSection.appendChild(enquiriesTable);
        }
    };

    // Toastify notification helper function
    const toast = (actionName) => {
        Toastify({
            text: actionName,
            position: 'center',
            duration: 1000,
        }).showToast();
    };
</script>

<!-- Toastify JS for toast notifications -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

</html>