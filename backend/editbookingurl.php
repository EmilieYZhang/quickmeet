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
            border: none;  
        }

        button:hover {
            background-color: #469FF0;
        }

        .modal {
            position: fixed; /* Ensures it stays in place relative to the viewport */
            top: 0;
            left: 0;
            width: 100%; /*careful there is inline css for this.*/ 
            height: 100%; 
            background-color: rgba(0, 0, 0, 0.5); 
            z-index: 1000; 
            display: none; 
            backdrop-filter: blur(5px);

        }
        .modal-content{
            background-color: #0C3D65;
            border-radius: 8px;
            width: 500px;
            margin: auto;
            padding: 15px 20px;
            position: relative;
            top: 20%; /* Push the modal to the vertical center */
            transform: translateY(-50%); /* Center it vertically */
            z-index: 1001;
            text-align: center;
        }

        input {
            width: 300px; 
            padding: 10px; 
            border: 1px solid #ccc; 
            border-radius: 8px; 
            font-size: 16px; 
            box-sizing: border-box; 
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
<!-- this is for add new time -->
<div id="timeslotModal" class="modal" 
        style="display: none;
         
         text-align: center;
         width: 100%; 
         margin: auto;
        
        ">


    <div class="modal-content" style="position: relative;">
        <span class="close" 
            style="color: white; cursor: pointer; position: absolute; top: 5px; right: 10px;" 
            onclick="closeModal()">
            &times;
        </span>
        <!-- <h2>Add New Time Slot</h2> -->
        <form id="timeslotForm">
            <input type="text" id="slotTitle" placeholder="Time Slot Title" required><br><br>
            <input type="text" id="hostName" placeholder="Host Name" required><br><br>
            <input type="text" id="location" placeholder="Location" required><br><br>
            <input type="datetime-local" id="startTime" placeholder="Start Time" required><br><br>
            <input type="datetime-local" id="endTime" placeholder="End Time" required><br><br>
            <input type="number" id="maxSlots" placeholder="Max Slots" required><br><br>
            <button type="button" onclick="submitTimeslot()">Add Time Slot</button>
        </form>
    </div>
</div>

<!-- this is for Edit booking -->

<div id="editBookingModal" class="modal" 
    style="display: none; 
           text-align: center; 
           width: 100%; 
           margin: auto;">
    <div class="modal-content" style="position: relative;">
        <span class="close" 
            style="color: white; cursor: pointer; position: absolute; top: 5px; right: 10px;" 
            onclick="closeEditBookingModal()">
            &times;
        </span>
        <form id="editBookingForm">
            <h3>Edit Booking</h3>
            <input type="text" id="bookingTitle" placeholder="Booking Title" required><br><br>
            <textarea id="bookingDescription" placeholder="Description" style="width: 300px; height: 100px;"></textarea><br><br>
            <input type="datetime-local" id="bookingStartTime" placeholder="Start Time" required><br><br>
            <input type="datetime-local" id="bookingEndTime" placeholder="End Time" required><br><br>
            <button type="button" onclick="submitEditBooking()">Save Changes</button>
        </form>
    </div>
</div>


<script>
    function AddNewTimeslot(){
        document.getElementById('timeslotModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('timeslotModal').style.display = 'none';
    }


    const bkurl = "<?php echo htmlspecialchars($ogbookingurl); ?>";

    // old add time slot which is working for single event
    // async function submitTimeslot() {
    //     console.log('Booking URL:', bkurl);
    //     const slotTitle = document.getElementById('slotTitle').value;
    //     const hostName = document.getElementById('hostName').value;
    //     const location = document.getElementById('location').value;
    //     const startTime = document.getElementById('startTime').value;
    //     const endTime = document.getElementById('endTime').value;
    //     const maxSlots = document.getElementById('maxSlots').value;

    //     // Validate inputs
    //     if (!slotTitle || !hostName || !location || !startTime || !endTime || !maxSlots) {
    //         alert('Please fill in all fields.');
    //         return;
    //     }

    //     try {
    //         const response = await fetch('../quickmeet_api/apiendpoints.php/timeslot', {
    //             method: 'POST',
    //             headers: { 'Content-Type': 'application/json' },
    //             body: JSON.stringify({
    //                 bookingurl: bkurl, 
    //                 slottitle: slotTitle,
    //                 hostname: hostName,
    //                 location: location,
    //                 startdatetime: startTime,
    //                 enddatetime: endTime,
    //                 // numopenslots: maxSlots;
    //                 maxslots: maxSlots
    //             })
    //         });

    //         if (response.ok) {
    //             alert('Time slot added successfully!');
    //             closeModal();
    //         } else {
    //             alert('Failed to add the time slot.');
    //         }
    //     } catch (error) {
    //         console.error('Error adding time slot:', error);
    //         alert('An error occurred while adding the time slot.');
    //     }
    // }
    //end of old add time slot which is working

    //new timeslot for reccuring add
    async function submitTimeslot() {
    console.log('Booking URL:', bkurl);
    const slotTitle = document.getElementById('slotTitle').value;
    const hostName = document.getElementById('hostName').value;
    const location = document.getElementById('location').value;
    const startTime = document.getElementById('startTime').value;
    const endTime = document.getElementById('endTime').value;
    const maxSlots = document.getElementById('maxSlots').value;

    // Validate inputs
    if (!slotTitle || !hostName || !location || !startTime || !endTime || !maxSlots) {
        alert('Please fill in all fields.');
        return;
    }

    try {
        // Fetch the booking details to get the booking start and end date
        const bookingResponse = await fetch(`../quickmeet_api/apiendpoints.php/booking/${bkurl}/bookingurl`);
        const bookingDetails = await bookingResponse.json();

        if (!bookingResponse.ok) {
            alert('Failed to fetch booking details.');
            return;
        }

        const bookingStart = new Date(bookingDetails.startdatetime);
        const bookingEnd = new Date(bookingDetails.enddatetime);

        // Create a recurring schedule
        const slotStartTime = new Date(startTime);
        const slotEndTime = new Date(endTime);

        while (slotStartTime <= bookingEnd) {
            // Skip time slots that are before the booking start date
            if (slotStartTime >= bookingStart) {
                // Make a POST request for the current time slot
                const response = await fetch('../quickmeet_api/apiendpoints.php/timeslot', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        bookingurl: bkurl,
                        slottitle: slotTitle,
                        hostname: hostName,
                        location: location,
                        startdatetime: slotStartTime.toISOString(),
                        enddatetime: slotEndTime.toISOString(),
                        maxslots: maxSlots
                    })
                });

                if (!response.ok) {
                    console.error('Failed to create time slot:', await response.text());
                    alert('Failed to create one or more time slots.');
                    return;
                }
            }

            // Move to the same time next week
            slotStartTime.setDate(slotStartTime.getDate() + 7);
            slotEndTime.setDate(slotEndTime.getDate() + 7);
        }

        alert('Recurring time slots added successfully!');
        closeModal();
    } catch (error) {
        console.error('Error adding time slots:', error);
        alert('An error occurred while adding the time slots.');
    }
}
//end of new time slot add

// start of script for edit booking
function EditBooking() {
        // Fetch the current booking details
        const bookingTitleInput = document.getElementById('bookingTitle');
        const bookingDescriptionInput = document.getElementById('bookingDescription');
        const bookingStartTimeInput = document.getElementById('bookingStartTime');
        const bookingEndTimeInput = document.getElementById('bookingEndTime');

        //here I fill the modal with the current descriptions etc..
        fetch(`../quickmeet_api/apiendpoints.php/booking/${bkurl}/bookingurl`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch booking details.');
                }
                return response.json();
            })
            .then(data => {
                bookingTitleInput.value = data.bookingtitle || '';
                bookingDescriptionInput.value = data.bookingdescription || '';
                bookingStartTimeInput.value = data.startdatetime || '';
                bookingEndTimeInput.value = data.enddatetime || '';
                document.getElementById('editBookingModal').style.display = 'block';
            })
            .catch(error => {
                console.error('Error fetching booking details:', error);
                alert('Failed to load booking details.');
            });
    }

    function closeEditBookingModal() {
        document.getElementById('editBookingModal').style.display = 'none';
    }

    async function submitEditBooking() {
        const bookingTitle = document.getElementById('bookingTitle').value;
        const bookingDescription = document.getElementById('bookingDescription').value;
        const bookingStartTime = document.getElementById('bookingStartTime').value;
        const bookingEndTime = document.getElementById('bookingEndTime').value;

        // Validate inputs
        if (!bookingTitle || !bookingStartTime || !bookingEndTime) {
            alert('Please fill in all required fields.');
            return;
        }

        try {
            const response = await fetch('../quickmeet_api/apiendpoints.php/booking/edit', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    bookingurl: bkurl,
                    bookingtitle: bookingTitle,
                    bookingdescription: bookingDescription,
                    startdatetime: bookingStartTime,
                    enddatetime: bookingEndTime
                })
            });

            const result = await response.json();

            if (response.ok) {
                alert('Booking updated successfully!');
                closeEditBookingModal();
                location.reload(); // Reload the page
            } else {
                alert('Failed to update booking: ' + result.error);
            }
        } catch (error) {
            console.error('Error updating booking:', error);
            alert('An error occurred while updating the booking.');
        }
    }
    //end of script for edit booking

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
