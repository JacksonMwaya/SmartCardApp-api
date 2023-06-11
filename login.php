<?php

session_save_path(__DIR__ . '/sessions');
ini_set('session.gc_maxlifetime', 3600);
session_start();

header('Content-Type: application/json');
header("Access-Control-Expose-Headers: Access-Control-Allow-Origin");
header("Access-Control-Allow-Origin: http://localhost:3000"); // Replace with your frontend URL
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");


// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    // User is already logged in, return success response
    $response = array('status' => 201, 'message' => 'User is already logged in');
    echo json_encode($response);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

// Check if the login request is submitted

// Retrieve username and password from the request
$username = $data['lecturer_id'];
$password = $data['password'];

// Your database connection details
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "smartcard_db";

// Create a database connection
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Check connection
if ($conn->connect_error) {
    // Connection error, return error response
    $response = array('status' => 400, 'message' => 'Database connection error');
    echo json_encode($response);
    exit();
}

// Prepare the SQL statement
$stmt = $conn->prepare("SELECT lecturer_id FROM lecturers WHERE lecturer_id = ? AND password = ? ");
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

// Check if the login is successful
if ($result->num_rows === 1) {
    // Successful login

    // Store user information in session
    $_SESSION['user_id'] = $username;

    // set role is an admin
    if ($_SESSION['user_id'] === '20100000000') {
        $_SESSION['role'] = 'admin';
    }

    // Return success response
    $response = array('status' => 200, 'message' => 'Login successful');
    echo json_encode($response);
} else {
    // Invalid credentials
    $response = array('status' => 401, 'message' => 'Invalid username or password');
    echo json_encode($response);
    exit();
}

// Close the database connection
$stmt->close();
$conn->close();
