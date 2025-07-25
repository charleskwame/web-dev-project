<?php
// checking if database exists and creating it if not found
$server = "localhost";
$user = "root";
$password = "";
$database = "nacom_database";
$connection = mysqli_connect($server, $user, $password);
$sqlDatabaseCheck = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$database'";
$result = mysqli_query($connection, $sqlDatabaseCheck);
if ($result && mysqli_num_rows($result) == 0) {
    // echo "does not exist";
    $sqlCreateDatabase = "CREATE DATABASE IF NOT EXISTS $database";
    mysqli_query($connection, $sqlCreateDatabase);
}
// Check if 'bookings' table exists in the newly created database
$connectionToDatabase = mysqli_connect($server, $user, $password, $database);
$sqlTableCheckQuery = "SHOW TABLES LIKE 'enquiries'";
$sqlTableCheckResult = mysqli_query($connectionToDatabase, $sqlTableCheckQuery);

if (!$sqlTableCheckResult || mysqli_num_rows($sqlTableCheckResult) == 0) {
    // Table does not exist, create it
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="./enquiriespage.css?v= <?php echo date('his'); ?>">
    <title>Enquiries</title>
</head>

<body>
    <header>
        <nav class="nav">
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
        <!-- <h1>How Can We Help You?</h1> -->
        <section class="faq-section">
            <h2>Frequently Asked Questions</h2>

            <div class="faq-item">
                <div class="faq-question">What services do you offer?</div>
                <div class="faq-answer">
                    <p>We provide a comprehensive range of services including Web Design, Mobile Application Design,
                        Network Set Up and
                        Server Set Up. Our team specializes in delivering high-quality solutions tailored to your
                        specific
                        needs.</p>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">How long does a typical project take?</div>
                <div class="faq-answer">
                    <p>Project timelines vary depending on scope and complexity. Most standard projects are
                        completed within 2-4 weeks, while more extensive engagements may take 6-8 weeks. We'll
                        provide a detailed timeline during our initial consultation.</p>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">What are your working hours?</div>
                <div class="faq-answer">
                    <p>Our office is open Monday through Friday from 9:00 AM to 6:00 PM. Emergency support is
                        available 24/7 for existing clients.</p>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">Do you offer customized solutions?</div>
                <div class="faq-answer">
                    <p>Absolutely! Every client receives a personalized solution. We begin each engagement with
                        a
                        discovery session to understand your unique requirements before proposing any solutions.
                    </p>
                </div>
            </div>
        </section>

        <section class="contact-section">
            <h2>Email Us Your Questions</h2>

            <form action="../../backend/client/enquiriespage.php" method="post" class="contact-form" id="enquiryForm" onsubmit="toast()">
                <div class="form-group">
                    <label for="name">Your Name</label>
                    <input type="text" name="customerName" id="name" onkeydown="return /[a-zA-Z ]/i.test(event.key)"
                        placeholder="Enter your name"
                        minlength="3" maxlength="50" required>
                    <!-- <input type="text" id="name" name="customerName" required placeholder="John Smith"> -->
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="customerEmail" pattern="[^@]+@[^\.]+\..+" placeholder="aba@gmail.com"
                        minlength="10" maxlength="50" required>
                    <!-- <input type="email" id="email" name="customerEmail" required placeholder="john@example.com"> -->
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="customerPhoneNumber" minlength="10" maxlength="10" onkeydown="return /[0-9]|Backspace/i.test(event.key)" placeholder="0200000000" required>
                </div>

                <div class="form-group">
                    <label for="question">Your Question</label>
                    <textarea id="question" name="enquiryQuestion" required
                        placeholder="Type your question here..." maxlength="300"></textarea>
                </div>

                <button type="submit" class="submit-btn" name="enquiryButton">Submit Enquiry</button>
            </form>
        </section>
    </main>

    <script>
        // FAQ Accordion Functionality
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', () => {
                const answer = question.nextElementSibling;
                const isActive = question.classList.contains('active');

                // Close all other FAQs
                document.querySelectorAll('.faq-question').forEach(q => {
                    q.classList.remove('active');
                    q.nextElementSibling.classList.remove('show');
                });

                // Open clicked one if it wasn't active
                if (!isActive) {
                    question.classList.add('active');
                    answer.classList.add('show');
                }
            });
        });
        const toast = () => {
            Toastify({
                text: 'Enquiry Submitted. An Administrator will contact you with a response soon',
                position: 'center',
                duration: 3000,
            }).showToast();
        }
    </script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

</body>

</html>