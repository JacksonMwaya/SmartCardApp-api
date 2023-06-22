<?php

header('Content-Type: application/json');
header("Access-Control-Expose-Headers: Access-Control-Allow-Origin");
header("Access-Control-Allow-Origin: *"); // Replace with your hardware URL
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header('Access-Control-Allow-Credentials: true');

$jsonData = json_decode(file_get_contents('php://input'), true);

// Receive card ID and device name from the hardware

$deviceName = $jsonData['deviceName'];
$cardID = $jsonData['cardID'];
$sessionId = $jsonData['sessionId'];

// Set the current session ID
session_id($sessionId);

session_start();

// Save device name and card ID in the session
$_SESSION['cardID'] = $cardID;
$_SESSION['device'] = $deviceName;


