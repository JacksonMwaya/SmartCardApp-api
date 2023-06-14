<?php

header('Content-Type: application/json');
header("Access-Control-Expose-Headers: Access-Control-Allow-Origin");
header("Access-Control-Allow-Origin: http://localhost:3000"); // Replace with your frontend URL
header("Access-Control-Allow-Methods: GET");
header('Access-Control-Allow-Credentials: true'); 

session_start();


if (!isset($_SESSION['user_id'])) {
    $response = array('status' => 401, 'message' => 'Not logged in');
    echo json_encode($response);
    exit();
}

 // Connect to your database
 $servername = "localhost";
 $username_db = "root";
 $password_db = "";
 $dbname = "smartcard_db";

 $conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch student accesses and join with student table
$sql = "SELECT student_accesses.timestamp, student.sf_name, student.sl_name, student_accesses.reg_no, student_accesses.venue_id, student_accesses.college
        FROM student_accesses
        JOIN student ON student_accesses.reg_no = student.reg_no";

$result = $conn->query($sql); 

if (!$conn->query($sql)) {
    $response = array('status' => 500, 'message' => 'Failed to execute statement: ' . $conn->error);
    echo json_encode($response);
    exit();
}

// Check if any records were found
if ($result->num_rows > 0) { 

    $studentAccesses = array();

    // Fetch each row and store in the studentAccesses array
    while ($row = $result->fetch_assoc()) { 

        $studentAccess = array(
            'timestamp' => $row['timestamp'],
            'first_name' => $row['sf_name'],
            'last_name' => $row['sl_name'], 
            'reg_no' => $row['reg_no'],
            'venue_id' => $row['venue_id'],
            'college' => $row['college']
        ); 

        $studentAccesses[] = $studentAccess;
    }

    // Return the student accesses as a JSON response
    echo json_encode($studentAccesses); 

} else {
    // No records found
    $response = array(
        'status' => 404,
        'message' => 'Requested Resource not Found'
    );
    echo json_encode($response);
}

// Close the database connection
$conn->close();
