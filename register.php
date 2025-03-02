<?php  
session_start();  
?>  
<!DOCTYPE html>  
<html lang="hu">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Log in Or Sign Up</title>  
    <link rel="stylesheet" href="./Styles/register.css">  
    <link rel="stylesheet" href="./Styles/navbar.css">  
    <link rel="stylesheet" href="./Styles/footer.css">  
    <style>  
        .error-message {  
            background-color: #ff5757;  
            color: white;  
            padding: 10px;  
            border-radius: 5px;  
            margin: 10px 0;  
            text-align: center;  
            width: 100%;  
        }  
        .success-message {  
            background-color: #4CAF50;  
            color: white;  
            padding: 10px;  
            border-radius: 5px;  
            margin: 10px 0;  
            text-align: center;  
            width: 100%;  
        }  
    </style>  
</head>  
<body>  
    <?php  
    if(isset($_SESSION['error'])) {  
        echo '<div class="error-message">' . htmlspecialchars($_SESSION['error']) . '</div>';  
        unset($_SESSION['error']);  
    }  
    if(isset($_SESSION['success'])) {  
        echo '<div class="success-message">' . htmlspecialchars($_SESSION['success']) . '</div>';  
        unset($_SESSION['success']);  
    }  
    ?>  
    <div class="container">  
        <div class="slider"></div>  
        <div class="btn">  
            <button class="login">Login</button>  
            <button class="signup">Signup</button>  
        </div>  

        <div class="form-section">  
            <form action="./login.php" method="post" class="login-box">  
                <input type="email" name="email" class="email ele" placeholder="example@email.com"   
                    value="<?php echo isset($_SESSION['old_login_email']) ? htmlspecialchars($_SESSION['old_login_email']) : ''; ?>" required>  
                <input type="password" name="password" class="password ele" placeholder="Password" required>  
                <button type="submit" name="login" class="clkbtn">Login</button>  
            </form>  

            <form action="./signup.php" method="post" class="signup-box">  
                <input type="text" name="name" class="name ele" placeholder="Enter your name"   
                    value="<?php echo isset($_SESSION['old_name']) ? htmlspecialchars($_SESSION['old_name']) : ''; ?>" required>  
                <input type="email" name="email" class="email ele" placeholder="example@email.com"   
                    value="<?php echo isset($_SESSION['old_email']) ? htmlspecialchars($_SESSION['old_email']) : ''; ?>" required>  
                <input type="password" name="password" class="password ele" placeholder="Password" required>  
                <input type="password" name="confirm_password" class="password ele" placeholder="Confirm password" required>  
                <button type="submit" name="signup" class="clkbtn">Signup</button>  
            </form>  
        </div>  
    </div>  

    <script>  
        // Az oldal betöltésekor ellenőrizzük, hogy melyik formot kell mutatni  
        window.onload = function() {  
            if (sessionStorage.getItem('show_signup')) {  
                document.querySelector('.signup').click();  
                sessionStorage.removeItem('show_signup');  
            }  
            // Ha van login hiba, akkor maradjon a login form aktív  
            <?php if(isset($_SESSION['old_login_email'])) { ?>  
                document.querySelector('.login').click();  
            <?php } ?>  
        };  

        <?php  
        if (isset($_SESSION['show_signup'])) {  
            echo "sessionStorage.setItem('show_signup', 'true');";  
            unset($_SESSION['show_signup']);  
        }  
        ?>  
    </script>  
    <script src="register.js"></script>  
</body>  
</html>  

<?php  
// Töröljük a régi session adatokat  
unset($_SESSION['old_name']);  
unset($_SESSION['old_email']);  
unset($_SESSION['old_login_email']);  
?>  