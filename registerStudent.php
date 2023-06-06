<?php
session_start();

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    // User is already logged in, return success response 
    $response = array('status' => 'success', 'message' => 'User is already logged in');
    echo json_encode($response);
    exit();
} 

// Check if user is an admin
if ($_SESSION['role'] !== 'admin') {
    $response = array('status' => 'error', 'message' => 'Unauthorized access');
    echo json_encode($response);
    exit();
}

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

// Retrieve student details sent from the frontend
$fname = $_POST['firstName']; 
$lname = $_POST['LastName'];
$registrationNumber = $_POST['registrationNumber'];
$cardNumber = $_POST['cardNo']; // Exclude this from the students table
$year = $_POST['year']; 
$college = $_POST['college'];
$gender = $_POST['gender'];
$programme = $_POST['programme'];
$semester1Paid = $_POST['semester1Paid'] ? 1 : 0;
$semester2Paid = $_POST['semester2Paid'] ? 1 : 0; // Convert boolean value to 1 or 0
$admin_id = $_SESSION['user_id']; 


// Check if registration number or card number already exist in the database
$stmt_check1 = $conn->prepare("SELECT COUNT(*) AS count FROM Student WHERE registration_number = ? ");
$stmt_check1->bind_param("s", $registrationNumber);
$stmt_check1->execute();
$result1 = $stmt_check1->get_result();
$row1 = $result1->fetch_assoc();
$count1 = $row1['count'];

// If registration number or card number already exists, return an error response
if ($count1 > 0) {
    $response = array('status' => 'error', 'message' => 'Registration number or card number already exists');
    echo json_encode($response);
    exit();
} 
// Check if registration number or card number already exist in the database
$stmt_check2 = $conn->prepare("SELECT COUNT(*) AS count FROM Student_rfid_id WHERE  card_number = ?");
$stmt_check2->bind_param("s", $cardNumber);
$stmt_check2->execute();
$result2 = $stmt_check2->get_result();
$row2 = $result2->fetch_assoc();
$count2 = $row2['count'];

// If registration number or card number already exists, return an error response
if ($count2 > 0) {
    $response = array('status' => 'error', 'message' => 'Registration number or card number already exists');
    echo json_encode($response);
    exit();
} 


// Prepare the SQL statement to insert student details into the students table
$stmt_students = $conn->prepare("INSERT INTO  student(reg_no,Programme,s_lname,sf_name,college,Year_of_study,sem1_pay,sem2_pay, admin_id)  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt_students->bind_param("sssssiiii", $registrationNumber, $programme, $lname, $fname, $college, $year, $semester1Paid, $semester2Paid, $admin_id);

// Execute the statement to insert student details into the students table
$stmt_students->execute();

// Get the student ID of the newly inserted record
$studentId = $stmt_students->insert_id;

// Prepare the SQL statement to insert card details into the card table
$stmt_card = $conn->prepare("INSERT INTO  Student_rfid_id(rfid_id, reg_no) VALUES (?, ?)");
$stmt_card->bind_param("ss", $cardNumber, $registrationNumber);

// Execute the statement to insert card details into the student rfid table table
$stmt_card->execute();

// Close the prepared statements
$stmt_students->close();
$stmt_card->close();



// Close the database connection
$conn->close();

// Return a success response to the frontend
$response = array('status' => 'success', 'message' => 'Student registered successfully');
echo json_encode($response);
?>
