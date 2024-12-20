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



    </style>


    </head>
    <body>
        <br>
    <div class="email-input">
            <label for="email">Enter your email if you want to receive a confiramtion :</label> <br>
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
                        daySection.innerHTML += `
                            <div class="booking-slot">
                                <strong>${slot.title}</strong><br>
                                Host: ${slot.host}<br>
                                Location: ${slot.location}<br>
                                ${new Date(slot.start).toLocaleTimeString()} - ${new Date(slot.end).toLocaleTimeString()}<br>
                                <button onclick="reserveSlot('${slot.sid}')">Reserve</button>
                            </div>
                        `;
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
        try {
            const response = await fetch('../quickmeet_api/apiendpoints.php/reservation', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ sid: sid })
            });

            if (response.ok) {
                alert("You have successfully reserved this time slot!");
            } else {
                alert("Failed to reserve the slot.");
            }
        } catch (error) {
            console.error("Error reserving slot:", error);
            alert("An error occurred while reserving the slot.");
        }
    }
//     async function reserveSlot(sid, buttonElement) {
//     try {
//         const response = await fetch('../quickmeet_api/apiendpoints.php/reservation', {
//             method: 'POST',
//             headers: { 'Content-Type': 'application/json' },
//             body: JSON.stringify({ sid: sid })
//         });

//         const data = await response.json();
//         if (response.ok) {
//             // Locate the parent container of the button
//             const bookingSlot = buttonElement.parentElement;

//             // Find the "places left" text element
//             const availableSlotsText = bookingSlot.querySelector('strong:nth-of-type(2)');
//             let availableSlots = parseInt(availableSlotsText.textContent.split(' ')[0], 10);

//             if (availableSlots > 1) {
//                 availableSlots -= 1;
//                 availableSlotsText.textContent = `${availableSlots} places left`;
//                 alert(data.message);
//             } else {
//                 availableSlotsText.textContent = "No places left";
//                 buttonElement.disabled = true; // Disable the button
//                 alert("You have successfully reserved the last slot!");
//             }
//         } else {
//             alert(data.error || "Failed to reserve the slot.");
//         }
//     } catch (error) {
//         console.error("Error reserving slot:", error);
//         alert("An error occurred while reserving the slot.");
//     }
// }



    listTimeSlots();
</script>

    </body>
    </html>
    <?php
} else {
    echo "<h1>Invalid URL</h1>";
}
?>
