<?php
// register.php - Registration page
session_start();
require_once 'config.php';
require_once 'auth.php';

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['register'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        
        if ($password !== $confirm_password) {
            $error_message = 'Passwords do not match';
        } else if (strlen($password) < 6) {
            $error_message = 'Password must be at least 6 characters';
        } else {
            if (register_user($pdo, $username, $password)) {
                $success_message = 'Registration successful! You can now login.';
            } else {
                $error_message = 'Username already exists';
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['form'])) {
        // Insert new entry
        $form = htmlspecialchars($_POST['username']);

        
        $insert_sql = 'INSERT INTO users (username, password) VALUES (:username, :password)';
        $stmt_insert = $pdo->prepare($insert_sql);
        $stmt_insert->execute(['username' => $username, 'password' => $password]);
    } elseif (isset($_POST['delete_id'])) {
        // Delete an entry
        $delete_id = (int) $_POST['delete_id'];
        
        $delete_sql = 'DELETE FROM users WHERE id = :id';
        $stmt_delete = $pdo->prepare($delete_sql);
        $stmt_delete->execute(['id' => $delete_id]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Betty's Book Banning</title>
    <link rel="stylesheet" href="stylesnew2.css">
</head>
<body>
    <div class="auth-container">
        <center><h1>Register</h1>
        <?php if ($error_message): ?>
            <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <?php if ($success_message): ?>
            <div class="success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="register.php" class="auth-form">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <br>
            <div>
                <label for="password">Password: </label>
                <input type="password" id="password" name="password" required>
            </div>
            <br>
            <div>
                <label for="confirm_password">Confirm Password: </label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <br>
            <button class="register-button1" type="submit" name="register">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>