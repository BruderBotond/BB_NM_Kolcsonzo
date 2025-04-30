<?php  
// config.php - Adatbázis és alap konfiguráció  
session_start();  
error_reporting(E_ALL);  
ini_set('display_errors', 1);  

// A projekt webes alapútvonala  
$base_url = "/LuxHorizon";  

// Adatbázis kapcsolat adatai  
$servername = "localhost";  
$username = "root";  
$password = "";  
$dbname = "luxhorizon";  

// Adatbázis kapcsolat létrehozása  
$conn = new mysqli($servername, $username, $password, $dbname);  
if ($conn->connect_error) {  
    die("Adatbázis kapcsolódási hiba: " . $conn->connect_error);  
}  

// Segédfüggvények  
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