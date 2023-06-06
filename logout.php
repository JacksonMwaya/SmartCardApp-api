<?php
session_start();

// Destroy the session
session_destroy();

// Return a success response
$response = array('status' => 'success', 'message' => 'Logout successful');
echo json_encode($response);
?>
