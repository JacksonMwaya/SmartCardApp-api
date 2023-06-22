<?php

header('Content-Type: application/json');
header("Access-Control-Expose-Headers: Access-Control-Allow-Origin");
header("Access-Control-Allow-Origin: http://localhost:3000"); // Replace with your hardware URL
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header('Access-Control-Allow-Credentials: true'); 

session_start();

$jsonData = json_decode(file_get_contents('php://input'), true);

// Receive device option from the frontend

$deviceOption= $jsonData['deviceOption']; 
$sessionId = session_id();

// Save device option
$_SESSION['deviceOption'] = $deviceOption; 

$response = array(
    'status' => 200,
    'message' => 'Stored in session',
    'sessionId' => $sessionId
); 

echo json_encode($response); 

