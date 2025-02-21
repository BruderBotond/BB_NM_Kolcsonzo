<?php
// Adatbázis kapcsolat
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "car_rental";

$conn = new mysqli(hostname: $servername, username: $username, password: $password, database: $dbname);
$result = $conn->query(query: "SELECT DISTINCT brand FROM cars");

while ($row = $result->fetch_assoc()) {
    echo "<a href='cars.php?brand=" . urlencode(string: $row['brand']) . "'>" . $row['brand'] . "</a>";
}

$conn->close();
?>
