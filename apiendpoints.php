<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$servername = "localhost";
$username = "root"; // Supply the username you used, if any
$password = ""; // Supply the password you used, if any
$dbname = "mysql"; // You must supply the database name the table is within

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname); // Add a space between 'new' and 'mysqli'

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the API endpoint (e.g., /api.php/users, /api.php/orders)
$request = $_SERVER['REQUEST_URI'];
$uriSegments = explode('/', $request);

// Check for the endpoint and handle accordingly
if (isset($uriSegments[3])) {
    $resource = $uriSegments[3]; // 'users', 'orders', or 'products'
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
    }
} else {
    echo json_encode(array("error" => "Invalid endpoint"));
}

$conn->close();
?>