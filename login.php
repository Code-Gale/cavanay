<?php
// Start session
session_start();

// Include database connection
require_once 'config/db.php';

// Check if user is already logged in
if(isset($_SESSION['user_id'])){
    header('Location: dashboard.php');
    exit;
}

// Check if form is submitted
if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email and password are empty
    if(empty($email) || empty($password)){
        $msg = 'Please enter email and password';
    }else{
        // Prepare query to check if user exists
        $sql = 'SELECT * FROM users WHERE email = :email';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        // Check if user exists
        if($user){
            // Verify password
            if(password_verify($password, $user['password'])){
                // User authenticated, set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['name'];
                header('Location: dashboard.php');
                exit;
            }else{
                $msg = 'Incorrect password';
            }
        }else{
            $msg = 'User does not exist';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <?php if(isset($msg)): ?>
            <div class="error"><?php echo $msg; ?></div>
        <?php endif; ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label for="email">Email</label>
            <input type="email" name="email" required>
            <label for="password">Password</label>
            <input type="password" name="password" required>
            <button type="submit" name="login">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register</a></p>
    </div>
</body>
</html>
