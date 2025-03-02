<?php
header(header: "Access-Control-Allow-Origin: *"); // Allow requests from any domain
header(header: "Content-Type: application/json; charset=UTF-8"); // Set response type to JSON

// Database connection
$servername = "127.0.0.1";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "luxhorizon";

// Create connection
$conn = new mysqli(hostname: $servername, username: $username, password: $password, database: $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the brands table
$sql = "SELECT name, logo_url FROM brands";
$result = $conn->query(query: $sql);

if ($result->num_rows > 0) {
    $brands = [];
    while ($row = $result->fetch_assoc()) {
        $brands[] = $row;
    }
    echo json_encode(value: $brands); // Return JSON response
} else {
    echo json_encode(value: []); // Return empty array if no data
}

// Close connection
$conn->close();
?>