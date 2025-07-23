<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./bookingpage.css?v= <?php echo date('his'); ?>">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <title>Book Consultation</title>
</head>

<body>
    <div class="header">
        <nav class="nav">
            <a href="../client/index.html">
                <img src="../assets/images/logo.png" alt="">
            </a>
            <div>
                <a href="bookingpage.html" class="active">Book Us</a>
                <a href="enquiriespage.html">Enquiries</a>
                <a href="contactuspage.html">Contact Us</a>
            </div>
        </nav>

    </div>
    <main>
        <div class="our-services">
            <h2>Our Services</h2>
            <div class="services">
                <div class="service">
                    <img src="../assets/images/web development.jpg" alt="">
                    <p>
                        Web Design
                    </p>
                </div>
                <div class="service">
                    <img src="../assets/images/mobileapp.jpg" alt="">
                    <p>
                        Mobile Application Design
                    </p>
                </div>
                <div class="service">
                    <img src="../assets/images/network.jpg" alt="">
                    <p>
                        Network Set Up
                    </p>
                </div>
                <div class="service">
                    <img src="../assets/images/server.jpg" alt="">
                    <p>
                        Server Set Up
                    </p>
                </div>
            </div>
        </div>
        <div class="booking">
            <h2>Book a consultation with us</h2>
            <div class="booking-form">
                <form action="../../backend/client/bookingpage.php" method="post" onsubmit="toast()">
                    <div>
                        <label for="">Enter your name</label>
                        <input type="text" name="customerName" onkeydown="return /[a-zA-Z]/i.test(event.key)"
                            placeholder="Enter your name"
                            minlength="3" maxlength="50" required>
                    </div>

                    <div>
                        <label for="">Enter your email</label>
                        <input type="text" name="customerEmail" onkeydown="return /[a-zA-Z@0-9.]/i.test(event.key)" pattern="[^@]+@[^\.]+\..+" placeholder="aba@gmail.com"
                            minlength="10" maxlength="50" required>
                    </div>

                    <div>
                        <label for="">Choose Service</label>
                        <select name="serviceBooked" id="" required>
                            <option value="">--Select Service--</option>
                            <option value="Web Design and Development">Web Design And Development</option>
                            <option value="Mobile Application Development">Mobile Application Design</option>
                            <option value="Network Infastructure Set Up">Network Set Up</option>
                            <option value="Server Architecture Set Up">Server Set Up</option>
                        </select>
                    </div>

                    <div>
                        <label for="">Book Consultation day</label>
                        <input type="date" name="bookingDate" id="dateInput" min="<?php echo date("Y-m-d"); ?>" required>
                    </div>

                    <button type="submit" name="bookButton">Submit Appointment</button>
                </form>
            </div>
        </div>
    </main>
</body>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>
    const toast = () => {
        Toastify({
            text: 'Booking Completed. Waiting Confirmation From Administrator',
            position: 'center',
            duration: 3000,
        }).showToast();
    }
</script>

</html>