<?php
// Start the session to access session variables
session_start();

// Clear the admin login email session variable (logs out the admin)
$_SESSION['adminLoggedInEmail'] = "";

// Clear the admin login password session variable (logs out the admin)
$_SESSION['adminLoggedInPassword'] = "";

// Redirect the user to the admin login page after logout
header("Location: ../../frontend/admin/index.php?toast=loggedout");

// Stop further script execution after redirect
exit();
