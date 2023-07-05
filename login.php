<?php

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '192.168.43.109',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();

header('Content-Type: application/json');
header("Access-Control-Expose-Headers: Access-Control-Allow-Origin");
header("Access-Control-Allow-Origin: http://192.168.43.109:3000"); // Replace with your frontend URL
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header('Access-Control-Allow-Credentials: true');

// Check if user is already logged in


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

// Prepare the SQL statement with variables included in the query string
$sql = "SELECT lecturer_id, department FROM lecturer WHERE lecturer_id = '$username' AND passwd = '$password'";
$result = $conn->query($sql);

// Check if the login is successful
if ($result->num_rows === 1) {
    // Successful login 
    $dept = $result->fetch_assoc();
    // Store user information in session
    $_SESSION['user_id'] = $username;
    $_SESSION['department'] = $dept['department'];

    // set role is an admin

    if ($_SESSION['department']  === 'ADMIN') {
        $_SESSION['role'] = 'admin';
        $response = array('status' => 200, 'message' => 'Login successful Admin');
        echo json_encode($response);
    }
    if ($_SESSION['department']  !== 'ADMIN') {
        $_SESSION['role'] = 'Lecturer';
        $response = array('status' => 202, 'message' => 'Login successful Lecturer');
        echo json_encode($response);
    }
} else {
    // Invalid credentials
    $response = array('status' => 401, 'message' => 'Invalid username or password');
    echo json_encode($response);
    exit();
}

// Close the database connection
$conn->close();
