<?php

header('Content-Type: application/json');
header("Access-Control-Expose-Headers: Access-Control-Allow-Origin");
header("Access-Control-Allow-Origin: http://192.168.43.109:3000"); // Replace with your frontend URL
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header('Access-Control-Allow-Credentials: true');

session_start();

$jsonData = json_decode(file_get_contents('php://input'), true);

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



// Retrieve student details sent from the frontend
$fname = $jsonData['firstName'];
$lname = $jsonData['lastName'];
$registrationNumber = $jsonData['registrationNumber'];
$cardNumber = $jsonData['cardNo']; // Exclude this from the students table
$year = $jsonData['year'];
$college = $jsonData['college'];
$gender = $jsonData['gender'];

// Validate the gender value and set it accordingly
if ($gender === "female" || $gender === "male") {
    $genderValue = $gender;
} else {
    $genderValue = null; // If the gender value is neither "female" nor "male", set it to null or any other appropriate default value
}

$programme = $jsonData['programme'];
$semester1Paid = $jsonData['semester1paid'] ? 1 : 0;
$semester2Paid = $jsonData['semester2paid'] ? 1 : 0; // Convert boolean value to 1 or 0 
$dir = "profilePicture/" . $registrationNumber;
$admin_id = $_SESSION['user_id'];

// Prepare the SQL statement to insert student details into the students table
$query_students = "INSERT INTO student (reg_no, Programme, Gender, sl_name, sf_name, college, Year_of_study, sem1_pay, sem2_pay, admin_id, img_dir) VALUES ('$registrationNumber', '$programme','$genderValue', '$lname', '$fname', '$college', '$year', '$semester1Paid', '$semester2Paid', '$admin_id', '$dir')";

// Execute the statement to insert student details into the students table
if (!$conn->query($query_students)) {
    $response = array('status' => 500, 'message' => 'Failed to execute statement: ' . $conn->error);
    echo json_encode($response);
    exit();
}

// Get the student ID of the newly inserted record
$studentId = $conn->insert_id;

// Prepare the SQL statement to insert card details into the card table
$query_card = "INSERT INTO Student_rfid_id (rfid_id, reg_no) VALUES ('$cardNumber', '$registrationNumber')";

// Execute the statement to insert card details into the student rfid table
if (!$conn->query($query_card)) {
    $response = array('status' => 500, 'message' => 'Failed to execute statement: ' . $conn->error);
    echo json_encode($response);
    exit();
}

// Close the database connection
$conn->close();

// Return a success response to the frontend
$response = array('status' => 200, 'message' => 'Student registered successfully');
echo json_encode($response);
