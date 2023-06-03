<?php
session_start();

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    // User is already logged in, return success response
    $response = array('status' => 'success', 'message' => 'User is already logged in');
    echo json_encode($response);
    exit();
}

// Check if the login request is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve username and password from the request
    $username = $_POST['lecturer_id'];
    $password = $_POST['password'];

    // Your database connection details
    $servername = "localhost";
    $username_db = "root";
    $password_db = " ";
    $dbname = "smartcard_db";

    // Create a database connection
    $conn = new mysqli($servername, $username_db, $password_db, $dbname);

    // Check connection
    if ($conn->connect_error) {
        // Connection error, return error response
        $response = array('status' => 'error', 'message' => 'Database connection error');
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
        $_SESSION['user_id'] = $username; // Store lecturer_id in the session

        // Return success response
        $response = array('status' => 'success', 'message' => 'Login successful');
        echo json_encode($response);
        exit();
    } else {
        // Invalid credentials
        $response = array('status' => 'error', 'message' => 'Invalid username or password');
        echo json_encode($response);
        exit();
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}

// If the request is not a POST request, return error response
$response = array('status' => 'error', 'message' => 'Invalid request');
echo json_encode($response);
