<?php
header('Content-Type: application/json');
header("Access-Control-Expose-Headers: Access-Control-Allow-Origin");
header("Access-Control-Allow-Origin: http://192.168.43.109:3000"); // Replace with your hardware URL
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header('Access-Control-Allow-Credentials: true');

session_start();

$deviceOptiondata = json_decode(file_get_contents('php://input'), true);

// Check if 'deviceOption' key exists in $jsonData
if (isset($deviceOptiondata['deviceOption'])) {
    $deviceOption = $deviceOptiondata["deviceOption"];


    // Save device option
    $_SESSION["deviceOption"] = $deviceOption;

    $response = array(
        'status' => 200,
        'message' => 'Option stored',
    );

    echo json_encode($response);
} else {
    // Handle case when 'deviceOption' key is missing
    $response = array(
        'status' => 400,
        'message' => 'Missing device option'
    );

    echo json_encode($response);
}
