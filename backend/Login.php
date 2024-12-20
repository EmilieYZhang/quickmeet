<?php
include '../backend/header.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

   
    <link href="../FrontEndCode/LoginCssPho.css" rel="stylesheet">
    <link href="../FrontEndCode/LoginCssLap.css" rel="stylesheet">

    <script>
        function checkForm() {
            const email = document.forms["Form"]["email"].value;
            const password = document.forms["Form"]["password"].value;
            
            // Check if any field is empty
            if (email.length === 0  || password.length === 0) {
                alert("Please fill in all fields.");
                return false; // Prevent form submission
            }
            return true; // Proceed with form submission if validation passes
        }
    </script>
</head>



<body style="background-color: #0C3D65;">

<div class = "fromDivImgDiv">
    <div class = "divForForm">
        <form name = "Form" action="../backend/login_backend.php" method="POST" onsubmit="return checkForm();">
            <h1 class="title">Login</h1>

            <input type="text" name="email" placeholder="email" >

            <input type="password" name="password" placeholder="Password" >

            <input style="background-color: #0088DC;" class="registerButton" type="submit" value="Login">
        </form>

    </div>

    <div class = "divForSittingPers"> 
        <img class = "personImg" src="../FrontEndCode/SittingPersonGoodColorSmaller.jpg">
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
