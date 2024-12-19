<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

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

// read the api endpoint
$request = $_SERVER['REQUEST_URI'];
$uriSegments = explode('/', $request);

// check endpoint
if (isset($uriSegments[4])) {
    $resource = $uriSegments[4]; // 'users', 'booking', 'timeslot', 'reservations' or 'availability'
    $param ="";
    $paramname = "";
    if(isset($uriSegments[5])){
        $param = $uriSegments[5];
    }
    if(isset($uriSegments[6])){
        $paramname = $uriSegments[6];
    } else {
        $paramname = "default";
    }
} else {
    $resource = '';
}

// Handle GET request (fetch data from different tables)
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if ($resource == 'users') {
        $sql = "SELECT * FROM usernamepasstut WHERE age=33"; // Ensure SQL syntax is correct
        $result = $conn->query($sql);
        $users = array();

        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
            echo json_encode($users);
        } else {
            echo json_encode(array("error" => "Invalid resource"));
        }
    } else if ($resource == 'booking' && $param != "") {
        if ($paramname == 'bookingid' || $paramname == 'default') {
            $sql = "SELECT * FROM Booking WHERE bid = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $param);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $bookingres = $result->fetch_assoc();
                echo json_encode($bookingres);
            } else {
                echo json_encode(array("error" => "A booking with this booking id was not found"));
            }
        }
        else if ($paramname == 'userid') {
            $sql = "SELECT * FROM Booking WHERE uid = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $param);
            $stmt->execute();
            $result = $stmt->get_result();
            $bookings = array();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $bookings[] = $row;
                }
                echo json_encode($bookings);
            } else {
                echo json_encode(array("error" => "A booking with this user id was not found"));
            }
        }
        else if ($paramname == 'bookingurl') {
            $sql = "SELECT * FROM Booking WHERE bookingurl = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $param);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $bookingres = $result->fetch_assoc();
                echo json_encode($bookingres);
            } else {
                echo json_encode(array("error" => "A booking with this booking url was not found"));
            }
        }
    } else if ($resource == 'timeslot' && $param != "") {
        if ($paramname == 'bookingurl') {
            $sql = "SELECT * FROM Timeslot WHERE bookingurl = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $param);
            $stmt->execute();
            $result = $stmt->get_result();
            $slots = array();

            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    $slots[] = $row;
                }
                echo json_encode($slots);
            } else {
                echo json_encode(array("error" => "A timeslot with this booking url was not found"));
            }
        } 
        ////new code for num of slots in time slots here
        // if ($paramname == 'numopenslots') {
        //     $sql = "SELECT numopenslots FROM Timeslot WHERE sid = ?";
        //     $stmt = $conn->prepare($sql);
        //     $stmt->bind_param("i", $param);
        //     $stmt->execute();
        //     $result = $stmt->get_result();
        //     if ($result->num_rows > 0) {
        //         $numopenslots = $result->fetch_assoc();
        //         echo json_encode($numopenslots);
        //     } else {
        //         echo json_encode(array("error" => "A timeslot with this sid was not found"));
        //     }
        // } else if ($paramname == 'maxslots') {
        //     $sql = "SELECT maxslots FROM Timeslot WHERE sid = ?";
        //     $stmt = $conn->prepare($sql);
        //     $stmt->bind_param("i", $param);
        //     $stmt->execute();
        //     $result = $stmt->get_result();
        //     if ($result->num_rows > 0) {
        //         $maxslots = $result->fetch_assoc();
        //         echo json_encode($maxslots);
        //     } else {
        //         echo json_encode(array("error" => "A timeslot with this sid was not found"));
        //     }
        // }
        ////end of new code for for num of slots in time slots.
        
        else{
            $sql = "SELECT * FROM Timeslot WHERE sid = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $param);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $timeslotres = $result->fetch_assoc();
                echo json_encode($timeslotres);
            } else {
                echo json_encode(array("error" => "A timeslot with this sid was not found"));
            }
        }


    } 
    ///////////////////////////////////////new codeeee
    else if ($resource == 'reservation') {
        if ($param == 'edit') {
            $url = $input['reservationurl'];
            $sid = $input['sid'];
            $notes = $input['notes'];
    
            $sql = "UPDATE Reservation
            SET sid='$sid',
            notes='$notes'
            WHERE reservationurl = '$url';";
    
            if ($conn->query($sql)) {
                echo json_encode(array("message" => "The reservation with this url was updated successfully."));
            } else {
                echo json_encode(array("error" => "Error: " . $sql . "<br>" . $conn->error));
            }
        } else {
            $url = uniqid('reservation_', true);
            $sid = $input['sid'];
            $notes = $input['notes'];
    
            // Check available slots
            $checkSlotSql = "SELECT numopenslots FROM Timeslot WHERE sid = ?";
            $stmt = $conn->prepare($checkSlotSql);
            $stmt->bind_param("i", $sid);
            $stmt->execute();
            $result = $stmt->get_result();
            $slot = $result->fetch_assoc();
            $stmt->close();
    
            if ($slot && $slot['numopenslots'] > 0) {
                // Create the reservation
                $sql = "INSERT INTO Reservation (reservationurl, sid, notes)
                VALUES ('$url', '$sid', '$notes');";
    
                if ($conn->query($sql)) {
                    // Reduce the number of available slots
                    $updateSlotSql = "UPDATE Timeslot SET numopenslots = numopenslots - 1 WHERE sid = ? AND numopenslots > 0";
                    $updateStmt = $conn->prepare($updateSlotSql);
                    $updateStmt->bind_param("i", $sid);
                    $updateStmt->execute();
                    $updateStmt->close();
    
                    echo json_encode(array(
                        "message" => "The reservation was created successfully",
                        "reservation_url" => "http://localhost/quickmeet/quickmeet_api/reservation.php?url=" . urlencode($url)
                    ));
                } else {
                    echo json_encode(array("error" => "Error: " . $sql . "<br>" . $conn->error));
                }
            } else {
                echo json_encode(array("error" => "No available slots for this time slot."));
            }
        }
    }
///////////////////////////// end of new code    
    
    
    
    
    
    
    else if ($resource == 'reservation' && $param != "") {
        $sql = "SELECT * FROM Reservation WHERE reservationurl = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $param);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $reservationres = $result->fetch_assoc();
            echo json_encode($reservationres);
        } else {
            echo json_encode(array("error" => "A reservation with this url was not found"));
        }
    } else if ($resource == 'availability' && $param != "") {
        $sql = "SELECT * FROM AvailabilityRequests WHERE bookingurl = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $param);
        $stmt->execute();
        $result = $stmt->get_result();
        $availabilityres = array();
        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                $availabilityres[] = $row;
            }
            echo json_encode($availabilityres);
        } else {
            echo json_encode(array("error" => "Availability requests with this bookingurl was not found"));
        }
    }
    //////////////////here new code starts
    else if ($resource == 'booking' && $param == "") {
            // Fetch all bookings
            $sql = "SELECT * FROM Booking";
            $result = $conn->query($sql);
            $bookings = array();
        
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $bookings[] = $row;
                }
                echo json_encode($bookings);
            } else {
                echo json_encode(array("error" => "No bookings found"));
            }
        }
    ///////////////////here new code ends
    else {
        echo json_encode(array("error" => "Invalid endpoint"));
    }
}

// Handle POST request 
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $input = json_decode(file_get_contents("php://input"), true);

    if ($resource == 'booking'){
        if ($param == 'edit'){
            $url = $input['bookingurl'];
            $start = $input['startdatetime'];
            $end = $input['enddatetime'];
            $title = $input['bookingtitle'];
            $description = $input['bookingdescription'];
            $sql = "UPDATE Booking
            SET startdatetime='$start',
            enddatetime='$end',
            bookingtitle='$title',
            bookingdescription='$description',
            WHERE bookingurl = '$url';";

            if ($conn->query($sql)) {
                echo json_encode(array("message" => "The booking with this url was updated successfully."));
                error_log("Booking created successfully with ID: $booking_id");
            } else {
                echo json_encode(array("error" => "Error: " . $sql . "<br>" . $conn->error));
            }
        }
        else {
            $url = uniqid('booking_', true);
            $editurl = uniqid('editbooking_', true);
            $uid = $input['uid'];
            $start = $input['startdatetime'];
            $end = $input['enddatetime'];
            $title = $input['bookingtitle'];
            $description = $input['bookingdescription'];

            $sql = "INSERT INTO Booking (bookingurl, editbookingurl, uid, startdatetime, enddatetime, bookingtitle, bookingdescription)
            VALUES ('$url', '$editurl', '$uid', '$start', '$end', '$title', '$description');";
            
            if ($conn->query($sql)) {
                $booking_id = $conn->insert_id;

                if($booking_id){
                    error_log("User ID passed: $uid");
                    $userEmailQuery = "SELECT email FROM users WHERE id = ?";
                    $stmt = $conn->prepare($userEmailQuery);
                    if (!$stmt) {
                        error_log("Failed to prepare email query: " . $conn->error);
                        echo json_encode(array("error" => "Internal Server Error"));
                        exit;
                    }
                    error_log("Fetching email for UID: $uid");
                    $stmt->bind_param("i", $uid);
                    $stmt->execute();
                    $result = $stmt->get_result(); 

                    if ($result->num_rows > 0) {
                        $user = $result->fetch_assoc();
                        $toEmail = $user['email'];
                        //error_log("Email fetched for user ID $id: $toEmail");

                        require __DIR__ . '/../backend/email_helper.php';

                        $subject = "Booking Confirmation";
                        $body = "<h1>Booking Created Successfully</h1>
                                <p>Your booking with the following details has been created:</p>
                                 <ul>
                                    <li><strong>Booking Title:</strong> $title</li>
                                    <li><strong>Start Date & Time:</strong> $start</li>
                                    <li><strong>End Date & Time:</strong> $end</li>
                                    <li><strong>Description:</strong> $description</li>
                                </ul>
                                <p>You can manage your booking using the following links:</p>
                                <p>Booking URL: <a href='http://localhost/quickmeet/quickmeet_api/bookingurl.php?url=" . urlencode($url) . "'>View Booking</a></p>
                                <p>Edit URL: <a href='http://localhost/quickmeet/quickmeet_api/editbookingurl.php?url=" . urlencode($editurl) . "'>Edit Booking</a></p>";


                                if (sendEmail($toEmail, $subject, $body)) {
                                    error_log("Email sent successfully to $toEmail");
                                    echo json_encode(array(
                                        "message" => "The booking was created successfully, and a confirmation email was sent.",
                                        "booking_id" => $booking_id,
                                        "booking_url" => "http://localhost/quickmeet/quickmeet_api/bookingurl.php?url=" . urlencode($url)
                                    ));
                                }
                                else{ 
                                    error_log("Failed to send email to $toEmail");
                                    echo json_encode(array(
                                    "message" => "The booking was created successfully, but the confirmation email failed to send.",
                                    "booking_id" => $booking_id,
                                    "booking_url" => "http://localhost/quickmeet/quickmeet_api/bookingurl.php?url=" . urlencode($url)
                                ));
                            }
                        } else {
                            error_log("No email found for user ID $id");
                            echo json_encode(array(
                                "message" => "The booking was created, but user email could not be found.",
                                "booking_id" => $booking_id,
                                "booking_url" => "http://localhost/quickmeet/quickmeet_api/bookingurl.php?url=" . urlencode($url)
                            ));
                        }
                    } else {
                        error_log("Booking creation failed: " . $conn->error);
                        echo json_encode(array(
                            "error" => "Error: Unable to retrieve booking ID after insert."
                        ));
                    }
                } else {
                    echo json_encode(array("error" => "Error: " . $sql . "<br>" . $conn->error));
                }
            }
    } else if ($resource == 'timeslot'){
        // new code for time slot maxslots and other handling
       
        // end of new code for time slot maxslots and other handling.
        
        if ($param == 'edit'){
            $sid = $input['sid'];
            $title = $input['slottitle'];
            $host = $input['hostname'];
            $location = $input['location'];
            $start = $input['startdatetime'];
            $end = $input['enddatetime'];
            $numslots = $input['numopenslots'];
            $maxslots = $input['maxslots'];

            $sql = "UPDATE Timeslot
            SET slottitle='$title',
            hostname='$host',
            location='$location',
            startdatetime='$start',
            enddatetime='$end',
            numopenslots='$numslots',
            maxslots='$maxslots',
            WHERE sid = '$sid';";

            if ($conn->query($sql)) {
                echo json_encode(array("message" => "The timeslot with this sid " . $sid . " was updated successfully."));
            } else {
                echo json_encode(array("error" => "Error: " . $sql . "<br>" . $conn->error));
            }
        }
        else {
            $url = $input['bookingurl'];;
            $title = $input['slottitle'];
            $host = $input['hostname'];
            $location = $input['location'];
            $start = $input['startdatetime'];
            $end = $input['enddatetime'];
            // $numslots = $input['numopenslots'];
            $maxslots = $input['maxslots'];
            $numslots = $maxslots;
            $sql = "INSERT INTO Timeslot (bookingurl, slottitle, hostname, location, startdatetime, enddatetime, numopenslots, maxslots)
            VALUES ('$url', '$title', '$host', '$location', '$start', '$end', '$numslots', '$maxslots');";
            
            if ($conn->query($sql)) {
                $sid = $conn->insert_id;

                if($sid){
                    echo json_encode(array(
                    "message" => "The timeslot was created successfully",
                    "timeslot_id" => $sid
                ));
                } else {
                    echo json_encode(array(
                        "error" => "Error: Unable to retrieve timeslot ID after update."
                    ));
                }
            } else {
                echo json_encode(array("error" => "Error: " . $sql . "<br>" . $conn->error));
            }
        }
    } else if ($resource == 'reservation'){
        if ($param == 'edit'){
            $url = $input['reservationurl'];
            $sid = $input['sid'];
            $notes = $input['notes'];

            $sql = "UPDATE Reservation
            SET sid='$sid',
            notes='$notes',
            WHERE reservationurl = '$url';";

            if ($conn->query($sql)) {
                echo json_encode(array("message" => "The reservation with this url was updated successfully."));
            } else {
                echo json_encode(array("error" => "Error: " . $sql . "<br>" . $conn->error));
            }
        }
        else {
            $url = uniqid('reservation_', true);
            $sid = $input['sid'];
            $notes = $input['notes'];

            $sql = "INSERT INTO Reservation (reservationurl, sid, notes)
            VALUES ('$url', '$sid', '$notes');";
            
            if ($conn->query($sql)) {
                echo json_encode(array(
                    "message" => "The reservation was created successfully",
                    "reservation_url" => "http://localhost/quickmeet/quickmeet_api/reservation.php?url=" . urlencode($url)
                ));
            } else {
                echo json_encode(array("error" => "Error: " . $sql . "<br>" . $conn->error));
            }
        }
    } else if ($resource == 'availability'){
        $url = $input['bookingurl'];
        $start = $input['startdatetime'];
        $end = $input['enddatetime'];
        $sql = "INSERT INTO AvailabilityRequests (bookingurl, startdatetime, enddatetime)
        VALUES ('$url', '$start', '$end');";
        
        if ($conn->query($sql)) {
            $rid = $conn->insert_id;

            if($rid){
                echo json_encode(array(
                "message" => "The availability request was created successfully",
                "request_id" => $rid
            ));
            } else {
                echo json_encode(array(
                    "error" => "Error: Unable to retrieve availability request id after insert."
                ));
            }
        } else {
            echo json_encode(array("error" => "Error: " . $sql . "<br>" . $conn->error));
        }
    } else {
        echo json_encode(array("error" => "Invalid endpoint"));
    }
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] == 'DELETE'){
    if ($resource == 'booking' && $param != "") {
        $sql = "DELETE FROM Booking WHERE bid = '$param'";
        if ($conn->query($sql)) {
            echo json_encode(array("message" => "Booking with bid " . $param . " deleted successfully"));
        } else {
            echo json_encode(array("error" => "Error: " . $sql . "<br>" . $conn->error));
        }
    } else if ($resource == 'timeslot' && $param != "") {
        $sql = "DELETE FROM Timeslot WHERE sid = '$param'";
        if ($conn->query($sql)) {
            echo json_encode(array("message" => "Timeslot with sid " . $param . " deleted successfully"));
        } else {
            echo json_encode(array("error" => "Error: " . $sql . "<br>" . $conn->error));
        }
    } else if ($resource == 'reservation' && $param != "") {
        $sql = "DELETE FROM Reservation WHERE reservationurl = '$param'";
        if ($conn->query($sql)) {
            echo json_encode(array("message" => "Reservation with url " . $param . " deleted successfully"));
        } else {
            echo json_encode(array("error" => "Error: " . $sql . "<br>" . $conn->error));
        }
    } else if ($resource == 'availability' && $param != "") {
        $sql = "DELETE FROM AvailabilityRequests WHERE rid = '$param'";
        if ($conn->query($sql)) {
            echo json_encode(array("message" => "Availability request with rid " . $param . " deleted successfully"));
        } else {
            echo json_encode(array("error" => "Error: " . $sql . "<br>" . $conn->error));
        }
    } else {
        echo json_encode(array("error" => "Invalid endpoint"));
    }
}

$conn->close();
?>