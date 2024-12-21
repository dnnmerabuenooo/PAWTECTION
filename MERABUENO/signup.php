<?php
include 'db_connect.php';
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = $_POST['employeeid'];
    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $middleinitial = $_POST['middleinitial'];
    $email = $_POST['email'];
    $phone_number = $_POST['phonenumber'];
    $password = $_POST['e_password'];
    $confirm_password = $_POST['confirmpassword'];

    // Check if Employee ID already exists
    $sql = "SELECT * FROM employee WHERE employee_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $employee_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $_SESSION['signup_error'] = "Employee ID already exists.";
            header("Location: signup.php");
            exit;
        }
    }

    // Check if Email already exists
    $sql = "SELECT * FROM employee WHERE email = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $_SESSION['signup_error'] = "Email already registered.";
            header("Location: signup.php");
            exit;
        }
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !strpos($email, '@') || !strpos($email, '.com')) {
        $_SESSION['signup_error'] = "Invalid email format. Please use a valid email with '@' and '.com'.";
        header("Location: signup.php");
        exit;
    }

    // Validate phone number (must start with '09' and be 11 digits)
    if (!preg_match("/^09\d{9}$/", $phone_number)) {
        $_SESSION['signup_error'] = "Phone number must start with '09' and be 11 digits long.";
        header("Location: signup.php");
        exit;
    }

    // Validate password length
    if (strlen($password) < 8) {
        $_SESSION['signup_error'] = "Password must be at least 8 characters long.";
        header("Location: signup.php");
        exit;
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        $_SESSION['signup_error'] = "Passwords do not match.";
        header("Location: signup.php");
        exit;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new employee record
    $sql = "INSERT INTO employee (employee_id, lastname, firstname, middleinitial, email, phone_number, password) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssssss", $employee_id, $lastname, $firstname, $middleinitial, $email, $phone_number, $hashed_password);
        if ($stmt->execute()) {
            header("Location: login.php"); 
            exit;
        } else {
            $_SESSION['signup_error'] = "Error: " . $stmt->error;
            header("Location: signup.php"); 
            exit;
        }
        $stmt->close();
    } else {
        $_SESSION['signup_error'] = "Error preparing the statement: " . $conn->error;
        header("Location: signup.php");
        exit;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pawtection | Sign-Up Page</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2 class="center-title">Centralized Data-Driven for San Juan City ABC Animal Bite Center with Prescriptive Analytics</h2>
        <div class="signup-box">
            <form method="POST" action="signup.php">
                <div class="input-field">
                    <label for="employeeid">Employee ID:</label>
                    <input type="text" id="employeeid" name="employeeid" placeholder="Enter your employee ID" required>
                </div>
                <div class="input-field">
                    <label for="lastname">Last Name:</label>
                    <input type="text" id="lastname" name="lastname" placeholder="Enter your last name" required>
                </div>
                <div class="input-field">
                    <label for="firstname">First Name:</label>
                    <input type="text" id="firstname" name="firstname" placeholder="Enter your first name" required>
                </div>
                <div class="input-field">
                    <label for="middleinitial">Middle Initial:</label>
                    <input type="text" id="middleinitial" name="middleinitial" placeholder="Enter your middle initial" required>
                </div>
                <div class="input-field">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="input-field">
                    <label for="phonenumber">Phone Number:</label>
                    <input type="text" id="phonenumber" name="phonenumber" placeholder="Enter your phone number" required>
                </div>
                <div class="input-field">
                    <label for="password">Password:</label>
                    <input type="password" id="e_password" name="e_password" placeholder="Enter your password" required>
                </div>
                <div class="input-field">
                    <label for="confirmpassword">Confirm Password:</label>
                    <input type="password" id="confirmpassword" name="confirmpassword" placeholder="Confirm your password" required>
                </div>
                <button type="submit" class="signup-btn">Sign Up</button>
            </form>
            
            <?php if (isset($_SESSION['signup_error'])): ?>
                <div class="error-popup">
                    <p><?php echo $_SESSION['signup_error']; ?></p>
                    <button onclick="this.parentElement.style.display='none'">Close</button>
                </div>
                <?php unset($_SESSION['signup_error']); ?>
            <?php endif; ?>

            <div class="login-link">
                <p>Already have an account? <a href="login.php">Login</a></p>
            </div>
        </div>
    </div>
</body>
</html>
