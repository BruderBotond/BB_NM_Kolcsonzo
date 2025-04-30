<?php  
header('Content-Type: text/plain; charset=utf-8');  

$conn = new mysqli("localhost", "root", "", "luxhorizon");  
$conn->set_charset("utf8mb4");  

if ($conn->connect_error) {  
    die("Connection failed: " . $conn->connect_error);  
}  

$car_id = isset($_POST['car_id']) ? intval($_POST['car_id']) : 0;  
$track_id = isset($_POST['track_id']) ? intval($_POST['track_id']) : 0;  
$selected_date = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');  
$laps = isset($_POST['laps']) ? intval($_POST['laps']) : 0;  

if ($car_id <= 0 || $track_id <= 0 || $laps <= 0) {  
    echo "Hiányzó vagy érvénytelen adatok!";  
    exit;  
}  

try {  
    $check_sql = "SELECT COUNT(*) as count FROM bookings   
                  WHERE car_id = ?   
                  AND start_date = ?   
                  AND status = 'confirmed'";  
    
    $check_stmt = $conn->prepare($check_sql);  
    if (!$check_stmt) {  
        throw new Exception("Prepare failed: " . $conn->error);  
    }  
    
    $check_stmt->bind_param("is", $car_id, $selected_date);  
    $check_stmt->execute();  
    $result = $check_stmt->get_result();  
    $row = $result->fetch_assoc();  
    
    if ($row['count'] > 0) {  
        echo "Ez az autó már foglalt erre a napra!";  
        $check_stmt->close();  
        exit;  
    }  
    $check_stmt->close();  

    // Fetch the correct price for the selected number of laps
    $lap_price_field = "";
    switch ($laps) {
        case 1:
            $lap_price_field = "lap_price_1";
            break;
        case 3:
            $lap_price_field = "lap_price_3";
            break;
        case 5:
            $lap_price_field = "lap_price_5";
            break;
        case 10:
            $lap_price_field = "lap_price_10";
            break;
        default:
            throw new Exception("Érvénytelen körszám!");
    }
    
    // Get price from database
    $price_sql = "SELECT $lap_price_field AS price FROM cars WHERE car_id = ?";
    $price_stmt = $conn->prepare($price_sql);
    if (!$price_stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $price_stmt->bind_param("i", $car_id);
    $price_stmt->execute();
    $price_result = $price_stmt->get_result();
    
    if ($price_result->num_rows === 0) {
        throw new Exception("Az autó nem található!");
    }
    
    $price_row = $price_result->fetch_assoc();
    $total_price = $price_row['price'];
    $price_stmt->close();
      
    $user_query = "SELECT user_id FROM users LIMIT 1";  
    $user_result = $conn->query($user_query);  
    if ($user_result && $user_result->num_rows > 0) {  
        $user_id = $user_result->fetch_assoc()['user_id'];  
    } else {  
        throw new Exception("Nem található felhasználó az adatbázisban!");  
    }  
    

    // Foglalás beszúrása  
    $insert_sql = "INSERT INTO bookings (user_id, car_id, track_id, start_date, end_date,   
                   total_price, booking_type, laps, status)   
                   VALUES (?, ?, ?, ?, ?, ?, 'track_day', ?, 'confirmed')";  

    $stmt = $conn->prepare($insert_sql);  
    
    if (!$stmt) {  
        throw new Exception("Prepare failed: " . $conn->error);  
    }  

    $stmt->bind_param("iiissdi",   
        $user_id,  
        $car_id,  
        $track_id,  
        $selected_date,  
        $selected_date,  
        $total_price,  
        $laps  
    );  

    if ($stmt->execute()) {  
        echo "Sikeres foglalás!";  
    } else {  
        throw new Exception("Execute failed: " . $stmt->error);  
    }  

} catch (Exception $e) {  
    echo "Hiba történt: " . $e->getMessage();  
} finally {  
    if (isset($stmt)) {  
        $stmt->close();  
    }
    if (isset($price_stmt)) {  
        $price_stmt->close();  
    }
    $conn->close();  
}  
?>