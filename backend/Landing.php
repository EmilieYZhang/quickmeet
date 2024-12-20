<?php
include "db_connect.php";
session_start();  // Start the session

// Check if the session ticket exists
if (isset($_SESSION['ticket'])) {
    // The user is logged in
    $ticket = $_SESSION['ticket'];

    // Validate the ticket in the database
    $stmt = $conn->prepare("SELECT user_id, expiry FROM user_tickets WHERE ticket = ?");
    if ($stmt === false) {
        die("Database query failed.");
    }

    $stmt->bind_param("s", $ticket);
    $stmt->execute();
    $stmt->bind_result($userId, $expiry);
    $stmt->fetch();
    $stmt->close();


    // Optionally, extend the session expiry if it's valid
    $newExpiry = time() + 3600;  // Extend for 1 more hour
    $updateStmt = $conn->prepare("UPDATE user_tickets SET expiry = ? WHERE ticket = ?");
    $updateStmt->bind_param("is", $newExpiry, $ticket);
    $updateStmt->execute();
    $updateStmt->close();

    // Set a flag to use in the page for logged-in users
    $isLoggedIn = true;
} else {
    // If there is no ticket, user is not logged in
    $isLoggedIn = false;
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <style>
        .calendar {
            display: flex;
            flex-wrap: wrap;
            width: 500px;
            bottom: 8vh;
            right: 0;
            margin: 50px auto;
            margin-right: 5vw;
            text-align: center;
            position: absolute;
            background-color: #2A4A63;
            border-radius: 15px;
            padding-top: 2vw;
            padding-right: 2vw;

        }

        .day, .date {
            margin-left: 5ch;
            margin-bottom: 2.5ch;
            width: 2.5ch;
            height: 2.5ch;
            line-height: 2.5ch;
            color: white;
        }

        .day {
            font-weight: bold;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;

        }

        .date {
            font-size: 17px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            /* border-radius: 50%; */
        }

        .current-day {
            background-color: white;
            color: black;
            font-weight: bold;
            border-radius: 50%;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        }


        /* a {
            margin-right: 5vw;
            color: #808998;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 18px;
        } */
        a {
        margin-right: 60px;
        color: #808998;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px;
    }

        .BookingMessageHeader{
            margin-top: 12%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: white;
            margin-left: 8px;
        }

        .messageUnder{
            margin-top: 0%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: white;
            margin-left: 8px;
        }

        .BOOKimg{
            margin-top: 5%;
            width: 400px;
        }
        .Links {
            position: absolute;
            top: 6vh;
            right: 0vh;
        }

        .hamUnderline{
            display: none;

        }
        .LinksForPhone{
            display: none;
        }
        .logoForPhone{
            display: none;
        }


    </style>
    <link href="../FrontEndCode/Landing.css" rel="stylesheet">
</head>
<body style="background-color: #0C3D65;">

    <!-- HTML Header Content -->
<div class="header">
    <a href="../backend/Landing.php"><img src="../FrontEndCode/NewLogoGoodColor.jpg" style="height: 8vw;"></a>

    <?php if ($isLoggedIn): ?>
        <!-- Logged-in user header -->
        <div class="Links">
            <a href="../backend/dashboard.php">Dashboard</a>
            <a href="../backend/FAQ.php">FAQ</a>
            <a href="#">Tutorial</a>
            <a href="../backend/BookNow.php">Book now</a>
            <a href="../backend/logout.php">Logout</a>
        </div>
    <?php else: ?>
        <!-- Guest user header -->
        <div class="Links">
            <a href="../backend/Register.php">Register</a>
            <a href="../backend/Login.php">Login</a>
            <a href="../backend/FAQ.php">FAQ</a>
            <a href="#">Tutorial</a>
            <a href="../backend/BookNow.php">Book now</a>
        </div>
    <?php endif; ?>
</div>

<!-- Mobile/Responsive Menu -->
<div class="hamburger" onclick="dropDownMenu()">
    <div style="display: flex;">
        <a href="../backend/Landing.php"><img class = "logoForPhone" src="../FrontEndCode/NewLogoGoodColor.jpg" style="height: 80px;"></a>
        <a class="hamUnderline" href="#">☰</a>
    </div>
</div>
<div class="LinksForPhone">
    <?php if ($isLoggedIn): ?>
        <a href="../backend/dashboard.php">Dashboard</a>
        <a href="../backend/FAQ.php">FAQ</a>
        <a href="#">Tutorial</a>
        <a href="../backend/BookNow.php">Book now</a>
        <a href="../backend/logout.php">Logout</a>
    <?php else: ?>
        <a href="../backend/Register.php">Register</a>
        <a href="../backend/Login.php">Login</a>
        <a href="../backend/FAQ.php">FAQ</a>
        <a href="#">Tutorial</a>
        <a href="../backend/BookNow.php">Book now</a>
    <?php endif; ?>
</div>

    <div class = "allBesideCalendar">
        <h1 class = "BookingMessageHeader"> Booking made easy.</h1>
        <div class="messageUnder"> Just choose the time and day, and we'll take care of the rest</div>

        <img class = "BOOKimg" src="../FrontEndCode/BOOKCoolcolor.jpg">
    </div>



        <div class="calendar">
                <div class="day">Mon</div>
                <div class="day">Tue</div>
                <div class="day">Wed</div>
                <div class="day">Thu</div>
                <div class="day">Fri</div>
                <div class="day">Sat</div>
                <div class="day">Sun</div>
        </div>


    <script src="../FrontEndCode/Landing.js"></script>
    <script>
        function dropDownMenu() {
            const linksForPhone = document.querySelector(".LinksForPhone");
            linksForPhone.classList.toggle('show');
        }
    </script>
</body>
</html>
