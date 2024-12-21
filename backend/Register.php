<?php

//@author:Hudanur Kacmaz

include '../backend/header.php';  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <link href="../FrontEndCode/RegisterCssPho.css" rel="stylesheet">
    <link href="../FrontEndCode/RegisterCssLap.css" rel="stylesheet">

    <script>
        function checkForm() {
            const fname = document.forms["Form"]["fname"].value;
            const lname = document.forms["Form"]["lname"].value;
            const username = document.forms["Form"]["username"].value;
            const email = document.forms["Form"]["email"];
            const password = document.forms["Form"]["password"].value;
            
            if (fname.length === 0 || lname.length === 0  || email.length === 0  || password.length === 0) {
                alert("Please fill in all required fields.");
                return false; // Prevent form submission
            }

	    const mcgillEmailRegex = /.+@mcgill\.ca|.+@mail\.mcgill\.ca/;
    	    if (!mcgillEmailRegex.test(email.value)) {
        	alert("Please enter a valid McGill email address (e.g., john.durant@mail.mcgill.ca).");
        	return false; // Prevent form submission
    	    }

 	    return true;

        }
    </script>
</head>

<body style="background-color: #0C3D65;">

    <div class = "fromDivImgDiv">
        <div class = "divForForm">
            <form name = "Form" action="../backend/register_backend.php" method="POST" onsubmit="return checkForm();">
                    <h1 class="title">Plan your bookings effortlessly with us!</h1>
                    <div class="subtitle">Let's get started.</div>
                    <input type="text" name="fname" placeholder="First name">

                    <input type="text" name="lname" placeholder="last name">

                    <input type="text" name="username" placeholder="(Optional) Username - defaults to first name">
    
                    <input type="email" name="email" placeholder="Student email jhon.durant@mail.mcgill.ca" >

                    <input type="text" name="password" placeholder="Password" >
    
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
