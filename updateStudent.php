<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $response = array('status' => 'error', 'message' => 'User not logged in');
    echo json_encode($response);
    exit();
}
// Check if the request method is PUT
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    $response = array('status' => 'error', 'message' => 'Invalid request method');
    echo json_encode($response);
    exit();
} 

// Retrieve registration number from the request URL or request body (depending on your API design)
$registrationNumber = $_REQUEST['registrationNumber'];

// Retrieve semester 2 payment status from the request body
$data = json_decode(file_get_contents('php://input'), true);
$semester2Paid = $data['semester2Paid'] ? 1 : 0; // Convert boolean value to 1 or 0

// Connect to your database
$servername = "localhost";
$username_db = "root";
$password_db = " ";
$dbname = "smartcard_db";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Prepare the SQL statement to update semester2_paid for the given registration number
$stmt = $conn->prepare("UPDATE students SET sem2_pay = ? WHERE reg_no = ?");
$stmt->bind_param("is", $semester2Paid, $registrationNumber);

// Execute the statement to update the value
$stmt->execute();

// Check if any rows were affected by the update operation
if ($stmt->affected_rows > 0) {
    $response = array('status' => 'success', 'message' => 'Semester 2 payment status updated successfully');
} else {
    $response = array('status' => 'error', 'message' => 'Registration number not found');
}

// Close the prepared statement
$stmt->close();

// Close the database connection
$conn->close();

// Return the response to the frontend
echo json_encode($response);
?>
