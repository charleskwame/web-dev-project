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

        <a href="../../frontend/admin/addusers.php"><button>
                Add a new admin user
            </button></a>
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
                    <option value="">None</option>
                    <option value="Web Development">Web Development</option>
                    <option value="Network Services">Network Services</option>
                    <option value="Server Services">Server Services</option>
                </select>
            </div>
            <div>
                <label for="">Enter New Booking Date</label>
                <input type="date" name="bookingDate" id="">
            </div>
            <div class="dialogDiv">
                <input type="submit" value="Update Booking" name="updateBooking" id="updateBookingButton">
                <button id="cancelUpdateButton">Cancel Update</button>
            </div>
        </form>
    </dialog>

    <!-- dialog to delete booking -->
    <dialog id="deleteBookingDialog">
        <form action="../../backend/admin/deletebooking.php" method="get">
            <label for="">Enter the ID of the booking to delete</label>
            <input type="text" name="bookingID" required>
            <div class="dialogDiv">
                <button id="cancelDeleteButton">Cancel Delete</button>
                <input type="submit" value="Delete Booking" name="deleteIDButton" id="confirmDeleteButton">
            </div>
        </form>
    </dialog>

    <!-- dialog to delete enquiry -->
    <dialog id="deleteEnquiryDialog">
        <form action="../../backend/admin/deleteenquiry.php" method="get">
            <label for="">Enter the ID of the enquiry to delete</label>
            <input type="text" name="enquiryID" required>
            <div class="dialogDiv">
                <button id="cancelDeleteEnquiryButton">Cancel Delete</button>
                <input type="submit" value="Delete Enquiry" name="deleteIDButton" id="confirmDeleteButton">
            </div>
        </form>
    </dialog>

    <section id="bookingSection">
        <!-- booking heading -->
        <div id="bookingHeadingAndButtonsDiv">
            <h1 id="bookingHeading">Current Bookings</h1>
            <div>
                <button id="editBookingButton">Edit Booking</button>
                <button id="deleteBookingButton">Delete Booking</button>
            </div>
        </div>

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
            $sqlQueryToViewBookings = "SELECT * FROM bookings";

            $bookingsResponse = $connection->query($sqlQueryToViewBookings);

            if ($bookingsResponse->num_rows > 0) {
                // bookings table display
                echo "<table cellpadding = '0' cellspacing='0' id='bookingTable'>";
                echo "<tr>
                <th>Booking ID</th>
                <th>Customer Name</th>
                <th>Customer Email</th>
                <th>Service Booked</th>
                <th>Booking Date</th>
             </tr>";

                while ($row = mysqli_fetch_assoc($bookingsResponse)) {
                    echo "<tr>
                        <td>" . htmlspecialchars($row["bookingID"]) . "</td>
                        <td>" . htmlspecialchars($row["customerName"]) . "</td>
                        <td>" . htmlspecialchars($row["customerEmail"]) . "</td>
                        <td>" . htmlspecialchars($row["serviceBooked"]) . "</td>
                        <td>" . htmlspecialchars($row["bookingDate"]) . "</td>
                        </tr>";
                }
                echo "</table>";
            } else {
                echo "<script>
        const noBookingHeading = document.createElement('h2')
        noBookingHeading.setAttribute('id','noBookingHeading');
        noBookingHeading.innerHTML = 'No Bookings Have Been Made Yet';
        const bookingSection = document.getElementById('bookingSection')
        bookingSection.appendChild(noBookingHeading)
        </script>";
            }
            $connection->close();
        } catch (\Throwable $th) {
            echo "Cannot connect to the database<br>";
        }
        ?>
    </section>

    <hr id="divider">

    <!-- div for enquiries table -->
    <section id="enquiriesSection">
        <div id="enquiriesHeadingAndButtonsDiv">
            <h1 id="enquiriesHeading">Current Enquiries</h1>
            <div>
                <button id="deleteEnquiryButton">Delete Enquiry</button>
            </div>
        </div>


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
            $sqlQueryToViewEnquiries = "SELECT * FROM enquiries";

            $enquiriesResponse = $connection->query($sqlQueryToViewEnquiries);
            if ($enquiriesResponse->num_rows > 0) {
                // enquiries table display
                echo "<table cellpadding = '0' cellspacing='0' id='enquiriesTable'>";
                echo "<tr>
                <th>Enquiry ID</th>
                <th>Customer Name</th>
                <th>Customer Email</th>
                <th>Customer Phone</th>
                <th>Enquiry Question</th>    
             </tr>";

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
                echo "<script>
              const noEnquiryHeading = document.createElement('h2')
        noEnquiryHeading.setAttribute('id','noEnquiryHeading');
        noEnquiryHeading.innerHTML = 'No Enquiries Have Been Made Yet';
        const enquiriesSection = document.getElementById('enquiriesSection')
        enquiriesSection.appendChild(noEnquiryHeading)
        </script>";
            }

            $connection->close();
        } catch (\Throwable $th) {
            echo "Cannot connect to the database<br>";
        }
        ?>
    </section>
</body>

<script>
    window.onload = () => {
        if (window.innerWidth < 800) {
            const bookingSection = document.getElementById("bookingSection")
            const enquiriesSection = document.getElementById("enquiriesSection")
            const bookingTableHeading = document.getElementById("bookingHeading")
            const noEnquiriesTableHeading = document.getElementById("noEnquiryHeading")
            const noBookingTableHeading = document.getElementById("noBookingHeading")
            const divider = document.getElementById("divider")
            const screenNotBigParagraphTag = document.createElement("h2")
            screenNotBigParagraphTag.setAttribute("id", "screenNotBigParagraphTag")
            screenNotBigParagraphTag.innerText = "Dashboard Only Available On Tablets And Desktops"
            document.body.appendChild(screenNotBigParagraphTag)
            bookingSection.style.display = "none"
            enquiriesSection.style.display = "none"
            divider.style.display = "none"
        } else {
            const editBookingButtons = document.querySelectorAll("#editBookingButton")
            const editBookingDialog = document.getElementById("editBookingDialog")
            editBookingButtons.forEach((editBookingButton) => {
                editBookingButton.addEventListener("click", () => {
                    editBookingDialog.showModal()
                })
            })

            const cancelUpdateButton = document.getElementById("cancelUpdateButton")
            cancelUpdateButton.addEventListener("click", () => {
                editBookingDialog.close()
            })



            const deleteBookingButtons = document.querySelectorAll("#deleteBookingButton")
            const deleteBookingDialog = document.getElementById("deleteBookingDialog")
            deleteBookingButtons.forEach((deleteBookingButton) => {
                deleteBookingButton.addEventListener("click", () => {
                    deleteBookingDialog.showModal()
                })
            })
            const cancelDeleteButton = document.getElementById("cancelDeleteButton")
            cancelDeleteButton.addEventListener("click", () => {
                deleteBookingDialog.close()
            })


            const deleteEnquiryButtons = document.querySelectorAll("#deleteEnquiryButton")
            const deleteEnquiryDialog = document.getElementById("deleteEnquiryDialog")
            deleteEnquiryButtons.forEach((deleteEnquiryButton) => {
                deleteEnquiryButton.addEventListener("click", () => {
                    deleteEnquiryDialog.showModal()
                })
            })
            const cancelDeleteEnquiryButton = document.getElementById("cancelDeleteEnquiryButton")
            cancelDeleteEnquiryButton.addEventListener("click", () => {
                deleteEnquiryDialog.close()
            })

            const noBookingTableHeading = document.getElementById("noBookingHeading")
            const bookingSection = document.getElementById("bookingSection")
            const bookingTable = document.getElementById("bookingTable")
            bookingSection.appendChild(bookingTable)

            const noEnquiriesTableHeading = document.getElementById("noEnquiryHeading")
            const enquiriesSection = document.getElementById("enquiriesSection")
            const enquiriesTable = document.getElementById("enquiriesTable")
            enquiriesSection.appendChild(enquiriesTable)
        }
    }
</script>

</html>