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
            // echo "<h3>Meeting Details: " . htmlspecialchars($restimeslot['slottitle']) . "</h3>";
            // echo "<h3>Hostname: " . htmlspecialchars($restimeslot['hostname']) . "</h3>";
            // echo "<h3>Location: " . htmlspecialchars($restimeslot['location']) . "</h3>";
            // echo "<h4>Start Time: " . htmlspecialchars($restimeslot['startdatetime']) . "</h4>";
            // echo "<h4>End Time: " . htmlspecialchars($restimeslot['enddatetime']) . "</h4>";
            // echo "<h4>Notes: " . htmlspecialchars($res['notes']) . "</h4>";
            // echo "<h4>Original Booking URL: " . htmlspecialchars("http://localhost/quickmeet/quickmeet_api/bookingurl.php?url=" . urlencode($restimeslot['bookingurl'])) . "</h4>";
            // new html/css
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
                    .TimeSlot-container {
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
                <div class='TimeSlot-container'>

                    <h1>" . "Some info about your reservation!" . "</h1>    
                    <p><span class = 'bolder'>Meeting Details:</span> " . htmlspecialchars($restimeslot['slottitle']) . "</p>
                    <p><span class = 'bolder'>Hostname:</span> " . htmlspecialchars($restimeslot['hostname']) . "</p>
                    <p><span class = 'bolder'>Location:</span> " . htmlspecialchars($restimeslot['location']) . "</p>
                    <p><span class = 'bolder'>Start Time: </span>" . htmlspecialchars($restimeslot['startdatetime']) . "</p>
                    <p><span class = 'bolder'>Start Time: </span>" . htmlspecialchars($restimeslot['enddatetime']) . "</p>
                    <p><span class = 'bolder'>Notes: </span>" . "</p>
                    <p>". htmlspecialchars($res['notes']) ."</p>
                    <p><span class = 'bolder'>Original Booking URL: </span> " . htmlspecialchars("http://localhost/quickmeet/quickmeet_api/bookingurl.php?url=" . urlencode($restimeslot['bookingurl'])) . "</p>
                    
                    
                    
                </div>
            </body>
            </html>"; 

            //End of new html / css
        }
        else {
            echo "<h3> Sorry, the timeslot that was created was deleted by the owner and no longer found </h3>";
        }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>res Details</title>
        <style>
            .buttons{
                text-align: center;
                margin-top: 20px;
            }

            button, input{
                border-radius: 10px;
                width: 210px;
                height: 50px;
                cursor: pointer;
                font-size: 15px;
                font-weight: 600;  
                border: none;  
            }

            .modal-content{
                background-color: #0C3D65;
                border-radius: 8px;
                width: 33%;
                margin-top: 0%;
                margin: auto;
                padding: 80px 20px;
                position: relative;
                top: 50%; /* Push the modal to the vertical center */
                transform: translateY(-50%); /* Center it vertically.. Just tried a bunch of things*/
                z-index: 1001;
                text-align: center;
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

            .saveBut{
                width: 70%;
            }


        </style>
    </head>
    <body>
        <div class = "buttons">
            <button  onclick="editNotes()">Edit Notes</button>
            <input  type="submit" value="Cancel This Meeting" onclick="delRes(event)" />
        </div>

        <div id="editNotesModal" class="modal" 
            style="display: none; 
            text-align: center; 
            width: 100%; 
            margin: auto;">
            <div class="modal-content" style="position: relative;">
                <span class="close" 
                style="color: white; cursor: pointer; position: absolute; top: 5px; right: 10px;" 
                onclick="closeEditNotesModal()">
                    &times;
                </span>
                <form id="editBookingForm">
                    <h3 style="color: white">Edit Reservation Notes</h3>
                    <textarea id="newReservationNotes" style="width: 70%; height: 50%;"></textarea><br><br>
                    <button type='button' class="saveBut" onclick="saveNotes()">Save Changes</button>
                </form>
            </div>
        </div>

    <!-- <p id="demo"></p> -->

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
            
        function editNotes() {
            document.getElementById('editNotesModal').style.display = 'block';
        }

        function closeEditNotesModal() {
            document.getElementById('editNotesModal').style.display = 'none';
        }

        async function saveNotes(){
            const newNotes = document.getElementById('newReservationNotes').value;

            try {
                const response = await fetch('./apiendpoints.php/reservation/edit', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    reservationurl: <?php echo json_encode($resUrl); ?>, 
                    notes: newNotes
                })
                });

                const result = await response.json();

                if (response.ok) {
                    alert('Notes updated successfully!');
                    document.getElementById('editNotesModal').style.display = 'none';
                    location.reload(); 
                } else {
                    alert(result.error || 'Failed to update notes.');
                }
            } 
            catch (error) {
                console.error('Error updating notes:', error);
                alert('An error occurred while updating the notes.');
            }
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
