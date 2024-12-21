<!-- (Serhii Artemenko), (Emilie Yahui Zhang) -->
<?php
include 'bookingpagesheader.php';
// @author: Emilie Zhang for unique edit booking url generation/routing, history of timeslots display frontend/backend and api calls backend
// @author: Serhii Artemenko for frontend interactions, css display and template api calls (e.g. Edit booking )
?>
<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/html; charset=utf-8');

// ** THIS IS FOR MIMI SERVER HOST **//
require_once '../config/config.php';

// create a connection to the MySQL database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// ** ----------------  **//

// ** THIS IS FOR LOCAL HOST **//

$bookingUrl = $_GET['url'] ?? null;

if (!$isLoggedIn){
    header("Location: ../backend/Landing.php");
    exit();
}

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
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Edit Booking Details</title>

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
                
                <p><span class = 'bolder'>Full Booking URL:</span> <a href='https://www.cs.mcgill.ca/~ezhang19/quickmeet/quickmeet_api/bookingurl.php?url=" . htmlspecialchars($booking['bookingurl']) . "' target='_blank'>Link</a></p>
                
            </div>
        </body>
        </html>"; 
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
            position: fixed; 
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
            width: 33%;
            margin-top: 0%;
            margin: auto;
            padding: 80px 20px;
            position: relative;
            top: 50%; 
            transform: translateY(-50%); /* Center it vertically */
            z-index: 1001;
            text-align: center;
        }

        .lightmodal-content{
            background-color:rgb(106, 153, 190);
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

        input {
            width: 70%; 
            padding: 10px; 
            border: 1px solid #ccc; 
            border-radius: 8px; 
            font-size: 16px; 
            box-sizing: border-box; 
        }

        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
    </style>


</head>
<body>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timeslot Calendar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        .calendar {
            max-width: 600px;
            margin: 20px auto;
            padding: 10px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .day-header {
            font-size: 1.2em;
            margin: 10px 0;
            color: #333;
        }

        .timeslot {
            display: flex; 
            align-items: center; /* Vertically align items */
            justify-content: space-between; 
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #fafafa;
        }

        .timeslot-title {
            font-weight: bold;
        }

        .buttons {
            display: flex;
            gap: 20px; 
            justify-content: center;
            align-items: center;
        }

        .buttons button {
            padding: 10px 15px; 
            font-size: 16px; 
            cursor: pointer; 
            margin: 50px 0px;
        }

        .delete-icon {
            align-self: flex-end; /* Align the icon to the right */
            width: 20px; 
            height: 20px;
            cursor: pointer; 
        }

        .delete-icon:hover {
            filter: brightness(0.8); 
        }

        @media (max-width: 600px) {
            .calendar {
                padding: 5px;
            }
            .day-header {
                font-size: 1em;
            }
            .timeslot {
                flex-direction: column; /* Stack items vertically */
                align-items: flex-start; /* Align items to the left */
            }
            .delete-icon {
                align-self: flex-end; /* Align the icon to the right */
                margin-top: 5px; /* Add spacing for stacked layout */
            }
        }
    </style>
</head>

    <div id="calendar" class="calendar">You currently have no timeslots, please add a new Timeslot.</div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script>
        // @author: Emilie Zhang for loadCalendar() and deleteTimeslot()
        async function loadCalendar() {
            const timeslotResponse = await fetch('../quickmeet_api/apiendpoints.php/timeslot/<?php echo $ogbookingurl ?>/bookingurl', {
                method: 'GET'
            });
            
            const timeslots = await timeslotResponse.json();

            if (timeslots !== undefined && timeslots !== null && Array.isArray(timeslots)) {
                const groupedtimeslots = {};
                timeslots.forEach(timeslot => {
                    const date = timeslot.startdatetime.split(' ')[0];
                    if (!groupedtimeslots[date]) groupedtimeslots[date] = [];
                    groupedtimeslots[date].push(timeslot);
                });


                const calendar = document.getElementById('calendar');
                calendar.innerHTML = '';
                Object.keys(groupedtimeslots).sort().forEach(date => {
                    const dayHeader = document.createElement('div');
                    dayHeader.className = 'day-header';
                    dayHeader.textContent = moment(date).format('dddd, MMMM D, YYYY');
                    calendar.appendChild(dayHeader);

                    groupedtimeslots[date].forEach(timeslot => {
                        const timeslotDiv = document.createElement('div');
                        timeslotDiv.className = 'timeslot';

                        timeslotDiv.innerHTML = `
                            <div class="timeslot-title">${timeslot.slottitle}</div>
                            <div>${moment(timeslot.startdatetime).format('hh:mm A')} - ${moment(timeslot.enddatetime).format('hh:mm A')}</div>
                            <div>${timeslot.numopenslots}/${timeslot.maxslots}</div>
                            <img src="bin.png" alt="Delete" class="delete-icon" onclick="deleteTheTimeslot('${timeslot.sid}', ${timeslot.numopenslots}, ${timeslot.maxslots})">
                        `;
                        calendar.appendChild(timeslotDiv);
                    });
                });
            }
        }

        loadCalendar();

        function deleteTheTimeslot(timeslot_id, filled, max){
            if (confirm("Are you sure you want to delete this timeslot?")) {
                filled = parseInt(filled);
                max = parseInt(max);
                if (filled < max){
                    alert('Unable to delete this timeslot, people have already reserved.');
                }
                else {
                   fetch(`../quickmeet_api/apiendpoints.php/timeslot/${timeslot_id}`, { method: 'DELETE' })
                    .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                                alert('Did not suceed in deleting timeslot');
                            } else{
                                alert('Successfully deleted');
                                window.location.reload(true);
                            }
                        }) 
                }
            }
        }
    </script>
<br>
<div class="buttons">
    <button onclick="AddNewTimeslot()">Add New Timeslot</button>
    <button onclick="EditBooking()">Edit Booking</button>
    <button onclick="ViewAvailability()">View Availability Requests</button>
</div>

<!-- this is for viewing availability -->
<div id="availabilityModal" class="modal" 
        style="display: none;
         
         text-align: center;
         width: 100%; 
         margin: auto;
        
        ">
    <div class="lightmodal-content" style="position: relative;">
        <span class="close" 
            style="color: white; cursor: pointer; position: absolute; top: 5px; right: 10px;" 
            onclick="closeAvailabilityModal()">
            &times;
        </span>
        <!-- <h2>Display availability</h2> -->
        <table id="availability-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                </tr>
            </thead>
            <tbody>
                <!-- Rows for availability dynamically appended here -->
            </tbody>
        </table>
    </div>
</div>

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
            <h3 style="color: white;">New Timeslot</h3>
            <input type="text" id="slotTitle" placeholder="Time Slot Title" required><br><br>
            <input type="text" id="hostName" placeholder="Host Name" required><br><br>
            <input type="text" id="location" placeholder="Location" required><br><br>
            <input type="datetime-local" id="startTime" placeholder="Start Time" required><br><br>
            <input type="datetime-local" id="endTime" placeholder="End Time" required><br><br>
            <input type="number" id="maxSlots" placeholder="Max Slots" required min="1"><br><br>
            <button type="button" onclick="submitTimeslot()" style="width: 70%;">Add Time Slot</button>
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
            <button type='button' onclick="submitEditBooking()">Save Changes</button>
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

    function closeAvailabilityModal(){
        document.getElementById('availabilityModal').style.display = 'none';
    }


    const bkurl = "<?php echo htmlspecialchars($ogbookingurl); ?>";

    //new timeslot for reccuring add
    async function submitTimeslot() {
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

            const slotStartTime = new Date(startTime);
            const slotEndTime = new Date(endTime);

            /** ----The code below is taken from the internet for adjusting timestamps for wacky timezones ----**/
            // source: https://stackoverflow.com/questions/17415579/how-to-iso-8601-format-a-date-with-timezone-offset-in-javascript
            // Function to format the dates as local versions
            const localStartTime = slotStartTime.toISOString().slice(0, 19).replace('T', ' ');
            const localEndTime = slotEndTime.toISOString().slice(0, 19).replace('T', ' ');

            // Adjust to local timezone
            const offset = slotStartTime.getTimezoneOffset(); // Offset in minutes
            const adjustedStartTime = new Date(slotStartTime.getTime() - offset * 60 * 1000).toISOString().slice(0, 19).replace('T', ' ');
            const adjustedEndTime = new Date(slotEndTime.getTime() - offset * 60 * 1000).toISOString().slice(0, 19).replace('T', ' ');

            console.log("Adjusted Start Time (Local):", adjustedStartTime);
            console.log("Adjusted End Time (Local):", adjustedEndTime);
            /** -----conclude wacky timezone adjustments----- **/

            //while (slotStartTime <= bookingEnd) {
                // Skip time slots that are before the booking start date
                if (slotStartTime >= slotEndTime) {
                    alert('The start date of the timeslot must be before the end date.');
                    return;
                } else if (slotStartTime < bookingStart){
                    alert('The start time can not be before the booking start time.');
                    return;
                } else if (slotEndTime > bookingEnd){
                    alert('The end time can not be after the booking end time.');
                    return;
                } else if (slotStartTime >= bookingStart) {
                    // Make a POST request for the current time slot
                    const response = await fetch('../quickmeet_api/apiendpoints.php/timeslot', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            bookingurl: bkurl,
                            slottitle: slotTitle,
                            hostname: hostName,
                            location: location,
                            startdatetime: adjustedStartTime,
                            enddatetime: adjustedEndTime,
                            maxslots: maxSlots
                        })
                    });

                    if (!response.ok) {
                        console.error('Failed to create time slot:', await response.text());
                        alert('Failed to create one or more time slots.');
                        return;
                    }
                    else{
                        alert('Time slot added successfully!');
                        closeModal();
                        window.location.reload(true);
                        return;
                    }
                } else {
                    alert("something else happened");
                    return;
                }

        } catch (error) {
            console.error('Error adding time slots:', error);
            alert('An error occurred while adding the time slots.');
        }
    }

    // start of script for edit booking
    async function EditBooking() {
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
            return false;
        }

        // edge case
        if (bookingStartTime >= bookingEndTime){
            alert('The start date must be before end date.');
            return false;
        }

        try {
            fetch('../quickmeet_api/apiendpoints.php/booking/edit', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    bookingurl: bkurl,
                    bookingtitle: bookingTitle,
                    bookingdescription: bookingDescription,
                    startdatetime: bookingStartTime,
                    enddatetime: bookingEndTime
                })
            })
            .then(response => {
                if (!response.ok) {
                    alert('Failed to update booking: ' + response.error);
                }
                else {
                    alert('Booking updated successfully!');
                    closeEditBookingModal();
                    location.reload(); // Reload the page
                }
            })
        } catch (error) {
            console.error('Error updating booking:', error);
            alert('An error occurred while updating the booking.');
        }

        return false;
    }

    async function ViewAvailability(){
        console.log("Inside viewing");
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

                document.getElementById('availabilityModal').style.display = 'block';
                const tableBody = document.getElementById('availability-table').querySelector('tbody');
                tableBody.innerHTML = ''; // Clear existing rows

                if (slots.error) {
                    // Display a message in a single-row table
                    const row = document.createElement('tr');
                    const cell = document.createElement('td');
                    cell.textContent = "No availability requests received at the moment.";
                    cell.colSpan = 3;
                    row.appendChild(cell);
                    tableBody.appendChild(row);
                } else {
                    let index = 1;
                    slots.forEach(slot => {
                        const row = document.createElement('tr');

                        const numCell = document.createElement('td');
                        numCell.textContent = `${index}`;
                        index = index+1;
                        row.appendChild(numCell);

                        const startCell = document.createElement('td');
                        startCell.textContent = slot.startdatetime;
                        row.appendChild(startCell);

                        const endCell = document.createElement('td');
                        endCell.textContent = slot.enddatetime;
                        row.appendChild(endCell);

                        tableBody.appendChild(row);
                    });
                }
            })
            .catch(error => console.error('Error parsing availability JSON:', error));
        }
    </script>
    <script>
    function fetchTimeslots(){
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
                    console.log("execute GET /apiendpoints.php/slots to fetch timeslots"); 
                    const slotList = document.getElementById('user-list');
                    slotList.innerHTML = '';
                    slots.forEach(slot => {
                        const li = document.createElement('li');
                        li.textContent = `${slot.slottitle} - ${slot.location} - ${slot.hostname} - Start: ${slot.startdatetime} - End: ${slot.enddatetime} - Availability ${slot.numopenslots}`;
                        slotList.appendChild(li);
                    });
                })
                .catch(error => console.error('Error fetching users:', error));
    }
    </script>
</body>
</html>
