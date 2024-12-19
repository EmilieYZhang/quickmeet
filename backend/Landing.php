<?php
include '../backend/header.php';  // This will handle session and DB connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Calendar</title>
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
	.hamburger {
	    display: none;
	}
 	.header {
	    display: block;
	}

    </style>
    <link href="../FrontEndCode/Landing.css" rel="stylesheet">
</head>
<body style="background-color: #0C3D65;">
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
