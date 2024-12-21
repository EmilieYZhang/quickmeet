<?php
include 'header.php';

if (!$userId || time() > $expiry) {
    session_destroy();
    header("Location: ../FrontEndCode/Login.html");
    exit();
}

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link href="../FrontEndCode/RegisterCssPho.css" rel="stylesheet">
    <link href="../FrontEndCode/RegisterCssLap.css" rel="stylesheet">

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


        .dropdown {
                width: 50%;
                padding: 10px;
                font-size: 18px;
                background-color: #303245;
                border: none;
                color: #eee;
                border-radius: 12px;
                margin-right: 5px;
                box-sizing: border-box;
                margin-top: 10px;
            }

            .startMonthDay{
                display: flex;
            }
            .endMonthDay{
                display: flex;
            }
    </style>
</head>
<body style="background-color: #0C3D65;">

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
                            <td><a href='https://cs.mcgill.ca/~hkacma/COMP307/booking_tool/quickmeet/quickmeet_api/bookingurl.php?url=" . htmlspecialchars($booking['bookingurl']) . "' target='_blank'>" . htmlspecialchars($booking['bookingurl']) . "</a></td>
                            <td><a href='https://cs.mcgill.ca/~hkacma/COMP307/booking_tool/quickmeet/backend/editbookingurl.php?url=" . htmlspecialchars($booking['editbookingurl']) . "' target='_blank'> EDIT</td>
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
                                <td><a href='https://cs.mcgill.ca/~hkacma/COMP307/booking_tool/quickmeet/quickmeet_api/bookingurl.php?url=" . htmlspecialchars($booking['bookingurl']) . "' target='_blank'>" . htmlspecialchars($booking['bookingurl']) . "</a></td>
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
        <div class="modal-content" style="background-color: #456288; border-radius: 15px;">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Create a Booking</h2>
            <form name="Form" onsubmit="return createBooking();">
                <input type="text" name="btitle" placeholder="Booking Title" required><br><br>
                <input type="text" name="bdescription" placeholder="Booking Description" required><br><br>
                <div class="dropdown-container">
                    <div class="startMonthDay">
                        <select id="startYear" class="dropdown" name="startYear">
                            <option value="" disabled selected>starting year</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
                        </select>
                        <select id="startMonth" class="dropdown" name="startMonth">
                            <option value="" disabled selected>starting month</option>
                            <option value="01">January</option>
                            <option value="02">February</option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>

                        <select id="date" class="dropdown" name="startDay">>
                            <option value="" disabled selected>starting day</option>
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                            <option value="05">05</option>
                            <option value="06">06</option>
                            <option value="07">07</option>
                            <option value="08">08</option>
                            <option value="09">09</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                            <option value="24">24</option>
                            <option value="25">25</option>
                            <option value="26">26</option>
                            <option value="27">27</option>
                            <option value="28">28</option>
                            <option value="29">29</option>
                            <option value="30">30</option>
                            <option value="31">31</option>
                        </select>
                    </div>

                    <div class="endMonthDay">
                    <select id="endYear" class="dropdown" name="endYear">
                            <option value="" disabled selected>ending year</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
                        </select>
                        <select id="date" class="dropdown" name="endMonth" >
                            <option value="" disabled selected>ending month</option>
                            <option value="01">January</option>
                            <option value="02">February</option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>

                        <select id="date" class="dropdown" name="endDay" >
                            <option value="" disabled selected>ending day</option>
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                            <option value="05">05</option>
                            <option value="06">06</option>
                            <option value="07">07</option>
                            <option value="08">08</option>
                            <option value="09">09</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                            <option value="24">24</option>
                            <option value="25">25</option>
                            <option value="26">26</option>
                            <option value="27">27</option>
                            <option value="28">28</option>
                            <option value="29">29</option>
                            <option value="30">30</option>
                            <option value="31">31</option>
                        </select>
                    </div>
            </div>
                <input style="background-color: #0088DC;" class="registerButton" type="submit" value="Submit">
            </form>
            <div class="bottomText">Share the booking URL with anyone!</div>
        </div>
    </div>

    <script>
        const userId = <?php echo htmlspecialchars($userId); ?>; // Hardcoded user ID, replace this with session-based user ID
        const apiEndpoint = `https://cs.mcgill.ca/~hkacma/COMP307/booking_tool/quickmeet/quickmeet_api/apiendpoints.php/booking/${userId}/userid`;

        // Function to show the modal for booking
        function showModal() {
            document.getElementById("bookingModal").style.display = "block";
        }

        // Function to close the modal
        function closeModal() {
            document.getElementById("bookingModal").style.display = "none";
        }

        // @author: Emilie Zhang for create new booking edge cases and backend
        function createBooking() {
            const bookingTitle = document.forms["Form"]["btitle"].value;
            const bookingDescription = document.forms["Form"]["bdescription"].value;

            const bookingStartYear = document.forms["Form"]["startYear"].value;
            const bookingStartMonth = document.forms["Form"]["startMonth"].value;
            const bookingStartDay = document.forms["Form"]["startDay"].value;

            const bookingEndYear = document.forms["Form"]["endYear"].value;
            const bookingEndMonth = document.forms["Form"]["endMonth"].value;
            const bookingEndDay = document.forms["Form"]["endDay"].value;

            const startDatetime = bookingStartYear + "-" + bookingStartMonth + "-" + bookingStartDay + " " + "00:00:00";
            const endDatetime = bookingEndYear + "-" + bookingEndMonth + "-" + bookingEndDay + " " + "23:59:00";

            // Check if data exists before proceeding
            if (!bookingTitle || !bookingDescription) {
                alert("Please fill in all fields.");
                return false;
            }

            if (startDatetime > endDatetime) {
                alert("This booking start date can not be after the end date.");
                return false;
            }

            fetch('https://cs.mcgill.ca/~hkacma/COMP307/booking_tool/quickmeet/quickmeet_api/apiendpoints.php/booking', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    uid: userId,  // Replace with actual user ID
                    startdatetime: startDatetime, //"2024-12-10 10:00:00",
                    enddatetime: endDatetime, //"2024-12-19 12:00:00",
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
                window.location.href = newBooking.editbooking_url; // Redirect to the booking URL
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

