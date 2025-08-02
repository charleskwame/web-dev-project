<?php
// Define database connection parameters
$server = "localhost";
$user = "root";
$password = "";
$database = "nacom_database";

// Connect to MySQL server without selecting a database yet
$connection = mysqli_connect($server, $user, $password);

// Query to check if the database already exists
$sqlDatabaseCheck = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$database'";
$result = mysqli_query($connection, $sqlDatabaseCheck);

// If the database does not exist, create it
if ($result && mysqli_num_rows($result) == 0) {
    // Database not found, create it
    $sqlCreateDatabase = "CREATE DATABASE IF NOT EXISTS $database";
    mysqli_query($connection, $sqlCreateDatabase);
}

// Connect again, now specifying the target database
$connectionToDatabase = mysqli_connect($server, $user, $password, $database);

// Query to check if the 'enquiries' table exists
$sqlTableCheckQuery = "SHOW TABLES LIKE 'enquiries'";
$sqlTableCheckResult = mysqli_query($connectionToDatabase, $sqlTableCheckQuery);

// If the 'enquiries' table doesn't exist, create it
if (!$sqlTableCheckResult || mysqli_num_rows($sqlTableCheckResult) == 0) {
    // SQL query to create 'enquiries' table with necessary fields
    $sqlCreateEnquiriesTable = "CREATE TABLE `enquiries` (
        `enquiryID` int(3) NOT NULL AUTO_INCREMENT,
        `customerName` varchar(30) NOT NULL,
        `customerEmail` varchar(50) NOT NULL,
        `customerPhoneNumber` varchar(30) NOT NULL,
        `enquiryQuestion` varchar(300) NOT NULL,
        PRIMARY KEY (`enquiryID`)
    )";
    mysqli_query($connectionToDatabase, $sqlCreateEnquiriesTable);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- Responsive display settings -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Toastify CSS for toast notifications -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <!-- Custom CSS for the enquiries page, with cache-busting query string -->
    <link rel="stylesheet" href="./enquiriespage.css?v= <?php echo date('his'); ?>">
    <title>Enquiries</title>
</head>

<body>
    <header>
        <nav class="nav">
            <!-- Logo and navigation links -->
            <a href="../client/index.html">
                <img src="../assets/images/logo.png" alt="">
            </a>
            <div>
                <a href="bookingpage.php">Book Us</a>
                <a href="enquiriespage.php" class="active">Enquiries</a>
                <a href="contactuspage.html">Contact Us</a>
            </div>
        </nav>
    </header>

    <main>
        <!-- Frequently Asked Questions Section -->
        <section class="faq-section">
            <h2>Frequently Asked Questions</h2>

            <!-- Each FAQ item contains a question and its corresponding answer -->
            <div class="faq-item">
                <div class="faq-question">What services do you offer?</div>
                <div class="faq-answer">
                    <p>We provide a comprehensive range of services including Web Design, Mobile Application Design,
                        Network Set Up and Server Set Up.</p>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">How long does a typical project take?</div>
                <div class="faq-answer">
                    <p>Project timelines vary depending on scope. Most projects are completed within 2-4 weeks, or up to 8 weeks for more complex tasks.</p>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">What are your working hours?</div>
                <div class="faq-answer">
                    <p>Monday through Friday, 9:00 AM to 6:00 PM. Emergency support is available 24/7 for existing clients.</p>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">Do you offer customized solutions?</div>
                <div class="faq-answer">
                    <p>Yes! We tailor every solution to the client's unique requirements after a discovery session.</p>
                </div>
            </div>
        </section>

        <!-- Contact form for submitting enquiries -->
        <section class="contact-section">
            <h2>Email Us Your Questions</h2>

            <!-- Enquiry form submission -->
            <form action="../../backend/client/enquiriespage.php" method="post" class="contact-form" id="enquiryForm" onsubmit="toast()">
                <!-- Name input with character restriction -->
                <div class="form-group">
                    <label for="name">Your Name</label>
                    <input type="text" name="customerName" id="name" onkeydown="return /[a-zA-Z ]/i.test(event.key)"
                        placeholder="Enter your name"
                        minlength="3" maxlength="50" required>
                </div>

                <!-- Email input with basic pattern validation -->
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="customerEmail" pattern="[^@]+@[^\.]+\..+" placeholder="aba@gmail.com"
                        minlength="10" maxlength="50" required>
                </div>

                <!-- Phone number input with digit-only validation -->
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="customerPhoneNumber" minlength="10" maxlength="10" onkeydown="return /[0-9]|Backspace/i.test(event.key)" placeholder="0200000000" required>
                </div>

                <!-- Textarea for the user to type their question -->
                <div class="form-group">
                    <label for="question">Your Question</label>
                    <textarea id="question" name="enquiryQuestion" required
                        placeholder="Type your question here..." maxlength="300"></textarea>
                </div>

                <!-- Submit button -->
                <button type="submit" class="submit-btn" name="enquiryButton">Submit Enquiry</button>
            </form>
        </section>
    </main>

    <script>
        // JavaScript to toggle FAQ answers (accordion-style behavior)
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', () => {
                const answer = question.nextElementSibling;
                const isActive = question.classList.contains('active');

                // Close all open questions
                document.querySelectorAll('.faq-question').forEach(q => {
                    q.classList.remove('active');
                    q.nextElementSibling.classList.remove('show');
                });

                // Open the clicked question if it was previously closed
                if (!isActive) {
                    question.classList.add('active');
                    answer.classList.add('show');
                }
            });
        });

        window.onload = () => {
            // Create a URLSearchParams object to easily access query parameters
            const params = new URLSearchParams(window.location.search);

            // Check if the "toast" parameter exists and equals "booking_success"
            if (params.get('toast') === 'enquiries_success') {
                // Show the toast notification using Toastify
                Toastify({
                    text: "Enquiry Sent. An Administrator will contact you soon.",
                    position: "center", // position the toast in the center of the screen
                    duration: 2000, // display duration in milliseconds (3 seconds)
                }).showToast();

                // Remove the "toast" query parameter from the URL to prevent
                // the toast from showing again if the page is reloaded or revisited
                params.delete('toast');

                // Construct the new URL without the "toast" parameter
                // If there are other parameters, keep them; otherwise, just use pathname
                const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');

                // Use the History API to update the URL in the browser without reloading the page
                window.history.replaceState({}, document.title, newUrl);
            }
        }
    </script>

    <!-- Toastify JS library for showing toast notifications -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

</body>

</html>