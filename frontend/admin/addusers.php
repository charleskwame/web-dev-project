<?php
// Start session and check if admin is logged in; redirect to login if not
session_start();
if (empty($_SESSION['adminLoggedInEmail'])) {
    header("Location: ./index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Add Users</title>
    <!-- CSS with cache busting -->
    <link rel="stylesheet" href="./addusers.css?v=<?php echo date('his'); ?>" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" />
</head>

<body>
    <nav>
        <img src="../assets/images/logo.png" alt="logo" />
        <a href="../../frontend/admin/admindashboard.php">
            <button>View Bookings & Enquiries</button>
        </a>
    </nav>

    <!-- Add Admin User Dialog -->
    <dialog id="addAdminUserDialog">
        <form action="../../backend/admin/addadminuser.php" method="post" onsubmit="toast('Adding User')">
            <div>
                <label for="adminName">Enter Admin Name</label>
                <input
                    type="text"
                    name="adminName"
                    id="adminName"
                    placeholder="Enter Admin Name"
                    minlength="3"
                    maxlength="50"
                    required
                    onkeydown="return /[a-zA-Z]/i.test(event.key)" />
            </div>
            <div>
                <label for="adminEmail">Enter Admin Email</label>
                <input
                    type="email"
                    name="adminEmail"
                    id="adminEmail"
                    placeholder="Enter Admin Email"
                    minlength="3"
                    maxlength="50"
                    required
                    pattern="[^@]+@[^\.]+\..+"
                    onkeydown="return /[a-zA-Z0-9@._-]/i.test(event.key)" />
            </div>
            <div>
                <label for="adminPassword">Enter Admin Password</label>
                <input
                    type="password"
                    name="adminPassword"
                    id="adminPassword"
                    minlength="8"
                    maxlength="15"
                    required />
            </div>
            <div>
                <label for="adminRole">Select Admin Role</label>
                <select name="adminRole" id="adminRole" required>
                    <option value="" disabled selected>None</option>
                    <option value="Manager">Manager</option>
                    <option value="Technician">Technician</option>
                    <option value="Developer">Developer</option>
                </select>
            </div>
            <div class="dialogDiv">
                <button type="button" id="cancelAddAdminUser">Cancel Add</button>
                <input type="submit" value="Add Admin" name="addAdminUser" id="confirmAddAdminButton" />
            </div>
        </form>
    </dialog>

    <!-- Delete Admin User Dialog -->
    <dialog id="deleteAdminUserDialog">
        <form action="../../backend/admin/deleteadminuser.php" method="get" onsubmit="toast('Deleting Admin User')">
            <label for="adminID">Enter the ID of the Admin User to delete</label>
            <input
                type="number"
                name="adminID"
                id="adminID"
                minlength="1"
                maxlength="3"
                required
                onkeypress="return event.charCode >= 48 && event.charCode <= 57" />
            <div class="dialogDiv">
                <button type="button" id="cancelDeleteButton">Cancel Delete</button>
                <input type="submit" value="Delete Admin" name="deleteIDButton" id="confirmDeleteButton" />
            </div>
        </form>
    </dialog>

    <section id="adminUserSection">
        <div id="adminUserHeadingAndButtonDiv">
            <h1 id="adminUserHeading">Current Admin Users</h1>
            <div>
                <button id="addAdminUserButton">Add Admin User</button>
                <button id="deleteAdminUserButton">Delete Admin User</button>
            </div>
        </div>

        <?php
        // Connect to database and fetch admin users
        $server = "localhost";
        $user = "root";
        $password = "";
        $database = "nacom_database";

        $connection = mysqli_connect($server, $user, $password, $database);

        if (!$connection) {
            echo "<h2>Database connection failed: " . mysqli_connect_error() . "</h2>";
            exit();
        }

        $sql = "SELECT * FROM admin_users";
        $result = mysqli_query($connection, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            echo "<table cellpadding='0' cellspacing='0' id='adminUsersTable'>";
            echo "<thead><tr>
                <th>Admin ID</th>
                <th>Admin Name</th>
                <th>Admin Email</th>
                <th>Admin Password</th>
                <th>Admin Role</th>
            </tr></thead><tbody>";

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                    <td>" . htmlspecialchars($row["adminID"]) . "</td>
                    <td>" . htmlspecialchars($row["adminName"]) . "</td>
                    <td>" . htmlspecialchars($row["adminEmail"]) . "</td>
                    <td>" . htmlspecialchars($row["adminPassword"]) . "</td>
                    <td>" . htmlspecialchars($row["adminRole"]) . "</td>
                </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<h2>No Admin Users Added Yet</h2>";
        }

        mysqli_close($connection);
        ?>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        window.onload = () => {
            // If screen width is less than 800px, hide the dashboard content
            if (window.innerWidth < 800) {
                const adminUserSection = document.getElementById("adminUserSection");
                const warning = document.createElement("h2");
                warning.innerText = "Dashboard Only Available On Tablets And Desktops";
                document.body.appendChild(warning);
                adminUserSection.style.display = "none";
            }

            // Show Add Admin User dialog
            document.getElementById("addAdminUserButton").addEventListener("click", () => {
                document.getElementById("addAdminUserDialog").showModal();
            });

            // Cancel Add Admin User dialog
            document.getElementById("cancelAddAdminUser").addEventListener("click", () => {
                document.getElementById("addAdminUserDialog").close();
            });

            // Show Delete Admin User dialog
            document.getElementById("deleteAdminUserButton").addEventListener("click", () => {
                document.getElementById("deleteAdminUserDialog").showModal();
            });

            // Cancel Delete Admin User dialog
            document.getElementById("cancelDeleteButton").addEventListener("click", () => {
                document.getElementById("deleteAdminUserDialog").close();
            });
        };

        // Toast notification helper
        const toast = (message) => {
            Toastify({
                text: message,
                position: "center",
                duration: 1000,
            }).showToast();
        };
    </script>
</body>

</html>