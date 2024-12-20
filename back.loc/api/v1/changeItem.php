<?php

//CORS
header("Access-Control-Allow-Origin: http://front.loc");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

// database connection
global $conn;
require_once("db_conect.php");

//receives json  { id: 22, text: "...", checked: true }
$jsonGet = file_get_contents('php://input');
$json = json_decode($jsonGet, true);

$id = htmlspecialchars(strip_tags($json["id"])); // The ID of the record to update
$text = htmlspecialchars(strip_tags($json["text"]));// New values to update
$checked = htmlspecialchars(strip_tags($json["checked"])); // New values to update

// SQL-request to update a record
$stmt = $conn->prepare("UPDATE items SET text = ?, checked = ? WHERE id = ?"); // prepared request
$stmt->bind_param("sii", $text, $checked, $id);

if ($stmt->execute()) {
    http_response_code(200);
    echo json_encode(['ok' => true]);

} else {
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}

$stmt->close();
$conn->close();
