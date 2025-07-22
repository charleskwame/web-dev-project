<?php
session_start();
$_SESSION['adminLoggedInEmail'] = "";
$_SESSION['adminLoggedInPassword'] = "";
header("Location: ../../frontend/admin/adminloginpage.html");
