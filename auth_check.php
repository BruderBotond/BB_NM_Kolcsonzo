<?php  

if (session_status() === PHP_SESSION_NONE) {  
    session_start();  
}  

$base_url = "/LuxHorizon";  

if (!isset($_SESSION['user_id'])) {  
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {  
        header('Content-Type: application/json');  
        echo json_encode([  
            'status' => 'error',  
            'message' => 'Authentication required',  
            'redirect' => $base_url . '/register.php'  
        ]);  
        exit;  
    } else {  
        $_SESSION['error'] = "Login is required to continue!";  
        header("Location: $base_url/register.php");  
        exit;  
    }  
}  

$servername = "localhost";  
$username = "root";  
$password = "";  
$dbname = "luxhorizon";  

$conn = new mysqli($servername, $username, $password, $dbname);  
if ($conn->connect_error) {  
    die("Adatbázis kapcsolódási hiba: " . $conn->connect_error);  
}  

function isLoggedIn() {  
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);  
}  

function getUserId() {  
    return $_SESSION['user_id'] ?? null;  
}  

function getUserName() {  
    return $_SESSION['user_name'] ?? '';  
}  

function redirectTo($page) {  
    global $base_url;  
    header("Location: $base_url/$page");  
    exit;  
}  
?>  