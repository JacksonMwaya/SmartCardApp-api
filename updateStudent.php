<?php
session_start();

header('Content-Type: application/json');
header("Access-Control-Expose-Headers: Access-Control-Allow-Origin");
header("Access-Control-Allow-Origin: http://localhost:3000"); // Replace with your frontend URL
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type");


// Retrieve semester 2 payment status from the request body
$data = json_decode(file_get_contents('php://input'), true);
$registrationNumber = $data['registrationNumber'];
$semester2Paid = $data['semester2paid'] ? 1 : 0; // Convert boolean value to 1 or 0

// Connect to your database
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "smartcard_db";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); 
    exit();
}

// Prepare the SQL statement to update semester2_paid for the given registration number
$sql = "UPDATE student SET sem2_pay = {$semester2Paid} WHERE reg_no = {$registrationNumber}";

// Execute the SQL statement
if ($conn->query($sql) === true) {
    $response = array('status' => 200, 'message' => 'Semester 2 payment status updated successfully');
} else {
    $response = array('status' => 404, 'message' => 'Registration number not found');
}

// Close the database connection
$conn->close();

// Return the response to the frontend
echo json_encode($response);
