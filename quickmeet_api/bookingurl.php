<?php
include '../backend/bookingpagesheader.php';
?>

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
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mysql";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// ** ---------------- **//

$bookingUrl = $_GET['url'] ?? null;
$Tnow = time();

if ($bookingUrl) {
    // Fetch the booking details
    $sql = "SELECT * FROM Booking WHERE bookingurl = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $bookingUrl);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $booking = $result->fetch_assoc();
        // Render the booking page

        // echo "<h1>" . htmlspecialchars($booking['bookingtitle']) . "</h1>";
        // echo "<p>" . htmlspecialchars($booking['bookingdescription']) . "</p>";
        // echo "<p>Start: " . htmlspecialchars($booking['startdatetime']) . "</p>";
        // echo "<p>End: " . htmlspecialchars($booking['enddatetime']) . "</p>";
        
        // echo "<p>Full Booking URL: <a href='http://localhost/quickmeet/quickmeet_api/bookingurl.php?url=" . htmlspecialchars($booking['bookingurl']) . "' target='_blank'>http://localhost/quickmeet/quickmeet_api/bookingurl.php?url=" . htmlspecialchars($booking['bookingurl']) . "</a></p>";
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Booking Timeslot Reservation</title>

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
    } ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Booking Details</title>
        <style>
        body {
            font-family: Arial, sans-serif;
            font-family: 
            margin: 20px;
            background-color: #f9f9f9;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .day-section {
            margin: 20px 0;
            background-color: #ffffff;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        h2 {
            color: #444;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .booking-slot {
            margin: 10px 0;
            padding: 10px;
            background-color: #e7f3ff;
            border-radius: 5px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            
        }
        .booking-slot-full {
            margin: 10px 0;
            padding: 10px;
            background-color:rgb(253, 175, 175);
            border-radius: 5px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            
        }
        .booking-slot-past {
            margin: 10px 0;
            padding: 10px;
            background-color:rgb(188, 188, 188);
            border-radius: 5px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .outputDiv {
            display: flex;
        }

        .email-input input {
            border: 1px solid #ccc; 
            border-radius: 20px; 
            padding: 10px 15px; 
            width: 33%; 
            font-size: 16px; 
            box-sizing: border-box; 
            margin-top: 20px;
            outline: none; 
        }
        .email-input label {
            color: white; /* Sets the text color to white */
            font-size: 16px; /* Adjusts the font size */
            font-weight: bold; /* Makes the text bold (optional) */
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
            margin-top: 50%;
            margin: auto;
            padding: 15px 20px;
            position: relative;
            top: 50%; /* Push the modal to the vertical center */
            transform: translateY(-50%); /* Center it vertically */
            z-index: 1001;
            text-align: center;
        }
    </style>
    </head>
    <body>
        <br>
    <div class="email-input">
            <label for="email">Enter your email if you want to receive a confirmation :</label> <br>
            <input type="email" id="email" placeholder="example@example.com" required>
    </div>

<div id="output" class="outputDiv"></div>

<script>
    async function listTimeSlots() {
        const daysOfWeek = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
        const outputDiv = document.getElementById('output');
        const weekBookings = {};
        daysOfWeek.forEach(day => (weekBookings[day] = []));

        const now = new Date();
        const currentWeekStart = new Date(now.setDate(now.getDate() - now.getDay() + 1)); // Start of the current week (Monday)
        const currentWeekEnd = new Date(currentWeekStart);
        currentWeekEnd.setDate(currentWeekEnd.getDate() + 6); // End of the current week (Sunday)
        const currentMonth = new Date().getMonth();

        try {
            const timeslotResponse = await fetch('../quickmeet_api/apiendpoints.php/timeslot/<?php echo $bookingUrl ?>/bookingurl', {
                method: 'GET'
            });
            const timeslots = await timeslotResponse.json();

            timeslots.forEach(slot => {
                const startDate = new Date(slot.startdatetime);

                // Skip time slots outside the current week or month
                if (
                    startDate < currentWeekStart ||
                    startDate > currentWeekEnd ||
                    startDate.getMonth() !== currentMonth
                ) {
                    return;
                }

                // Adjust the day name to start the week on Monday
                const dayIndex = (startDate.getDay() + 6) % 7; // Sunday becomes 6, Monday becomes 0
                const dayName = daysOfWeek[dayIndex];

                weekBookings[dayName].push({
                    title: slot.slottitle,
                    host: slot.hostname,
                    location: slot.location,
                    maxslots: slot.maxslots,
                    numopenslots: slot.numopenslots,
                    start: slot.startdatetime,
                    end: slot.enddatetime,
                    sid: slot.sid
                });
            });

            // Render filtered time slots grouped by day
            for (const day of daysOfWeek) {
                const daySection = document.createElement('div');
                daySection.className = 'day-section';
                daySection.innerHTML = `<h2>${day}</h2>`;

                // Sort time slots by start time
                weekBookings[day].sort((a, b) => new Date(a.start) - new Date(b.start));

                if (weekBookings[day].length > 0) {
                    weekBookings[day].forEach(slot => {
                        //const now = time();
                        console.log(new Date());
                        console.log(`Past time: ${slot.end}`);
                        //console.log(now);
                        if (new Date(slot.end) < new Date()){
                            daySection.innerHTML += `
                            <div class="booking-slot-past">
                                <strong>${slot.title}</strong><br>
                                Host: ${slot.host}<br>
                                Location: ${slot.location}<br>
                                Availability: PAST - ${slot.numopenslots}/${slot.maxslots}<br>
                                ${new Date(slot.start).toLocaleTimeString()} - ${new Date(slot.end).toLocaleTimeString()}<br>
                            </div>
                            `;
                        } else if (slot.numopenslots == 0) {
                            daySection.innerHTML += `
                            <div class="booking-slot-full">
                                <strong>${slot.title}</strong><br>
                                Host: ${slot.host}<br>
                                Location: ${slot.location}<br>
                                Availability: FULL - 0/${slot.maxslots}<br>
                                ${new Date(slot.start).toLocaleTimeString()} - ${new Date(slot.end).toLocaleTimeString()}<br>
                            </div>
                            `;
                        } else {
                            daySection.innerHTML += `
                                <div class="booking-slot">
                                    <strong>${slot.title}</strong><br>
                                    Host: ${slot.host}<br>
                                    Location: ${slot.location}<br>
                                    Availability: ${slot.numopenslots}/${slot.maxslots}<br>
                                    ${new Date(slot.start).toLocaleTimeString()} - ${new Date(slot.end).toLocaleTimeString()}<br>
                                    <button onclick="reserveSlot('${slot.sid}')">Reserve</button>
                                </div>
                            `;
                        }
                    });
                } else {
                    daySection.innerHTML += "<p>No bookings available for this day.</p>";
                }
                outputDiv.appendChild(daySection);
            }
        } catch (error) {
            console.error('Error fetching time slots:', error);
            outputDiv.innerHTML = "";
        }
    }

    async function reserveSlot(sid) {
        const toemail = document.getElementById('email').value ?? '';
        // if yes, reserve        
        fetch('../quickmeet_api/apiendpoints.php/reservation', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    sid: sid,
                    email: toemail,
                    notes: ""
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok, failed to reserve.');
                }
                return response.json();
            })
            .then(newReservation => {
                alert(`Success! Reservation created: ${newReservation.reservation_url}`);
                window.location.href = newReservation.reservation_url; // Redirect to the reservation URL
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Failed to create booking.");
            });
    }
    listTimeSlots();
</script>

<div class = buttons>
    <button onclick="openRequestAvailability()">Request a Timeslot Availability</button>
</div>

<div id="requestAvailabilityModal" class="modal" 
        style="display: none;
         
         text-align: center;
         width: 100%; 
         margin: auto;
        
        ">


    <div class="modal-content" style="position: relative;">
        <span class="close" 
            style="color: white; cursor: pointer; position: absolute; top: 5px; right: 10px;" 
            onclick="closeRequestAvailabilityModal()">
            &times;
        </span>
        <!-- <h2>Request Availability </h2> -->
        <form id="timeslotForm">
            <h3 style="color: white;">Request New Availability</h3>
            <input type="datetime-local" id="availstartTime" placeholder="Start Time" required><br><br>
            <input type="datetime-local" id="availendTime" placeholder="End Time" required><br><br>
            <button type="button" onclick="submitAvailability()">Send</button>
        </form>
    </div>
</div>

<script>
    function closeRequestAvailabilityModal(){
        document.getElementById('requestAvailabilityModal').style.display = 'none';
    }

    function openRequestAvailability(){
        document.getElementById('requestAvailabilityModal').style.display = 'block';
    }

    function submitAvailability(){
        const availStartTime = document.getElementById('availstartTime').value;
        const availEndTime = document.getElementById('availendTime').value;

        // Validate inputs
        if (!availStartTime || !availEndTime) {
            alert('Please fill in all required fields.');
            return false;
        }

        // edge case
        if (availStartTime >= availEndTime){
            alert('The start date must be before end date.');
            return false;
        }

        try {
            const bkurl = "<?php echo htmlspecialchars($bookingUrl); ?>";

            fetch('../quickmeet_api/apiendpoints.php/availability', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    bookingurl: bkurl,
                    startdatetime: availStartTime,
                    enddatetime: availEndTime
                })
            })
            .then(response => {
                if (!response.ok) {
                    alert('Failed to send availability request: ' + response.error);
                }
                else {
                    console.log(response);
                    alert('Availability Request sent successfully!');
                    closeRequestAvailabilityModal();
                }
            })
        } catch (error) {
            console.error('Error sending availability request:', error);
            alert('An error occurred while requesting the timeslot availability.');
        }

        return false;
    }
</script>

    </body>
    </html>
    <?php
} else {
    echo "<h1>Invalid URL</h1>";
}
?>
