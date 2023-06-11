<?php

session_start(); // Start the session 

header('Content-Type: application/json');
header("Access-Control-Expose-Headers: Access-Control-Allow-Origin");
header("Access-Control-Allow-Origin: http://localhost:3000"); // Replace with your frontend URL
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Connect to your database
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "smartcard_db";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$_SESSION['user_id'] = '20100000000';

// Retrieve the user details
$lecturer_id = $_SESSION['user_id'];
$sql_user = "SELECT ll_name,lf_name, lecturer_email,department, lecturer_id FROM lecturer WHERE lecturer_id = $lecturer_id";
$result_user = $conn->query($sql_user);

// Prepare the user data as an associative array
$user = array();
if ($result_user->num_rows > 0) {
    $row_user = $result_user->fetch_assoc(); 
    $user['Lecturer ID'] = $row_user['lecturer_id'];
    $user['First Name'] = $row_user['lf_name'];
    $user['Last Name'] = $row_user['ll_name'];
    $user['Email'] = $row_user['lecturer_email']; 
    $user['Department'] = $row_user['department']; 
    // Add more user details if needed
}

// Query the database to get the summary data
$sql_students = "SELECT COUNT(*) as total_students FROM student";
$sql_lecturers = "SELECT COUNT(*) as total_lecturers FROM lecturer";

$result_students = $conn->query($sql_students);
$result_teachers = $conn->query($sql_lecturers);

// Prepare the summary data as an associative array
$summary = array();
if ($result_students->num_rows > 0) {
    $row_students = $result_students->fetch_assoc();
    $summary['total_students'] = $row_students['total_students'];
}

if ($result_teachers->num_rows > 0) {
    $row_teachers = $result_teachers->fetch_assoc();
    $summary['total_lecturers'] = $row_teachers['total_lecturers'];
}

// Close the database connection
$conn->close();

// Prepare the response data
$response = array(
    'user' => $user,
    'summary' => $summary
);

echo json_encode($response);
