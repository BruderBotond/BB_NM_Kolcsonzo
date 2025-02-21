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

//  URL-ből a márka beolvasása (pl. cars.php?brand=Audi)
$brand = isset($_GET['brand']) ? $_GET['brand'] : 'Audi';

//  Lekérdezés az adott márka autóira
$sql = "SELECT * FROM cars WHERE brand = ?";
$stmt = $conn->prepare(query: $sql);
$stmt->bind_param(types: "s", var: $brand);
$stmt->execute();
$result = $stmt->get_result();

$cars = [];
while ($row = $result->fetch_assoc()) {
    $cars[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $brand; ?></title>
    <link rel="stylesheet" href="brands.css">
</head>
<body>

    <div id="navbar"></div>

    <main>
        <h1 class="brand-title"><?php echo $brand; ?></h1>
        <div class="car-list">
            <?php foreach ($cars as $car) : ?>
                <div class="car-card">
                    <div class="specs">
                        <div class="spec-item"><?php echo $car['hp']; ?> hp</div>
                        <div class="spec-item"><?php echo $car['engine']; ?></div>
                        <div class="spec-item"><?php echo $car['top_speed']; ?> km/h</div>
                        <div class="spec-item"><?php echo $car['gears']; ?> gears</div>
                        <div class="spec-item"><?php echo $car['drive']; ?></div>
                        <div class="spec-item">0-100: <?php echo $car['acceleration']; ?> s</div>
                    </div>
                    <div class="car-image">
                        <img src="<?php echo $car['image_url']; ?>" alt="<?php echo $car['model']; ?>">
                        <h2><?php echo $car['brand'] . " " . $car['model']; ?></h2>
                    </div>
                    <div class="pricing">
                        <h3>Prices:</h3>
                        <div class="price-item">
                            <span>Rent:</span>
                            <span>From <?php echo $car['rent_price']; ?> HUF/day</span>
                        </div>
                        <div class="price-item">
                            <span>Track use:</span>
                            <span><?php echo $car['lap_price_1']; ?> HUF/1 lap</span>
                            <span><?php echo $car['lap_price_3']; ?> HUF/3 laps</span>
                            <span><?php echo $car['lap_price_5']; ?> HUF/5 laps</span>
                            <span><?php echo $car['lap_price_10']; ?> HUF/10 laps</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <div id="footer"></div>

</body>
</html>
