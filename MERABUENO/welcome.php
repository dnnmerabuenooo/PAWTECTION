<?php
session_start();

include 'db_connect.php';

if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pawtection | Welcome Page</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="welcome-box">
        <div class="logo">
            <i class="fas fa-paw"></i>
        </div>
        <div class="welcome-title">
            Welcome to Pawtection: A Centralized Data-Driven for San Juan City ABC Animal Bite Center with Prescriptive Analytics
        </div>
        <div class="description">
            Manage reports, data, and patient treatments with ease. Please login or sign up to get started.
        </div>
        
        <div class="welcome-buttons">
            <a href="login.php" class="welcome-btn">Login</a>
            <a href="signup.php" class="welcome-btn">Sign-up</a>
        </div>
    </div>
</body>
</html>