<?php
// Retrieve data sent from the frontend
$data = json_decode(file_get_contents('php://input'), true);


// Extract the data from the request
$lecturer_id = $data['lecturer_id'];
$password = $data['password'];
$fname = $data['fname']; 
$lname = $data['lname']; 
$email = $data['email']; 
$phoneNo = $data['phoneNo']; 
$deptCode = $data['deptCode']; 


// Your database connection details
$servername = "localhost";
$username_db = "root";
$password_db = " ";
$dbname = "smartcard_db";

// Create a database connection
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Check connection
if ($conn->connect_error) {
    $response = array('status' => 'error', 'message' => 'Database connection error');
    echo json_encode($response);
    exit();
}

// Check if the lecturer ID is already present in the database
$stmt = $conn->prepare("SELECT lecturer_id FROM lecturers WHERE lecturer_id = ?");
$stmt->bind_param("s", $lecturer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $response = array('status' => 'error', 'message' => 'Lecturer ID already exists');
    echo json_encode($response);
    exit();
}

// Insert the new lecturer into the database
$stmt = $conn->prepare("INSERT INTO lecturers (lecturer_id, passwd, department, phone_no, lecturer_email, ll_name, lf_name) VALUES (?, ?, ?,?, ?, ?, ?)");
$stmt->bind_param("sssssss", $lecturer_id, $password, $deptCode, $phoneNo, $email, $lname ,$fname);
$stmt->execute();

// Check if the insertion was successful
if ($stmt->affected_rows === 1) {
    $response = array('status' => 'success', 'message' => 'Registration successful');
    echo json_encode($response);
} else {
    $response = array('status' => 'error', 'message' => 'Registration failed');
    echo json_encode($response);
}

// Close the database connection
$stmt->close();
$conn->close();
