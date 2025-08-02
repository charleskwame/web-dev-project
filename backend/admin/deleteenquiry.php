<?php
// Database connection parameters
$server = "localhost";
$user = "root";
$password = "";
$database = "nacom_database";
$connection = "";

try {
    // Establish connection to the MySQL database
    $connection = mysqli_connect($server, $user, $password, $database);

    // Check if the delete button was pressed (form submitted)
    if (isset($_POST["deleteIDButton"])) {

        // Sanitize and convert enquiryID input to integer
        $inputID = htmlspecialchars(intval($_POST['enquiryID']));

        // Prepare SQL query to delete the enquiry with the specified ID
        $sqlQueryToDeleteEnquiry = "DELETE FROM enquiries WHERE enquiryID = $inputID";

        // Execute the delete query and check if it was successful
        if (mysqli_query($connection, $sqlQueryToDeleteEnquiry) === TRUE) {
            // Redirect to admin dashboard after successful deletion
            header("Location: ../../frontend/admin/admindashboard.php?toast=deleteenquiry_success");

            // Stop further script execution after redirect
            exit();
        } else {
            // Output error message if deletion failed
            echo "Cannot Delete Enquiry at the moment<br>";
        }
    }

    // Redirect to admin dashboard if no delete button was pressed or after processing
    header("Location: ../../frontend/admin/admindashboard.php");
    exit();
} catch (\Throwable $th) {
    // Catch and display error if connection or query fails
    echo "Cannot connect to the database<br>";
}
