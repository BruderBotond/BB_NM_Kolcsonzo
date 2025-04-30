<?php  
session_start();  
error_reporting(E_ALL);  
ini_set('display_errors', 1);  

$servername = "localhost";  
$username = "root";  
$password = "";  
$dbname = "luxhorizon";  

$conn = new mysqli($servername, $username, $password, $dbname);  

if ($conn->connect_error) {  
    die("Connection failed: " . $conn->connect_error);  
}  

if (isset($_POST['login'])) {  
    try {  
        $email = trim($_POST['email']);  
        $password = $_POST['password'];  

        // Email és jelszó ellenőrzése  
        $stmt = $conn->prepare("SELECT user_id, name, password_hash FROM users WHERE email = ?");  
        $stmt->bind_param("s", $email);  
        $stmt->execute();  
        $result = $stmt->get_result();  

        if ($result->num_rows === 1) {  
            $user = $result->fetch_assoc();  
            
            if (password_verify($password, $user['password_hash'])) {  
                // Sikeres bejelentkezés  
                $_SESSION['user_id'] = $user['user_id'];  
                $_SESSION['user_name'] = $user['name'];  

                // Last login frissítése  
                $update_stmt = $conn->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE user_id = ?");  
                $update_stmt->bind_param("i", $user['user_id']);  
                $update_stmt->execute();  
                $update_stmt->close();  

                $stmt->close();  
                header("Location: index.php"); // index.html helyett index.php  
                exit();  
            } else {  
                // Hibás jelszó  
                $_SESSION['error'] = "Invalid password!";  
                $_SESSION['old_login_email'] = $email;  
                $stmt->close();  
                header("Location: register.php");  
                exit();  
            }  
        } else {  
            // Nincs ilyen email címmel regisztrált felhasználó  
            $_SESSION['error'] = "This user doesn't exist!";  
            $_SESSION['old_login_email'] = $email;  
            $stmt->close();  
            header("Location: register.php");  
            exit();  
        }  
    } catch (Exception $e) {  
        $_SESSION['error'] = "An error occurred during login!";  
        header("Location: register.php");  
        exit();  
    }  
}  

$conn->close();  
?>  