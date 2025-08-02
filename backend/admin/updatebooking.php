<?php
// Database connection credentials
$server = "localhost";
$user = "root";
$password = "";
$database = "nacom_database";
$connection = "";

try {
    // Establish connection to the MySQL database
    $connection = mysqli_connect($server, $user, $password, $database);

    // Check if the form with name 'updateBooking' was submitted
    if (isset($_POST['updateBooking'])) {

        // Sanitize and convert bookingID to integer to prevent injection
        $bookingID = htmlspecialchars(intval($_POST['bookingID']));

        // Sanitize the new booking status from form input
        $newBookingStatus = htmlspecialchars($_POST['bookingStatus']);

        // Prepare SQL query to select current booking data for the given bookingID
        $sqlSelectQuery = "SELECT * FROM bookings WHERE bookingID = ?";
        $sqlSelectStatement = $connection->prepare($sqlSelectQuery);

        if ($sqlSelectStatement) {
            // Bind bookingID parameter and execute the select query
            $sqlSelectStatement->bind_param("i", $bookingID);
            $sqlSelectStatement->execute();

            // Get the result and fetch the current booking data as an associative array
            $result = $sqlSelectStatement->get_result();
            $currentDataFromTable = $result->fetch_assoc();

            // Close the select statement
            $sqlSelectStatement->close();

            if ($currentDataFromTable) {
                // Initialize array to hold columns to update and parameters to bind
                $updateColumnsArray = [];
                $bindParameters = '';
                $bindValuesArray = [];

                // Check if new booking status is not empty and different from current status
                if (!empty($newBookingStatus) && $newBookingStatus !== $currentDataFromTable["bookingStatus"]) {
                    // Prepare column update statement and parameters
                    $updateColumnsArray[] = 'bookingStatus = ?';
                    $bindParameters .= 's';  // 's' means string type
                    $bindValuesArray[] = $newBookingStatus;
                }

                // Only proceed if there are columns to update
                if (!empty($updateColumnsArray)) {
                    // Add integer type parameter for bookingID in the WHERE clause
                    $bindParameters .= 'i';
                    $bindValuesArray[] = $bookingID;

                    // Build the update query string dynamically
                    $sqlQueryToUpdate = "UPDATE bookings SET " . implode(",", $updateColumnsArray) . " WHERE bookingID = ?";

                    // Prepare the update SQL statement
                    $sqlUpdateStatement = $connection->prepare($sqlQueryToUpdate);

                    if ($sqlUpdateStatement) {
                        // Bind parameters dynamically using splat operator (...), for PHP 5.6+
                        mysqli_stmt_bind_param($sqlUpdateStatement, $bindParameters, ...$bindValuesArray);

                        // Execute the update statement
                        if ($sqlUpdateStatement->execute()) {
                            // Check if any rows were affected (updated)
                            if ($sqlUpdateStatement->affected_rows > 0) {
                                echo "<script>alert('Booking Data Updated Successfully')</script>";
                            } else {
                                // No rows affected means old data equals new data
                                echo "<script>alert('Booking Data Not Changed. The Old Data Is The Same As The New One.')</script>";
                            }
                        } else {
                            // Execution error
                            echo "<script>alert('Error Executing Update')</script>";
                        }

                        // Close the update statement
                        $sqlUpdateStatement->close();
                    } else {
                        // Error preparing update statement
                        echo "<script>alert('Error, Preparing Statement')</script>";
                    }
                } else {
                    // No changes detected to update
                    echo "<script>alert('Error, No Changes Detected In New Data.')</script>";
                }
            } else {
                // Booking ID not found in database
                echo "<script>alert('Error, User ID Not Found In Database.')</script>";
            }
        } else {
            // Error preparing or executing select statement
            echo "<script>alert('Error, Cannot Get Data To Update From Database.')</script>";
        }
    }

    // Redirect back to the admin dashboard page
    header("Location: ../../frontend/admin/admindashboard.php");
} catch (\Throwable $th) {
    // Catch any exceptions or errors and display connection error message
    echo "<h2>Cannot connect to the database</h2>";
}
