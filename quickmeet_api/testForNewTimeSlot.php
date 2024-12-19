<?php
function makeRequest($method, $url, $data = null) {
    $ch = curl_init();

    $options = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json']
    ];

    if ($method === 'POST' && $data) {
        $options[CURLOPT_POSTFIELDS] = json_encode($data);
    }

    curl_setopt_array($ch, $options);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [
        'status' => $httpCode,
        'response' => json_decode($response, true)
    ];
}

// Test Variables
$baseUrl = "http://localhost/quickmeet/quickmeet_api/apiendpoints.php";

// Test GET Request: numopenslots for a specific timeslot
$sid = 11;
$getNumOpenSlotsUrl = "$baseUrl/timeslot/$sid/numopenslots";
echo "GET numopenslots Test:\n";
$response = makeRequest('GET', $getNumOpenSlotsUrl);
print_r($response);
// ans: 50



// Test GET Request: maxslots for a specific timeslot
$getMaxSlotsUrl = "$baseUrl/timeslot/$sid/maxslots";
echo "\nGET maxslots Test:\n";
$response = makeRequest('GET', $getMaxSlotsUrl);
print_r($response);
// ans: 50



// Test POST Request: Increment numopenslots
// $incrementData = [
//     'sid' => $sid,
//     'field' => 'numopenslots'
// ];
// $incrementUrl = "$baseUrl/timeslot/increment";
// echo "\nPOST Increment numopenslots Test:\n";
// $response = makeRequest('POST', $incrementUrl, $incrementData);
// print_r($response);
// ans: success



// Test POST Request: Decrement numopenslots
$decrementData = [
    'sid' => $sid,
    'field' => 'numopenslots'
];
$decrementUrl = "$baseUrl/timeslot/decrement";
echo "\nPOST Decrement numopenslots Test:\n";
$response = makeRequest('POST', $decrementUrl, $decrementData);
print_r($response);
//sucess



// Test POST Request: Increment maxslots
$incrementMaxSlotsData = [
    'sid' => $sid,
    'field' => 'maxslots'
];
$incrementMaxSlotsUrl = "$baseUrl/timeslot/increment";
echo "\nPOST Increment maxslots Test:\n";
$response = makeRequest('POST', $incrementMaxSlotsUrl, $incrementMaxSlotsData);
print_r($response);
//success



// Test POST Request: Decrement maxslots
// $decrementMaxSlotsData = [
//     'sid' => $sid,
//     'field' => 'maxslots'
// ];
// $decrementMaxSlotsUrl = "$baseUrl/timeslot/decrement";
// echo "\nPOST Decrement maxslots Test:\n";
// $response = makeRequest('POST', $decrementMaxSlotsUrl, $decrementMaxSlotsData);
// print_r($response);
