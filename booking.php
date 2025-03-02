<?php  
header('Content-Type: text/plain; charset=utf-8');  

// Adatbázis kapcsolat  
$conn = new mysqli("localhost", "root", "", "luxhorizon");  
$conn->set_charset("utf8mb4");  

if ($conn->connect_error) {  
    die("Connection failed: " . $conn->connect_error);  
}  

// POST adatok fogadása és tisztítása  
$car_id = isset($_POST['car_id']) ? intval($_POST['car_id']) : 0;  
$track_id = isset($_POST['track_id']) ? intval($_POST['track_id']) : 0;  
$selected_date = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');  
$laps = isset($_POST['laps']) ? intval($_POST['laps']) : 0;  

// Adatok ellenőrzése  
if ($car_id <= 0 || $track_id <= 0 || $laps <= 0) {  
    echo "Hiányzó vagy érvénytelen adatok!";  
    exit;  
}  

try {  
    // Ellenőrizzük, hogy az autó már foglalt-e az adott napon  
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

    // Számoljuk ki a teljes árat  
    $total_price = $laps * 150;  

    // Foglalás beszúrása  
    $insert_sql = "INSERT INTO bookings (user_id, car_id, track_id, start_date, end_date,   
                   total_price, booking_type, laps, status)   
                   VALUES (1, ?, ?, ?, ?, ?, 'track_day', ?, 'confirmed')";  

    $stmt = $conn->prepare($insert_sql);  
    
    if (!$stmt) {  
        throw new Exception("Prepare failed: " . $conn->error);  
    }  

    $stmt->bind_param("iissdi",   
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
    $conn->close();  
}  
?>  