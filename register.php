<?php

header('Content-Type: application/json');
header("Access-Control-Expose-Headers: Access-Control-Allow-Origin");
header("Access-Control-Allow-Origin: http://localhost:3000"); // Replace with your frontend URL
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Retrieve data sent from the frontend
$data = json_decode(file_get_contents('php://input'), true);

// Extract the data from the request
$lecturer_id = $data['lecturer_id'];
$password = $data['password'];
$fname = $data['fName'];
$lname = $data['lName'];
$email = $data['email'];
$phoneNo = $data['phoneNo'];
$deptCode = $data['deptCode'];

// Your database connection details
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "smartcard_db";

// Create a database connection
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Check connection
if ($conn->connect_error) {
    $response = array('status' => 404, 'message' => 'Database connection error');
    echo json_encode($response);
    exit();
}

// Sanitize the lecturer ID before using it in the SQL query
$lecturer_id = $conn->real_escape_string($lecturer_id);

// Check if the lecturer ID is already present in the database
$sql = "SELECT lecturer_id FROM lecturer WHERE lecturer_id = $lecturer_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $response = array('status' => 400, 'message' => 'Lecturer ID already exists');
    echo json_encode($response);
    exit();
}

// Insert the new lecturer into the database
$sql = "INSERT INTO lecturer (lecturer_id, passwd, department, phone_no, lecturer_email, ll_name, lf_name) VALUES ('$lecturer_id', '$password', '$deptCode', '$phoneNo', '$email', '$lname', '$fname')";
if ($conn->query($sql) === TRUE) {
    $response = array('status' => 200, 'message' => 'Registration successful');
    echo json_encode($response);
} else {
    $response = array('status' => 400, 'message' => 'Registration failed');
    echo json_encode($response);
}

// Close the database connection
$conn->close();
