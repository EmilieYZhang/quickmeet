<?php
// @author Emilie Zhang Inherited from header.php but speficially revamping for public facing pages

require '../backend/db_connect.php'; // Include database connection
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
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<style>
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

        @media screen and (max-width: 768px){
        .LinksForPhone {
            display: none;
            flex-direction: column; /* Stack links vertically */
            background-color: #15182B;
            position: absolute;
            top: 90px;
            right: 10px;
            width: 82%;
            border-radius: 5px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        
        .LinksForPhone.show {
            display: flex;
        }
        

        .LinksForPhone a {
            text-decoration: none;
            color: white;
            padding: 10px 15px;
            border-bottom: 1px solid #303346;
        }
        

        .LinksForPhone a:last-child {
            border-bottom: none;
        }
        

        .LinksForPhone a:hover {
            background-color: #303346;
        }
        
    
        .hamUnderline {
            display: block;
            width: 10%;
            cursor: pointer;
            position: absolute;
            right: 0px;
            padding: 10px;
            color: white;
            font-size: 35px;
            text-decoration: none;
        }
        

        .hamUnderline:hover {
            color: #0088DC;
        }
        

        .header {
            display: none;
        }
    }
    @media screen and (min-width: 769px) {
    .hamburger{
        display: none;
    }
}
</style>

<!-- HTML Header Content -->
<div class="header">
    <a href="../backend/Landing.php"><img src="../FrontEndCode/Images/NewLogoGoodColor.jpg" style="height: 8vw;"></a>
    
    <?php if ($isLoggedIn): ?>
        <!-- Logged-in user header -->
        <div class="Links">
            <a href="../backend/dashboard.php">Dashboard</a>
            <a href="../backend/FAQ.php">FAQ</a>
            <a href="../backend/BookNow.php">Search Booking</a>
            <a href="../backend/logout.php">Logout</a>
        </div>
    <?php else: ?>
        <!-- Guest user header -->
        <div class="Links">
            <a href="../backend/Register.php">Register</a>
            <a href="../backend/Login.php">Login</a>
            <a href="../backend/FAQ.php">FAQ</a>
            <a href="../backend/BookNow.php">Search Booking</a>
        </div>
    <?php endif; ?>
</div>

<!-- Mobile/Responsive Menu -->
<div class="hamburger" onclick="dropDownMenu()">
    <div style="display: flex;">
        <a href="../backend/Landing.php"><img src="../FrontEndCode/Images/NewLogoGoodColor.jpg" style="height: 80px;"></a>
        <a class="hamUnderline" href="#">☰</a>
    </div>
</div>
<div class="LinksForPhone">
    <?php if ($isLoggedIn): ?>
        <a href="../backend/dashboard.php">Dashboard</a>
        <a href="../backend/FAQ.php">FAQ</a>
        
        <a href="../backend/BookNow.php">Search Booking</a>
        <a href="../backend/logout.php">Logout</a>
    <?php else: ?>
        <a href="../backend/Register.php">Register</a>
        <a href="../backend/Login.php">Login</a>
        <a href="../backend/FAQ.php">FAQ</a>
        
        <a href="../backend/BookNow.php">Search Booking</a>
    <?php endif; ?>
</div>

<script>
        function dropDownMenu() {
            const linksForPhone = document.querySelector(".LinksForPhone");
            linksForPhone.classList.toggle('show');
        }
</script>

