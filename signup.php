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

if (isset($_POST['signup'])) {  
    try {  
        // Adatok tisztítása  
        $name = trim($_POST['name']);  
        $email = trim($_POST['email']);  
        $password = $_POST['password'];  
        $confirm_password = $_POST['confirm_password'];  

        // Ellenőrzések  
        if (empty($name) || empty($email) || empty($password)) {  
            $_SESSION['error'] = "Minden mező kitöltése kötelező!";  
            $_SESSION['show_signup'] = true;  
            header("Location: " . $_SERVER['HTTP_REFERER']);  
            exit();  
        }  

        // Jelszó hosszának ellenőrzése  
        if (strlen($password) < 8) {  
            $_SESSION['error'] = "A jelszónak minimum 8 karakter hosszúnak kell lennie!";  
            $_SESSION['old_name'] = $name;  
            $_SESSION['old_email'] = $email;  
            $_SESSION['show_signup'] = true;  
            header("Location: " . $_SERVER['HTTP_REFERER']);  
            exit();  
        }  

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {  
            $_SESSION['error'] = "Érvénytelen email cím!";  
            $_SESSION['old_name'] = $name;  
            $_SESSION['old_email'] = $email;  
            $_SESSION['show_signup'] = true;  
            header("Location: " . $_SERVER['HTTP_REFERER']);  
            exit();  
        }  

        if ($password !== $confirm_password) {  
            $_SESSION['error'] = "A jelszavak nem egyeznek!";  
            $_SESSION['old_name'] = $name;  
            $_SESSION['old_email'] = $email;  
            $_SESSION['show_signup'] = true;  
            header("Location: " . $_SERVER['HTTP_REFERER']);  
            exit();  
        }  

        // Email ellenőrzése  
        $check_stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");  
        $check_stmt->bind_param("s", $email);  
        $check_stmt->execute();  
        $check_result = $check_stmt->get_result();  
        
        if ($check_result->num_rows > 0) {  
            $check_stmt->close();  
            $_SESSION['error'] = "Ez az email cím már regisztrálva van!";  
            $_SESSION['old_name'] = $name;  
            $_SESSION['old_email'] = $email;  
            $_SESSION['show_signup'] = true;  
            header("Location: " . $_SERVER['HTTP_REFERER']);  
            exit();  
        }  
        $check_stmt->close();  

        // Jelszó hashelése  
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);  

        // Felhasználó beszúrása  
        $insert_stmt = $conn->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");  
        $insert_stmt->bind_param("sss", $name, $email, $hashed_password);  

        if ($insert_stmt->execute()) {  
            $insert_stmt->close();  
            $_SESSION['success'] = "Sikeres regisztráció!";  
            header("Location: " . $_SERVER['HTTP_REFERER']);  
            exit();  
        } else {  
            $_SESSION['error'] = "Hiba történt a regisztráció során: " . $conn->error;  
            $_SESSION['old_name'] = $name;  
            $_SESSION['old_email'] = $email;  
            $_SESSION['show_signup'] = true;  
            header("Location: " . $_SERVER['HTTP_REFERER']);  
            exit();  
        }  

    } catch (Exception $e) {  
        $_SESSION['error'] = $e->getMessage();  
        $_SESSION['old_name'] = $name;  
        $_SESSION['old_email'] = $email;  
        $_SESSION['show_signup'] = true;  
        header("Location: " . $_SERVER['HTTP_REFERER']);  
        exit();  
    }  
}  

$conn->close();  
?>  