<?php
require '../backend/db_connect.php'; // Include database connection
session_start();  // Start the session

//@author: Hudanur Kacmaz

if (isset($_SESSION['ticket'])) {
    $ticket = $_SESSION['ticket'];

    $stmt = $conn->prepare("SELECT user_id, expiry FROM user_tickets WHERE ticket = ?");
    if ($stmt === false) {
        die("Database query failed.");
    }

    $stmt->bind_param("s", $ticket);
    $stmt->execute();
    $stmt->bind_result($userId, $expiry);
    $stmt->fetch();
    $stmt->close();


    $newExpiry = time() + 3600;  // Extend for 1 more hour
    $updateStmt = $conn->prepare("UPDATE user_tickets SET expiry = ? WHERE ticket = ?");
    $updateStmt->bind_param("is", $newExpiry, $ticket);
    $updateStmt->execute();
    $updateStmt->close();

    $isLoggedIn = true;
} else {
    $isLoggedIn = false;
    $conn->close();
}
?>

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

