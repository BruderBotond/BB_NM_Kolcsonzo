<?php
header('Content-Type: application/json; charset=utf-8');

$conn = new mysqli("localhost", "root", "", "luxhorizon");
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die(json_encode(['error' => "Connection failed: " . $conn->connect_error]));
}

// Get car ID from request
$car_id = isset($_GET['car_id']) ? intval($_GET['car_id']) : 0;

if ($car_id <= 0) {
    echo json_encode(['error' => 'Invalid car ID']);
    exit;
}

try {
    // Fetch lap prices for the specified car
    $sql = "SELECT lap_price_1, lap_price_3, lap_price_5, lap_price_10 FROM cars WHERE car_id = ?";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("i", $car_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['error' => 'Car not found']);
        exit;
    }
    
    $prices = $result->fetch_assoc();
    echo json_encode($prices);
    
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
}
?>