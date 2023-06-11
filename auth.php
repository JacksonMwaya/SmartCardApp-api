<?php

session_start();

header('Content-Type: application/json');
header("Access-Control-Expose-Headers: Access-Control-Allow-Origin");
header("Access-Control-Allow-Origin: http://localhost:3000"); // Replace with your frontend URL
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");


$_SESSION['user_id'] = '20100000000';
$_SESSION['role'] = 'admin';

// Check if user is already logged in
if (!isset($_SESSION['user_id'])) {
    $response = array('status' => 401, 'message' => 'Not logged in');
    echo json_encode($response);
    exit();
}

// Check if user is an admin
if ($_SESSION['role'] !== 'admin') {
    $response = array('status' => 404, 'message' => 'Unauthorized access');
    echo json_encode($response);
    exit();
}

$response = array('status' => 200, 'message' => 'User is logged in and Authorized');
echo json_encode($response);
