<?php
session_start();

header('Content-Type: application/json');
header("Access-Control-Expose-Headers: Access-Control-Allow-Origin");
header("Access-Control-Allow-Origin: http://localhost:3000"); // Replace with your frontend URL
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type"); 
header('Access-Control-Allow-Credentials: true');  

session_unset();

// Destroy the session
session_destroy(); 

setcookie(session_name(), '', time() - 3600, '/'); 

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");

// Return a success response
$response = array('status' => 200, 'message' => 'Logout successful');
echo json_encode($response);
