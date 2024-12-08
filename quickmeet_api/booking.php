<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/html; charset=utf-8');

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
        echo "<h1>" . htmlspecialchars($booking['bookingtitle']) . "</h1>";
        echo "<p>" . htmlspecialchars($booking['bookingdescription']) . "</p>";
        echo "<p>Start: " . htmlspecialchars($booking['startdatetime']) . "</p>";
        echo "<p>End: " . htmlspecialchars($booking['enddatetime']) . "</p>";
        // Add logic to display timeslot options and handle reservations
    } else {
        echo "<h1>Booking not found</h1>";
    } ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Booking Details</title>
    </head>
    <body>
    <ul id="user-list"></ul>
    <script>
    console.log('./apiendpoints.php/timeslot/<?php echo $bookingUrl ?>/bookingurl');
    
    fetch('./apiendpoints.php/timeslot/<?php echo $bookingUrl ?>/bookingurl', { method: 'GET' })
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
                        li.textContent = `${slot.slottile} - ${slot.location} - ${slot.hostname}`;
                        slotList.appendChild(li);
                    });
                })
                .catch(error => console.error('Error fetching users:', error));
    </script>
    </body>
    </html>
    <?php
} else {
    echo "<h1>Invalid URL</h1>";
}
?>
