<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/html; charset=utf-8');

require_once '../config/config.php';

// create a database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$resUrl = $_GET['url'] ?? null;

if ($resUrl) {
    // Fetch the res details
    $sql = "SELECT * FROM Reservation WHERE reservationurl = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $resUrl);
    $stmt->execute();
    $result1 = $stmt->get_result();
    if ($result1 && $result1->num_rows > 0) {
        $res = $result1->fetch_assoc();
        // Get associated timeslot details
        $sql = "SELECT * FROM Timeslot WHERE sid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $res['sid']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            $restimeslot = $result->fetch_assoc();
            echo "<h3>Meeting Details: " . htmlspecialchars($restimeslot['slottitle']) . "</h3>";
            echo "<h3>Hostname: " . htmlspecialchars($restimeslot['hostname']) . "</h3>";
            echo "<h3>Location: " . htmlspecialchars($restimeslot['location']) . "</h3>";
            echo "<h4>Start Time: " . htmlspecialchars($restimeslot['startdatetime']) . "</h4>";
            echo "<h4>End Time: " . htmlspecialchars($restimeslot['enddatetime']) . "</h4>";
            echo "<h4>Notes: " . htmlspecialchars($res['notes']) . "</h4>";
            echo "<h4>Original Booking URL: " . htmlspecialchars("http://localhost/quickmeet/quickmeet_api/bookingurl.php?url=" . urlencode($restimeslot['bookingurl'])) . "</h4>";
        }
        else {
            echo "<h3> Sorry, the timeslot that was created was deleted by the owner and no longer found </h3>";
        }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>res Details</title>
    </head>
    <body>
    <button onclick="editNotes()">Edit Notes</button>
    <input type="submit" value="Cancel This Meeting" onclick="delRes(event)" />

    <p id="demo"></p>

    <script>
        function delRes(e) {
            if(!confirm('Are you sure?')) {
                e.preventDefault();
            }
            else {
                console.log('./apiendpoints.php/reservation/<?php echo $resUrl ?>');
                fetch('./apiendpoints.php/reservation/<?php echo $resUrl ?>', { method: 'DELETE' })
                    .then(response => {
                            // Check if the response is successful
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            // Attempt to parse JSON
                            window.location.href = '../FrontEndCode/Landing.html';
                        })
            }
        }
            
        function editNotes(){

        }
    </script>
    </body>
    </html>
    <?php
    } else {
        echo "<h1>This reservation was not found</h1>";
    } 
} else {
    echo "<h1>Invalid URL</h1>";
}
?>
