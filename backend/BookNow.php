<?php
include 'header.php';
?>

<!DOCTYPE html>
<!-- @author: Emilie Zhang -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Booking</title>

    <link href="../FrontEndCode/RegisterCssPho.css" rel="stylesheet">
    <link href="../FrontEndCode/RegisterCssLap.css" rel="stylesheet">

    <style>
	.divForForm input[type="text"] {
	    width: 100%;
	    padding: 10px;
	    box-sizing: border-box;
	}
    </style>

    <script>
        function checkSearchForm() {
            const burl = document.forms["Form"]["burl"];

            const bURLRegex = /http:\/\/localhost\/quickmeet\/quickmeet_api\/bookingurl\.php\?url=.+$/;

            if (!bURLRegex.test(burl.value)) {
                alert("Please enter a valid Booking URL (e.g., starting with http://localhost/quickmeet/quickmeet_api/bookingurl.php?url=).");
                return false; // Prevent form submission
            }

            window.location.href = burl.value;
            return false;
        }
    </script>
</head>

<body style="background-color: #0C3D65;">
    <div class = "fromDivImgDiv">
        <div class = "divForForm">
            <form name = "Form" style="height: 250px;" onsubmit="return checkSearchForm();">
                    <h1 class="title">Search for your booking!</h1>
                    <div class="subtitle">Paste the booking url you want to search for:</div>
                    <input type="text" name="burl" placeholder="Booking URL" required>
                    <input style="background-color: #0088DC;" class="registerButton" type="submit" value="Submit">

                    <div class = "bottomText">Create an account to unlock more amazing features!</div>
            </form>
        </div>

        <div class = "divForSittingPers">
            <img class = "personImg" src="../FrontEndCode/Images/SittingPersonGoodColorSmaller.jpg">
        </div>
    </div>

    <script>
        function dropDownMenu() {
            const linksForPhone = document.querySelector(".LinksForPhone");
            linksForPhone.classList.toggle('show');

            const form = document.querySelector("form");
            form.classList.toggle('show');
        }
    </script>
</body>
</html>
