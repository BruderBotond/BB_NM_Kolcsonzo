<?php
header("Access-Control-Allow-Origin: *"); // Allow requests from any domain
header("Content-Type: application/json; charset=UTF-8"); // Set response type to JSON

// Get the track name from the query parameter
$trackName = isset($_GET['track']) ? $_GET['track'] : '';

// Database connection
$servername = "127.0.0.1";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "luxhorizon";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Fetch track data (only image_url)
$sql = "SELECT image_url FROM tracks WHERE name = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die(json_encode(['error' => 'Prepare failed: ' . $conn->error]));
}

$stmt->bind_param('s', $trackName);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $track = $result->fetch_assoc();
    echo json_encode(['image_url' => $track['image_url']]); // Return JSON response
} else {
    echo json_encode(['error' => 'Track not found']);
}

// Close connection
$stmt->close();
$conn->close();
?>