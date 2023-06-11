<?php
session_start();

header('Content-Type: application/json');
header("Access-Control-Expose-Headers: Access-Control-Allow-Origin");
header("Access-Control-Allow-Origin: http://localhost:3000"); // Replace with your frontend URL
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Destroy the session
session_destroy();

// Return a success response
$response = array('status' => 'success', 'message' => 'Logout successful');
echo json_encode($response);
