<!-- starting session to make sure admin is logged in -->
<?php
session_start();
if (empty($_SESSION['adminLoggedInEmail'])) {
    header("Location: ./index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./addusers.css?v= <?php echo date('his'); ?>">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <title>Admin Add Users</title>
</head>

<body>
    <nav>
        <img src="../assets/images/logo.png" alt="logo">

        <a href="../../frontend/admin/admindashboard.php"><button>
                View Bookings & Enquiries
            </button></a>
    </nav>

    <!-- adding admin user -->
    <dialog id="addAdminUserDialog">
        <form action="../../backend/admin/addadminuser.php" method="post" onsubmit="toast('Adding User')">
            <div>
                <label for="">Enter Admin Name</label>
                <!-- <input type="text" name="adminName"  required /> -->
                <input type="text" name="adminName" onkeydown="return /[a-zA-Z]/i.test(event.key)"
                    placeholder="Enter Admin Name"
                    minlength="3" maxlength="50" required>
            </div>
            <div>
                <label for="">Enter Admin Email</label>
                <!-- <input type="text" name="adminEmail" id="" required /> -->
                <input type="text" name="adminEmail" onkeydown="return /[a-zA-Z@0-9.]/i.test(event.key)"
                    placeholder="Enter Admin Email" pattern="[^@]+@[^\.]+\..+" minlength="3" maxlength="50" required>
            </div>
            <div>
                <label for="">Enter Admin Password</label>
                <input type="text" name="adminPassword" id="" minlength="8" maxlength="15" required />
            </div>
            <div>
                <label for="adminRole">Select Admin Role</label>
                <select name="adminRole" id="" required>
                    <option value="">None</option>
                    <option value="Manager">Manager</option>
                    <option value="Technician">Technician</option>
                    <option value="Developer">Developer</option>
                </select>
            </div>
            <div class="dialogDiv">
                <button id="cancelAddAdminUser">Cancel Add</button>
                <input type="submit" value="Add Admin" name="addAdminUser" id="confirmAddAdminButton">
            </div>
        </form>
    </dialog>

    <!-- dialog to delete admin -->
    <dialog id="deleteAdminUserDialog">
        <form action="../../backend/admin/deleteadminuser.php" method="get" onsubmit="toast('Deleting Admin User')">
            <label for="">Enter the ID of the Admin User to delete</label>
            <input type="text" name="adminID" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" minlength="1" maxlength="3" required>
            <div class="dialogDiv">
                <button id="cancelDeleteButton">Cancel Delete</button>
                <input type="submit" value="Delete Admin" name="deleteIDButton" id="confirmDeleteButton">
            </div>
        </form>
    </dialog>


    <!-- div for enquiries table -->
    <section id="adminUserSection">
        <div id="adminUserHeadingAndButtonDiv">
            <h1 id="adminUserHeading">Current Admin Users</h1>
            <div>
                <button id="addAdminUserButton">Add Admin User</button>
                <button id="deleteAdminUserButton">Delete Admin User</button>
            </div>
        </div>
        <!-- no enquiry heading -->
        <!-- <h2 id="noAdminUserHeading"></h2> -->
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
            $sqlQueryToViewAdminUsers = "SELECT * FROM admin_users";
            $adminUsersResponse = $connection->query($sqlQueryToViewAdminUsers);

            if ($adminUsersResponse->num_rows > 0) {
                // bookings table display
                echo "<table cellpadding = '0' cellspacing='0' id='adminUsersTable'>";
                echo "<tr>
                <th>Admin ID</th>
                <th>Admin Name</th>
                <th>Admin Email</th>
                <th>Admin Password</th>
                <th>Admin Role</th>
             </tr>";

                while ($row = mysqli_fetch_assoc($adminUsersResponse)) {
                    echo "<tr>
                        <td>" . htmlspecialchars($row["adminID"]) . "</td>
                        <td>" . htmlspecialchars($row["adminName"]) . "</td>
                        <td>" . htmlspecialchars($row["adminEmail"]) . "</td>
                        <td>" . htmlspecialchars($row["adminPassword"]) . "</td>
                        <td>" . htmlspecialchars($row["adminRole"]) . "</td>
                        </tr>";
                }
                echo "</table>";
            } else {
                echo "<script>
        const noAdminUserHeading = document.createElement('h2')
        noAdminUserHeading.setAttribute('id','noAdminUserHeading');
        noAdminUserHeading.innerHTML = 'No Admin Users Added Yet';
        const adminUserSection = document.getElementById('adminUserSection')
        adminUserSection.appendChild(noAdminUserHeading)
        </script>";
            }
            $connection->close();
        } catch (\Throwable $th) {
            echo "<h2>No admins currently registered. Please add an admin or Log out to reset data.</h2>";
        }
        ?>
    </section>
</body>

<script>
    window.onload = () => {
        if (window.innerWidth < 800) {
            const adminUserSection = document.getElementById("adminUserSection")
            const noAdminUserHeading = document.getElementById("noAdminUserHeading")
            const screenNotBigParagraphTag = document.createElement("h2")
            screenNotBigParagraphTag.innerText = "Dashboard Only Available On Tablets And Desktops"
            document.body.appendChild(screenNotBigParagraphTag)
            adminUserSection.style.display = "none"
        }

        const addAdminUserButton = document.querySelectorAll("#addAdminUserButton")
        const addAdminUserDialog = document.getElementById("addAdminUserDialog")
        addAdminUserButton.forEach((addAdminUserButton) => {
            addAdminUserButton.addEventListener("click", () => {
                addAdminUserDialog.showModal()
            })
        })

        const cancelAddAdminUser = document.getElementById("cancelAddAdminUser")
        cancelAddAdminUser.addEventListener("click", () => {
            addAdminUserDialog.close()
        })



        const deleteAdminUserButtons = document.querySelectorAll("#deleteAdminUserButton")
        const deleteAdminUserDialog = document.getElementById("deleteAdminUserDialog")
        deleteAdminUserButtons.forEach((deleteAdminButton) => {
            deleteAdminButton.addEventListener("click", () => {
                deleteAdminUserDialog.showModal()
            })
        })

        const cancelDeleteButton = document.getElementById("cancelDeleteButton")
        cancelDeleteButton.addEventListener("click", () => {
            deleteAdminUserDialog.close()
        })

        const adminUserSection = document.getElementById("adminUserSection")
        const adminUsersTable = document.getElementById("adminUsersTable")
        adminUserSection.appendChild(adminUsersTable)

    }

    const toast = (actionName) => {
        Toastify({
            text: actionName,
            position: 'center',
            duration: 1000,
        }).showToast();
    }
</script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

</html>