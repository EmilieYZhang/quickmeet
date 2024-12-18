<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/html; charset=utf-8');

// ** THIS IS FOR MIMI SERVER HOST **//
// require_once '../config/config.php';

// // create a connection to the MySQL database
// $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// // check the connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// } else {
//     echo "Database connected successfully!";
// }
// ** ----------------  **//

// ** THIS IS FOR LOCAL HOST **//
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

$bookingUrl = $_GET['url'] ?? null;

if ($bookingUrl) {
    // Fetch the booking details
    $sql = "SELECT * FROM Booking WHERE editbookingurl = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $bookingUrl);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $booking = $result->fetch_assoc();
        $ogbookingurl = $booking['bookingurl'];
        // Render the booking page

        // echo "<h1>" . htmlspecialchars($booking['bookingtitle']) . "</h1>";
        // echo "<p>" . htmlspecialchars($booking['bookingdescription']) . "</p>";
        // echo "<p>Start: " . htmlspecialchars($booking['startdatetime']) . "</p>";
        // echo "<p>End: " . htmlspecialchars($booking['enddatetime']) . "</p>";

        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Booking Details</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f5f5f5;
                    margin: 20px;
                    color: #333;
                }
                .booking-container {
                    max-width: 600px;
                    margin: auto;
                    padding: 20px;
                    background-color: #ffffff;
                    border-radius: 10px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                }
                h1 {
                    color: #444;
                    font-size: 24px;
                    text-align: center;
                }
                p {
                    margin: 10px 0;
                    line-height: 1.6;
                }

                .bolder {
                    font-weight: bold;
                }

                a{
                    color: #007BFF;
                    text-decoration: none;
                    font-weight: bold;
                }

                a:hover {
                    text-decoration: underline;
                }
            </style>
        </head>
        <body style='background-color: #0C3D65'>
            <div class='booking-container'>
                <h1>" . htmlspecialchars($booking['bookingtitle']) . "</h1>
                <p><span class = 'bolder'>Description:</span> " . htmlspecialchars($booking['bookingdescription']) . "</p>
           
                <p><span class = 'bolder'>Start:</span> " . htmlspecialchars($booking['startdatetime']) . "</p>
                <p><span class = 'bolder'>End: </span>" . htmlspecialchars($booking['enddatetime']) . "</p>
                
                <p><span class = 'bolder'>Full Booking URL:</span> <a href='http://localhost/quickmeet/quickmeet_api/bookingurl.php?url=" . htmlspecialchars($booking['bookingurl']) . "' target='_blank'>http://localhost/quickmeet/quickmeet_api/bookingurl.php?url=" . htmlspecialchars($booking['bookingurl']) . "</a></p>
                
            </div>
        </body>
        </html>"; 
        // Add logic to display timeslot options and handle reservations
    } else {
        echo "<h1>Booking not found</h1>";
    } 
    $stmt->close();
} else {
    echo "<h1>Invalid URL</h1>";
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Details</title>

    <style>
        .buttons{
            text-align: center;
        }

        button{
            border-radius: 10px;
            width: 210px;
            height: 50px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;    
        }

        button:hover {
        background-color: #38C4DB;
    }



    </style>


</head>
<body>

<ul id="user-list"></ul>
    <div class = buttons>
        <button  onclick="AddNewTimeslot()">Add New Timeslot</button>
        <button  onclick="EditBooking()">Edit Booking</button>
        <button  onclick="ViewAvailability()">View Availability Requests</button>
    </div>
<ul id="availability-list"></ul>
<script>
    function AddNewTimeslot(){
        return true;
    }

    function EditBooking(){
        return true;
    }

    function ViewAvailability(){
        fetch('../quickmeet_api/apiendpoints.php/availability/<?php echo $ogbookingurl ?>/bookingurl', { method: 'GET' })
        .then(response => {
                // Check if the response is successful
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                // Attempt to parse JSON
                return response.json();
            })
            .then(slots => {
                console.log(slots); 
                const slotList = document.getElementById('availability-list');
                slotList.innerHTML = '';

                if (slots.error){
                    const li = document.createElement('li');
                    li.textContent = "No availability requests received at the moment.";
                    slotList.appendChild(li);
                }
                else{
                    slots.forEach(slot => {
                        const li = document.createElement('li');
                        li.textContent = `Start: ${slot.startdatetime} - End: ${slot.enddatetime}`;
                        slotList.appendChild(li);
                    });
                }
            })
            .catch(error => console.error('Error fetching users:', error));
    }
</script>
<script>
console.log('../quickmeet_api/apiendpoints.php/timeslot/<?php echo $bookingUrl ?>/bookingurl');

fetch('../quickmeet_api/apiendpoints.php/timeslot/<?php echo $bookingUrl ?>/bookingurl', { method: 'GET' })
        .then(response => {
                // Check if the response is successful
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                // Attempt to parse JSON
                return response.json();
            })
            .then(slots => {
                console.log("execute GET /apiendpoints.php/slots"); 
                const slotList = document.getElementById('user-list');
                slotList.innerHTML = '';
                slots.forEach(slot => {
                    const li = document.createElement('li');
                    li.textContent = `${slot.slottitle} - ${slot.location} - ${slot.hostname} - Start: ${slot.startdatetime} - End: ${slot.enddatetime} - Availability ${slot.numopenslots}`;
                    slotList.appendChild(li);
                });
            })
            .catch(error => console.error('Error fetching users:', error));
</script>
</body>
</html>
