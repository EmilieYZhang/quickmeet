<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/html; charset=utf-8');

require 'db_connect.php';
$userid=24; // hardcoded for now, should be == userid from the current session ticket
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Details</title>
</head>
<body>

<h1> My Bookings </h1>
<h3> Here are the currently active booking URLs </h3>
<ul id="current-user-list"></ul>

<h3> Here are the past booking URLs </h3>
<ul id="past-user-list"></ul>

<button onclick="createBooking()">Create Booking</button>

<script>
console.log('./apiendpoints.php/booking/<?php echo $userid ?>/userid');

fetch('../quickmeet_api/apiendpoints.php/booking/<?php echo $userid ?>/userid', { method: 'GET' })
        .then(response => {
                // Check if the response is successful
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                // Attempt to parse JSON
                return response.json();
            })
            .then(bookings => {
                const activeBookingList = document.getElementById('current-user-list');
                activeBookingList.innerHTML = '';
                const pastBookingList = document.getElementById('past-user-list');
                pastBookingList.innerHTML = '';
                bookings.forEach(booking => {
                    const li = document.createElement('li');
                    const startDate = new Date(booking.startdatetime.replace(' ', 'T'));
                    const targetDate = new Date(booking.enddatetime.replace(' ', 'T'));
                    // Get the current timestamp
                    const currentDate = new Date();
                    
                    li.textContent = `${booking.bookingtitle} - ${booking.bookingurl} - Start: ${startDate} - End: ${targetDate}`;

                    console.log(targetDate);
                    console.log(currentDate);

                    if (targetDate >= currentDate){
                        console.log("greater");
                        activeBookingList.appendChild(li);
                    }
                    else {
                        console.log("less");
                        pastBookingList.appendChild(li);
                    }
                });
            })
            .catch(error => console.error('Error fetching users:', error));
</script>
<script>
    function createBooking() {
        window.location.href = '../quickmeet_api/createbooking.html';
    }
        
    function editNotes(){

    }
</script>
</body>
</html>
