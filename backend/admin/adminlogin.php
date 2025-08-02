<?php
// Start session to store current admin info after login
session_start();

// Database connection variables
$server = "localhost";
$user = "root";
$password = "";
$database = "nacom_database";
$connection = "";

try {
    // Establish connection to the database
    $connection = mysqli_connect($server, $user, $password, $database);

    if ($connection) {
        // Check if login form was submitted
        if (isset($_POST['adminLogInButton'])) {
            // Sanitize user inputs to prevent XSS attacks
            $adminEmail = htmlspecialchars($_POST['adminEmail']);
            $adminPassword = htmlspecialchars($_POST['adminPassword']);

            // Prepare SQL statement to fetch admin credentials matching input email and password
            $sqlQueryToGetAdminCredentials = "SELECT * FROM admin_users WHERE adminEmail = ? AND adminPassword = ?";
            $sqlQueryToGetAdminCredentialsStatement = $connection->prepare($sqlQueryToGetAdminCredentials);

            if ($sqlQueryToGetAdminCredentialsStatement) {
                // Bind input parameters to the prepared statement (both strings)
                $sqlQueryToGetAdminCredentialsStatement->bind_param("ss", $adminEmail, $adminPassword);

                // Execute the statement
                $sqlQueryToGetAdminCredentialsStatement->execute();

                // Get the result set
                $result = $sqlQueryToGetAdminCredentialsStatement->get_result();

                // Fetch the first matching row
                $currentDataFromAdminTable = $result->fetch_assoc();

                // Close the prepared statement
                $sqlQueryToGetAdminCredentialsStatement->close();

                // If matching admin user found
                if (!empty($currentDataFromAdminTable)) {
                    // Double check email and password match (redundant but extra safe)
                    if ($adminEmail === $currentDataFromAdminTable['adminEmail'] && $adminPassword === $currentDataFromAdminTable["adminPassword"]) {
                        // Store admin credentials in session variables for persistent login state
                        $_SESSION['adminLoggedInEmail'] = $adminEmail;
                        $_SESSION['adminLoggedInPassword'] = $adminPassword;

                        // Redirect to admin dashboard after successful login
                        header("Location: ../../frontend/admin/admindashboard.php?toast=login_success");

                        exit();
                    } else {
                        // Credentials do not match — show error
                        echo "<h2>Cannot Log in</h2>";
                    }
                } else {
                    // No matching user found — redirect back to login page
                    header("Location: ../../frontend/admin/index.php?toast=login_failed");
                    exit();
                }
            }
        }
    }
} catch (\Throwable $th) {
    // Display error if unable to connect to database
    echo "Cannot Connect To Database";
}
