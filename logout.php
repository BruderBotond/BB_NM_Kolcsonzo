<?php  
// logout.php  
require_once 'config.php';  

// Session törlése  
$_SESSION = [];  

// A session cookie törlése  
if (ini_get("session.use_cookies")) {  
    $params = session_get_cookie_params();  
    setcookie(session_name(), '', time() - 42000,  
        $params["path"], $params["domain"],  
        $params["secure"], $params["httponly"]  
    );  
}  

// Session megsemmisítése  
session_destroy();  

// Átirányítás a bejelentkező oldalra  
header("Location: register.php");  
exit;  
?>