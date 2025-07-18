<?php
$server = "localhost";
$user = "root";
$password = "";
$database = "nacom_database";
$connection = "";
try {
    $connection = mysqli_connect($server, $user, $password, $database);
} catch (\Throwable $th) {
    echo "Cannot connect to the database<br>";
}

// updating booking info here
if (isset($_POST['updateBooking'])) {
    $bookingID = intval($_POST['bookingID']);
    $newCustomerName = $_POST['customerName'];
    $newCustomerEmail = $_POST['customerEmail'];
    $newServiceBooked = $_POST['serviceBooked'];
    $newBookingDate = $_POST['bookingDate'];


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

            if (!empty($newCustomerName) && $newCustomerName !== $currentDataFromTable["customerName"]) {
                $updateColumnsArray[] = 'customerName = ?';
                $bindParameters .= 's';
                $bindValuesArray[] = $newCustomerName;
            }

            if (!empty($newCustomerEmail) && $newCustomerEmail !== $currentDataFromTable["customerEmail"]) {
                $updateColumnsArray[] = 'customerEmail = ?';
                $bindParameters .= 's';
                $bindValuesArray[] = $newCustomerEmail;
            }

            if (!empty($newServiceBooked) && $newServiceBooked !== $currentDataFromTable["serviceBooked"]) {
                $updateColumnsArray[] = 'serviceBooked = ?';
                $bindParameters .= 's';
                $bindValuesArray[] = $newServiceBooked;
            }

            if (!empty($newBookingDate) && $newBookingDate !== $currentDataFromTable["bookingDate"]) {
                $updateColumnsArray[] = 'bookingDate = ?';
                $bindParameters .= 's';
                $bindValuesArray[] = $newBookingDate;
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

header("Location: ../../frontend/admin/adminbookingpage.php");

// <?php
// // Assume $conn is your mysqli connection object, established earlier.

// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {

//     // Get the user ID being updated and cast to integer
//     $id = intval($_POST['update_id']);

//     // Capture updated values from the form
//     $newFullname = $_POST['fullname'];
//     $newEmail = $_POST['email'];
//     $newUsername = $_POST['username'];
//     $newGender = $_POST['gender'];
//     $newDob = $_POST['dob'];
//     $newMobile = $_POST['mobile'];

//     // 1. Fetch the current user data from the database
//     $selectSql = "SELECT fullname, email, username, gender, dob, mobile FROM registration2 WHERE id = ?";
//     $stmtSelect = $conn->prepare($selectSql);

//     if ($stmtSelect) {
//         $stmtSelect->bind_param("i", $id);
//         $stmtSelect->execute();
//         $result = $stmtSelect->get_result();
//         $currentUserData = $result->fetch_assoc();
//         $stmtSelect->close();

//         if ($currentUserData) {
//             $updateFields = []; // Array to store parts of the SET clause
//             $bindParams = '';   // String to build the type definition for bind_param
//             $bindValues = [];   // Array to store the actual values to bind

//             // 2. Compare new values with current values and add to update list if changed
//             if ($newFullname !== $currentUserData['fullname']) {
//                 $updateFields[] = "fullname = ?";
//                 $bindParams .= 's'; // 's' for string
//                 $bindValues[] = $newFullname;
//             }

//             if ($newEmail !== $currentUserData['email']) {
//                 $updateFields[] = "email = ?";
//                 $bindParams .= 's'; // 's' for string
//                 $bindValues[] = $newEmail;
//             }

//             if ($newUsername !== $currentUserData['username']) {
//                 $updateFields[] = "username = ?";
//                 $bindParams .= 's'; // 's' for string
//                 $bindValues[] = $newUsername;
//             }

//             if ($newGender !== $currentUserData['gender']) {
//                 $updateFields[] = "gender = ?";
//                 $bindParams .= 's'; // 's' for string
//                 $bindValues[] = $newGender;
//             }

//             if ($newDob !== $currentUserData['dob']) {
//                 $updateFields[] = "dob = ?";
//                 $bindParams .= 's'; // 's' for string
//                 $bindValues[] = $newDob;
//             }

//             if ($newMobile !== $currentUserData['mobile']) {
//                 $updateFields[] = "mobile = ?";
//                 $bindParams .= 's'; // 's' for string
//                 $bindValues[] = $newMobile;
//             }

//             // 3. Construct and execute the UPDATE query only if there are changes
//             if (!empty($updateFields)) {
//                 // Add the ID to the bind values for the WHERE clause
//                 $bindParams .= 'i'; // 'i' for integer
//                 $bindValues[] = $id;

//                 $sqlQueryToUpdate = "UPDATE registration2 SET " . implode(", ", $updateFields) . " WHERE id = ?";

//                 $stmtUpdate = $conn->prepare($sqlQueryToUpdate);

//                 if ($stmtUpdate) {
//                     // Dynamically bind parameters using the splat operator (...)
//                     // This unpacks the $bindValues array into individual arguments for bind_param
//                     mysqli_stmt_bind_param($stmtUpdate, $bindParams, ...$bindValues);

//                     if ($stmtUpdate->execute()) {
//                         if ($stmtUpdate->affected_rows > 0) {
//                             // Successfully updated at least one row/column
//                             // You might want to add a success message here or handle it client-side
//                         } else {
//                             // No rows were actually changed (e.g., submitted data was identical)
//                             // You might want to add a message indicating no changes were needed
//                         }
//                     } else {
//                         // Error during execution
//                         echo "Error executing update: " . $stmtUpdate->error . "<br>";
//                     }
//                     $stmtUpdate->close();
//                 } else {
//                     // Error preparing statement
//                     echo "Error preparing update statement: " . $conn->error . "<br>";
//                 }
//             } else {
//                 // No changes detected, so no UPDATE query was necessary
//                 // You might want to display a message to the user that nothing was updated
//                 echo "No changes submitted for user ID: " . $id . "<br>";
//             }
//         } else {
//             // User with provided ID not found
//             echo "User with ID " . $id . " not found.<br>";
//         }
//     } else {
//         // Error preparing the SELECT statement
//         echo "Error preparing select statement: " . $conn->error . "<br>";
//     }

//     // Redirect to the same page without the query string to avoid resubmitting on refresh
//     // This redirect should happen whether an update occurred or not, to prevent resubmission.
//     header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
//     exit();
// }
// 
