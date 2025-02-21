<?php
//  Adatbázis kapcsolat
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "car_rental";

$conn = new mysqli(hostname: $servername, username: $username, password: $password, database: $dbname);

//  Kapcsolat ellenőrzése
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $track; ?></title>
    <link rel="stylesheet" href="tracks.css">
</head>
<body>
    
</body>
</html>