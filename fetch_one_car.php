<?php
header("Access-Control-Allow-Origin: *"); // Allow requests from any domain
header("Content-Type: application/json; charset=UTF-8"); // Set response type to JSON

// Get the car model from the query parameter
$carModel = isset($_GET['model']) ? $_GET['model'] : '';

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

// Fetch car data
$sql = "SELECT * FROM cars WHERE model = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die(json_encode(['error' => 'Prepare failed: ' . $conn->error]));
}

$stmt->bind_param('s', $carModel);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $car = $result->fetch_assoc();
    echo json_encode($car); // Return JSON response
} else {
    echo json_encode(['error' => 'Car not found']);
}

// Close connection
$stmt->close();
$conn->close();
?>