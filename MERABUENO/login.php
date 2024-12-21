<?php
include 'db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM employee WHERE email = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['employee_id'];
            $_SESSION['user_name'] = $user['firstname'] . " " . $user['lastname'];
            header("Location: dashboard.php"); 
            exit;
        } 
        elseif ($user && !password_verify($password, $user['password'])) {
            $_SESSION['login_error'] = "Incorrect credentials. Please try again.";
            header("Location: login.php"); 
            exit;
        } 
        else {
            $_SESSION['login_error'] = "No account found with this email.";
            header("Location: login.php"); 
            exit;
        }
    } else {
        $_SESSION['login_error'] = "Error with the login process.";
        header("Location: login.php");
        exit;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pawtection | Login Page</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2 class="center-title">Centralized Data-Driven for San Juan City ABC Animal Bite Center with Prescriptive Analytics</h2>
        
        <div class="login-box">
            <form method="POST" action="login.php">
                <div class="input-field">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="input-field">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="login-btn">Login</button>

                <?php if (isset($_SESSION['login_error'])): ?>
                    <div class="error-popup">
                        <p><?php echo $_SESSION['login_error']; ?></p>
                        <button onclick="this.parentElement.style.display='none'">Close</button>
                    </div>
                    <?php unset($_SESSION['login_error']); ?>
                <?php endif; ?>
            </form>
            <div class="signup-link">
                <p>Don't have an account? <a href="signup.php">Sign up</a></p>
            </div>
        </div>
    </div>
</body>
</html>
