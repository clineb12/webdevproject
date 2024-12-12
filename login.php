<?php
// login.php - Login page
session_start();
require_once 'config.php';
require_once 'auth.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        if (login_user($pdo, $username, $password)) {
            header('Location: frontpage.html');
            exit;
        } else {
            $error_message = 'Invalid username or password';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Nightmare Nexus</title>
    <link rel="stylesheet" href="stylesnew2.css">
</head>
<body>
    <div class="auth-container">

        <center><h1>Login</h1>
        <?php if ($error_message): ?>
            <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        
        
        <form method="POST" action="" class="auth-form">
            <div>
                <label for="username">Username: </label>
                <input type="text" id="username" name="username" required>
            </div>
            <br><br>
            <div>
                <label for="password">Password: </label>
                <input type="password" id="password" name="password" required>
            </div>
            <br><br>
            <button class="register-button1" type="submit" name="login">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>