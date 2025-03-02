<?php
error_reporting(E_ALL); // Report all errors
ini_set('display_errors', 1); // Display errors

header("Access-Control-Allow-Origin: *"); // Allow requests from any domain
header("Content-Type: application/json; charset=UTF-8"); // Set response type to JSON

// Get the brand name from the query parameter
$brandName = isset($_GET['brand']) ? $_GET['brand'] : '';

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

// Fetch cars for the specified brand
$sql = "SELECT * FROM cars WHERE brand_id = (SELECT brand_id FROM brands WHERE name = ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die(json_encode(['error' => 'Prepare failed: ' . $conn->error]));
}

$stmt->bind_param('s', $brandName);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $cars = [];
    while ($row = $result->fetch_assoc()) {
        $cars[] = $row;
    }
    echo json_encode($cars); // Return JSON response
} else {
    echo json_encode([]); // Return empty array if no data
}

// Close connection
$stmt->close();
$conn->close();
?>