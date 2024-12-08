<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

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
        } else{
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
    } else if ($resource == 'reservation' && $param != "") {
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
        $stmt->bind_param("i", $param);
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
    } else {
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
            } else {
                echo json_encode(array("error" => "Error: " . $sql . "<br>" . $conn->error));
            }
        }
        else {
            $url = uniqid('booking_', true);
            $uid = $input['uid'];
            $start = $input['startdatetime'];
            $end = $input['enddatetime'];
            $title = $input['bookingtitle'];
            $description = $input['bookingdescription'];

            $sql = "INSERT INTO Booking (bookingurl, uid, startdatetime, enddatetime, bookingtitle, bookingdescription)
            VALUES ('$url', '$uid', '$start', '$end', '$title', '$description');";
            
            if ($conn->query($sql)) {
                $booking_id = $conn->insert_id;

                if($booking_id){
                    echo json_encode(array(
                        "message" => "The booking was created successfully",
                        "booking_id" => $booking_id,
                        "booking_url" => "http://localhost/quickmeet/quickmeet_api/booking.php?url=" . urlencode($url)
                    ));
                } else {
                    echo json_encode(array(
                        "error" => "Error: Unable to retrieve booking ID after update."
                    ));
                }
            } else {
                echo json_encode(array("error" => "Error: " . $sql . "<br>" . $conn->error));
            }
        }
    } else if ($resource == 'timeslot'){
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
            $numslots = $input['numopenslots'];
            $maxslots = $input['maxslots'];
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