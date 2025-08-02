<?php
// Database connection details
$server = "localhost";
$user = "root";
$password = "";
$database = "nacom_database";
$connection = "";

try {
    // Establish connection to the MySQL database
    $connection = mysqli_connect($server, $user, $password, $database);

    // Check if connection was successful
    if ($connection) {

        // Check if the enquiry form was submitted with the button named "enquiryButton"
        if (isset($_POST["enquiryButton"])) {

            // Sanitize user input to prevent XSS attacks
            $customerName = htmlspecialchars($_POST['customerName']);
            $customerEmail = htmlspecialchars($_POST['customerEmail']);
            $customerPhoneNumber = htmlspecialchars($_POST['customerPhoneNumber']);
            $enquiryQuestion = htmlspecialchars($_POST['enquiryQuestion']);

            // Prepare an SQL statement to insert the enquiry data safely
            $sqlQueryToInsertBookingToDatabase = $connection->prepare(
                "INSERT INTO enquiries (customerName, customerEmail, customerPhoneNumber, enquiryQuestion) VALUES (?, ?, ?, ?)"
            );

            // Bind parameters to the prepared statement (all strings)
            $sqlQueryToInsertBookingToDatabase->bind_param("ssss", $customerName, $customerEmail, $customerPhoneNumber, $enquiryQuestion);

            // Execute the prepared statement to insert the enquiry into the database
            $sqlQueryToInsertBookingToDatabase->execute();

            // Redirect user back to the enquiries page after submission
            header("Location: ../../frontend/client/enquiriespage.php?toast=enquiries_success");

            // Stop further script execution after redirect
            exit();
        }
    }
} catch (\Throwable $th) {
    // Handle any errors, such as connection failures, by displaying a message
    echo "Cannot Connect To Database";
}
