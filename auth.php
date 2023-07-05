<?php


header('Content-Type: application/json');
header("Access-Control-Expose-Headers: Access-Control-Allow-Origin");
header("Access-Control-Allow-Origin: http://192.168.43.109:3000"); // Replace with your frontend URL
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
header('Access-Control-Allow-Credentials: true');

session_start();


if (!isset($_SESSION['user_id'])) {
    $response = array('status' => 401, 'message' => 'Not logged in');
    echo json_encode($response);
    exit();
}

if ($_SESSION['role'] === 'Lecturer') {
    $response = array('status' => 404, 'message' => 'Unauthorized access');
    echo json_encode($response);
    exit();
}

$response = array('status' => 200, 'message' => 'User is logged in');
echo json_encode($response);
