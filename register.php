<?php
session_start();
include "config.php";

if (isset($_POST['register'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (email, password, role) VALUES ('$email', '$hashed_password', 'user')";
        if (mysqli_query($conn, $query)) {
            header("Location: login.php");
            exit;
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    
    <div class ="container">
        <div class="left-side">
        <h1>LAMPUNG TRIP</h1>
        <h3>Discover the beauty of 
            Lampung with ease.</h3>
        <p>
            Explore amazing destinations, find 
            travel information, and join 
            exciting open trips 
            for unforgettable experiences.
        </p>
        </div>

        <div class="login-box">
            <form method="POST">
                <label>Email</label>
                <input type="email" name="email" placeholder="Enter your email" required>

                <label>Password</label>
                <input type="password" name="password" placeholder="Create password" required>

                <label>Confirm Password</label>
                <input type="password" name="confirm" placeholder="Confirm password" required>

                <button type="submit" name="register">SIGN UP</button>

                <div class="divider">or</div>

                <p class="SignUp">
                    Already have an account? <a href="login.php">Login</a>
                </p>
            </form>
        </div>
    </div>
</body>
</html>