<?php
$server = "localhost";
$user = "root";
$password = "";
$database = "nacom_database";
$connection = "";
try {
    $connection = mysqli_connect($server, $user, $password, $database);
    // updating booking info here
    if (isset($_POST['updateBooking'])) {
        $bookingID = htmlspecialchars(intval($_POST['bookingID']));
        $newBookingStatus = htmlspecialchars($_POST['bookingStatus']);
        // getting data from table to modify
        $sqlSelectQuery = "SELECT * FROM bookings WHERE bookingID = ?";
        $sqlSelectStatement = $connection->prepare($sqlSelectQuery);

        if ($sqlSelectStatement) {
            $sqlSelectStatement->bind_param("i", $bookingID);
            $sqlSelectStatement->execute();
            $result = $sqlSelectStatement->get_result();
            $currentDataFromTable = $result->fetch_assoc();
            $sqlSelectStatement->close();

            if ($currentDataFromTable) {
                $updateColumnsArray = [];
                $bindParameters = '';
                $bindValuesArray = [];

                if (!empty($newBookingStatus) && $newBookingStatus !== $currentDataFromTable["bookingStatus"]) {
                    $updateColumnsArray[] = 'bookingStatus = ?';
                    $bindParameters .= 's';
                    $bindValuesArray[] = $newBookingStatus;
                }

                if (!empty($updateColumnsArray)) {
                    $bindParameters .= 'i';
                    $bindValuesArray[] = $bookingID;

                    $sqlQueryToUpdate = "UPDATE bookings SET " . implode(",", $updateColumnsArray) . " WHERE bookingID = ?";

                    $sqlUpdateStatement = $connection->prepare($sqlQueryToUpdate);

                    if ($sqlUpdateStatement) {
                        mysqli_stmt_bind_param($sqlUpdateStatement, $bindParameters, ...$bindValuesArray);

                        if ($sqlUpdateStatement->execute()) {
                            if ($sqlUpdateStatement->affected_rows > 0) {
                                echo "<script>alert('Booking Data Updated Successfully')</script>";
                            } else {
                                echo "<script>alert('Booking Data Not Changed. The Old Data Is The Same As The New One.')</script>";
                            }
                        } else {
                            echo "<script>alert('Error Executing Update')</script>";
                        }
                        $sqlUpdateStatement->close();
                    } else {
                        echo "<script>alert('Error, Preparing Statement')</script>";
                    }
                } else {
                    echo "<script>alert('Error, No Changes Detected In New Data.')</script>";
                }
            } else {
                echo "<script>alert('Error, User ID Not Found In Database.')</script>";
            }
        } else {
            echo "<script>alert('Error, Cannot Get Data To Update From Database.')</script>";
        }
    }
    sleep(1);
    header("Location: ../../frontend/admin/admindashboard.php");
} catch (\Throwable $th) {
    echo "<h2>Cannot connect to the database</h2>";
}
