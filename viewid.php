<?php

header('Content-Type: application/json');
header("Access-Control-Expose-Headers: Access-Control-Allow-Origin");
header("Access-Control-Allow-Origin: http://192.168.43.109:3000"); // Replace with your frontend URL
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header('Access-Control-Allow-Credentials: true');

session_start();


// Check if the device name from the session matches the selected device name from the frontend 

$viewIdData = json_decode(file_get_contents('php://input'), true);

$_SESSION["deviceName"] = $viewIdData['deviceName'];
$_SESSION["cardID"] = $viewIdData['cardId'];  //comment this line

date_default_timezone_set('Africa/Dar_es_Salaam');



if ($_SESSION["deviceName"] == $_SESSION["deviceOption"]) {

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

    // Retrieve card ID from the session 
    //$_SESSION['cardID'] = '43f3513e'; //comment this line
    $cardID =   $_SESSION['cardID'];


    // Query the database to retrieve student details based on card ID and device name
    $sql1 = "SELECT sf_name, sl_name, Year_of_study, college, reg_no, Programme, img_dir, sem1_pay,sem2_pay 
            FROM student
            WHERE reg_no = ( SELECT reg_no FROM student_rfid_id WHERE rfid_id = '$cardID' )";

    $result = $conn->query($sql1);

    $student = array();
    // Check if a matching student record is found
    if ($result->num_rows > 0) {

        // Fetch the student details
        $row = $result->fetch_assoc();

        $student['RegistrationNumber'] = $row['reg_no'];
        $student['FirstName'] = $row['sf_name'];
        $student['LastName'] = $row['sl_name'];
        $student['Year'] = $row['Year_of_study'];
        $student['Programme'] = $row['Programme'];
        $student['College'] = $row['college'];
        $student['img_dir'] = "http://localhost:8080/smartcardapp-api/profilePicture/" . $row['reg_no'] . ".png";

        $timestamp = date('Y-m-d H:i:s');
        $regno = $row['reg_no'];
        $college = $row['college'];
        $venue = $_SESSION['deviceName'];

        $sql2 = "INSERT INTO student_accesses(timestamp, reg_no, venue_id, college)  VALUES ('$timestamp','$regno','$venue','$college')";
        if ($row['sem1_pay'] == 1 || $row['sem2_pay'] == 1) {
            if ($conn->query($sql2) === TRUE) {
                // Insert successful, return response with status 200, success message, and student array
                $response = array(
                    'status' => 200,
                    'message' => 'Student inserted successfully',
                    'student' => $student
                );
                echo json_encode($response);
                unset($_SESSION['cardID']);
            }
        } else {
            // Insert failed, return response with status 500 and error message
            $response = array(
                'status' => 500,
                'message' => 'Failed to insert student'
            );
            echo json_encode($response);
        }
    } else {

        // No matching student record found 

        $student['RegistrationNumber'] = "";
        $student['FirstName'] = "";
        $student['LastName'] = "";
        $student['Year'] = "";
        $student['Programme'] = "";
        $student['College'] = "";
        $student['img_dir'] = "";

        $response = array(
            'status' => 404,
            'message' => 'Student Does not exist'
        );
        echo json_encode($response);
    }

    // Close the database connection
    $conn->close();
} else {
    // Device name does not match, return an error response
    $response = array(
        'status' => 404,
        'message' => 'Wrong Device Selection'
    );
    echo json_encode($response);
}
