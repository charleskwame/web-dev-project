
<?php
// starting session to store current admin information
session_start();
// establishing connection to database
$server = "localhost";
$user = "root";
$password = "";
$database = "nacom_database";
$connection = "";
try {
    $connection = mysqli_connect($server, $user, $password, $database);
    if ($connection) {
        // getting admin credentials to compare to database
        if (isset($_POST['adminLogInButton'])) {
            $adminEmail = htmlspecialchars($_POST['adminEmail']);
            $adminPassword = htmlspecialchars($_POST['adminPassword']);

            // Sql Query To retrieve credentials from database and check
            $sqlQueryToGetAdminCredentials = "SELECT * FROM admin_users WHERE adminEmail = ? AND adminPassword = ?";
            $sqlQueryToGetAdminCredentialsStatement = $connection->prepare($sqlQueryToGetAdminCredentials);

            if ($sqlQueryToGetAdminCredentialsStatement) {
                $sqlQueryToGetAdminCredentialsStatement->bind_param("ss", $adminEmail, $adminPassword);
                $sqlQueryToGetAdminCredentialsStatement->execute();
                $result = $sqlQueryToGetAdminCredentialsStatement->get_result();
                $currentDataFromAdminTable = $result->fetch_assoc();
                $sqlQueryToGetAdminCredentialsStatement->close();

                if (!empty($currentDataFromAdminTable)) {
                    if ($adminEmail === $currentDataFromAdminTable['adminEmail'] && $adminPassword === $currentDataFromAdminTable["adminPassword"]) {
                        $_SESSION['adminLoggedInEmail'] = $adminEmail;
                        $_SESSION['adminLoggedInPassword'] = $adminPassword;
                        header("Location: ../../frontend/admin/admindashboard.php");
                        sleep(1);
                    } else {
                        echo "<h2>Cannot Log in</h2>";
                    }
                } else {
                    header("Location: ../../frontend/admin/index.php");
                }
            }
        }
    }
} catch (\Throwable $th) {
    echo "Cannot Connect To Database";
}
?>
