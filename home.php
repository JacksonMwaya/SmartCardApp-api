<?php
session_start(); // Start the session

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

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) { 

    $response = array('status' => 'error', 'message' => 'User not logged in');
    echo json_encode($response);
    exit();
}  
// Check if user is an admin
if ($_SESSION['role'] !== 'admin') {
    $response = array('status' => 'error', 'message' => 'Unauthorized access');
    echo json_encode($response);
    exit();
}


// Retrieve the user details
$lecturer_id = $_SESSION['user_id'];
$sql_user = "SELECT ll_name,lf_name, lecturer_email,department, lecturer_id FROM Lecturer WHERE lecturer_id = $lecturer_id";
$result_user = $conn->query($sql_user);

// Prepare the user data as an associative array
$user = array();
if ($result_user->num_rows > 0) {
    $row_user = $result_user->fetch_assoc(); 
    $user['id'] = $row_user['lecturer_id'];
    $user['firstname'] = $row_user['lf_name'];
    $user['lastname'] = $row_user['ll_name'];
    $user['email'] = $row_user['lecturer_email']; 
    $user['department'] = $row_user['department']; 
    // Add more user details if needed
}

// Query the database to get the summary data
$sql_students = "SELECT COUNT(*) as total_students FROM Student";
$sql_lecturers = "SELECT COUNT(*) as total_lecturers FROM Lecturer";

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
    $summary['total_lecturers'] = $row_teachers['total_teachers'];
}

// Close the database connection
$conn->close();

// Prepare the response data
$response = array(
    'user' => $user,
    'summary' => $summary
);

// Send the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
