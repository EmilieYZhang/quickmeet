<?php
require 'db_connect.php'; // Include database connection
session_start();

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the session ticket exists
if (!isset($_SESSION['ticket'])) {
    header("Location: ../FrontEndCode/Login.html");
    exit();
}

$ticket = $_SESSION['ticket'];

// Validate the ticket
$stmt = $conn->prepare("SELECT user_id, expiry FROM user_tickets WHERE ticket = ?");
if ($stmt === false) {
    die("Database query failed.");
}
$stmt->bind_param("s", $ticket);
$stmt->execute();
$stmt->bind_result($userId, $expiry);
$stmt->fetch();
$stmt->close();

// Check if the ticket is valid and not expired
if (!$userId || time() > $expiry) {
    session_destroy();
    header("Location: ../FrontEndCode/Login.html");
    exit();
}

// Extend session expiry
$newExpiry = time() + 3600; // Extend for 1 more hour
$updateStmt = $conn->prepare("UPDATE user_tickets SET expiry = ? WHERE ticket = ?");
$updateStmt->bind_param("is", $newExpiry, $ticket);
$updateStmt->execute();
$updateStmt->close();

// Fetch user bookings
$bookings = [];
$bookingStmt = $conn->prepare("SELECT * FROM Booking WHERE uid = ?");
if ($bookingStmt === false) {
    die("Database query failed.");
}
$bookingStmt->bind_param("i", $userId);
$bookingStmt->execute();
$result = $bookingStmt->get_result();

while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}
$bookingStmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link href="../FrontEndCode/RegisterCssPho.css" rel="stylesheet">
    <link href="../FrontEndCode/RegisterCssLap.css" rel="stylesheet">
    <link href="../FrontEndCode/RegisterCssDesk.css" rel="stylesheet">

    <style>
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Styling for form and bottom text */
        .bottomText {
            font-size: 14px;
            color: #555;
            margin-top: 10px;
            text-align: center;
        }

        .registerButton {
            background-color: #0088DC;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .registerButton:hover {
            background-color: #006f99;
        }

        /* Specific Dashboard Styles */
        .dashboard-container {
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            max-width: 800px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            font-family: 'Segoe UI', sans-serif;
            color: #FFFFFF;
            margin: 40px auto;
            padding-left: 20px;
            max-width: 800px;
            text-align: left;
        }

        h2 {
            text-decoration: none; /* Removes the underline */
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        table thead {
            background-color: #456288;
            color: #fff;
        }

        .logout-button {
            background-color: #D9534F;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 20px;
        }

        .logout-button:hover {
            background-color: #C9302C;
        }

        .no-bookings {
            text-align: center;
            color: #888;
            font-style: italic;
            padding: 10px 0;
        }

        button {
            background-color: #456288;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 40px auto;
            display: block;
            text-align: left;
            max-width: 800px;
        }

        button:hover {
            background-color: #2c4568;
        }

        .toggle-section {
            margin-top: 20px;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            color: #0088DC;
        }

        .toggle-section:hover {
            text-decoration: underline;
        }

        .hidden {
            display: none;
        }

        .LinksForPhone {
            display: none;
        }

        /* Hamburger menu style */
        .hamburger {
            display: none;
            cursor: pointer;
        }

        /* Mobile view: Show the hamburger menu */
        @media (max-width: 768px) {
            .hamburger {
                display: block;
                padding: 10px;
            }

            .LinksForPhone.show {
                display: block;
            }

            .LinksForPhone {
                display: none;
                text-align: center;
                margin-top: 20px;
            }

            .LinksForPhone a {
                display: block;
                padding: 10px;
                color: white;
                background-color: #456288;
                text-decoration: none;
            }

            .LinksForPhone a:hover {
                background-color: #2c4568;
            }
        }
    </style>
</head>
<body style="background-color: #0C3D65;">

    <!-- Navbar Section -->

    <!-- Hamburger Icon -->
    <div class="hamburger" onclick="dropDownMenu()">
        <div style="display: flex;">
            <a href="../FrontEndCode/Landing.html"><img src="../FrontEndCode/NewLogoGoodColor.jpg" style="height: 80px;"></a>
            <a class="hamUnderline" href="#">☰</a>
        </div>
    </div>

    <!-- Links for mobile (Hamburger Menu) -->
    <div class="LinksForPhone">
        <a href="../FrontEndCode/FAQ.html">FAQ</a>
        <a href="#">Tutorial</a>
        <a href="../FrontEndCode/BookNow.html">Book now</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="header">
        <a href="../FrontEndCode/Landing.html"><img src="../FrontEndCode/NewLogoGoodColor.jpg" style="height: 8vw;"></a>
        <div class="Links">
            <a href="../FrontEndCode/FAQ.html">FAQ</a>
            <a href="#">Tutorial</a>
            <a href="../FrontEndCode/BookNow.html">Book now</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <!-- Welcome Line -->
    <h2 style="color: white; padding-left: 20px;">Welcome, <?php echo htmlspecialchars($_SESSION['firstName']); ?>!</h2>

    <!-- Active Bookings Section -->
    <h1 style="color: white; padding-left: 20px;">My Active Bookings</h1>
    <div class="dashboard-container">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Booking Title</th>
                    <th>Booking URL</th>
                    <th> </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $activeCount = 0;
                $currentDate = new DateTime();

                foreach ($bookings as $index => $booking) {
                    $endDate = new DateTime($booking['enddatetime']);
                    if ($endDate >= $currentDate) {
                        $activeCount++;
                        echo "<tr>
                            <td>" . ($index + 1) . "</td>
                            <td>" . htmlspecialchars($booking['bookingtitle']) . "</td>
                            <td><a href='http://localhost/quickmeet/quickmeet_api/bookingurl.php?url=" . htmlspecialchars($booking['bookingurl']) . "' target='_blank'>" . htmlspecialchars($booking['bookingurl']) . "</a></td>
                            <td><a href='http://localhost/quickmeet/backend/editbookingurl.php?url=" . htmlspecialchars($booking['editbookingurl']) . "' target='_blank'> EDIT</td>
                            </tr>";
                    }
                }

                if ($activeCount === 0) {
                    echo "<tr><td colspan='3' class='no-bookings'>No active or future bookings found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Create Booking Button -->
    <button onclick="showModal()">Create Booking</button>

    <!-- Past Bookings Section -->
    <h2 class="toggle-section" onclick="togglePastBookings()">My Past Bookings</h2>
    <div id="past-bookings-section" class="hidden">
        <div class="dashboard-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Booking Title</th>
                        <th>Booking URL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $pastCount = 0;

                    foreach ($bookings as $index => $booking) {
                        $endDate = new DateTime($booking['enddatetime']);
                        if ($endDate < $currentDate) {
                            $pastCount++;
                            echo "<tr>
                                <td>" . ($index + 1) . "</td>
                                <td>" . htmlspecialchars($booking['bookingtitle']) . "</td>
                                <td><a href='" . htmlspecialchars($booking['bookingurl']) . "' target='_blank'>" . htmlspecialchars($booking['bookingurl']) . "</a></td>
                            </tr>";
                        }
                    }

                    if ($pastCount === 0) {
                        echo "<tr><td colspan='3' class='no-bookings'>No past bookings found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- The Modal for Creating Booking -->
    <div id="bookingModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Create a Booking</h2>
            <form name="Form" onsubmit="return createBooking();">
                <input type="text" name="btitle" placeholder="Booking Title" required><br><br>
                <input type="text" name="bdescription" placeholder="Booking Description" required><br><br>
                <input style="background-color: #0088DC;" class="registerButton" type="submit" value="Submit">
            </form>
            <div class="bottomText">Share the booking URL with anyone!</div>
        </div>
    </div>

    <script>
        const userId = <?php echo htmlspecialchars($userId); ?>; // Hardcoded user ID, replace this with session-based user ID
        const apiEndpoint = `../quickmeet_api/apiendpoints.php/booking/${userId}/userid`;

        // Function to show the modal for booking
        function showModal() {
            document.getElementById("bookingModal").style.display = "block";
        }

        // Function to close the modal
        function closeModal() {
            document.getElementById("bookingModal").style.display = "none";
        }

        // Function to handle form submission and create booking
        function createBooking() {
            const bookingTitle = document.forms["Form"]["btitle"].value;
            const bookingDescription = document.forms["Form"]["bdescription"].value;

            // Check if data exists before proceeding
            if (!bookingTitle || !bookingDescription) {
                alert("Please fill in all fields.");
                return false;
            }

            fetch('../quickmeet_api/apiendpoints.php/booking', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    uid: userId,  // Replace with actual user ID
                    startdatetime: "2024-12-10 10:00:00",
                    enddatetime: "2024-12-19 12:00:00",
                    bookingtitle: bookingTitle,
                    bookingdescription: bookingDescription
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(newBooking => {
                alert(`Booking created: ${newBooking.booking_id} - ${newBooking.booking_url}`);
                window.location.href = newBooking.booking_url; // Redirect to the booking URL
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Failed to create booking.");
            });

            return false; // Prevent form submission from refreshing the page
        }

        // Toggle visibility of past bookings
        function togglePastBookings() {
            const pastBookingsSection = document.getElementById('past-bookings-section');
            pastBookingsSection.classList.toggle('hidden');
        }

        // Dropdown menu for mobile view
        function dropDownMenu() {
            const linksForPhone = document.querySelector(".LinksForPhone");
            linksForPhone.classList.toggle('show');
        }

    </script>
</body>
</html>

