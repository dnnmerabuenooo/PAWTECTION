<?php   
include 'db_connect.php';
session_start();

// Initialize an array to store error messages
$errors = [];

// Initialize variables to hold form data (with empty strings as defaults)
$fullname = $address = $age = $contactnumber = $emailadd = $animaltype = $dateofbite = $timeofbite = $placeofincident = '';

// Flag to check if the form is successfully submitted
$submitted = false;
$nameExists = false; // Flag to check if the name already exists

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data from POST
    $fullname = $_POST['fullname'] ?? '';
    $address = $_POST['address'] ?? '';
    $age = $_POST['age'] ?? '';
    $contactnumber = $_POST['contactnumber'] ?? '';
    $emailadd = $_POST['emailadd'] ?? '';
    $animaltype = $_POST['animaltype'] ?? '';
    $dateofbite = $_POST['dateofbite'] ?? '';
    $timeofbite = $_POST['timeofbite'] ?? '';
    $placeofincident = $_POST['placeofincident'] ?? '';

    // Validate the inputs
    if (empty($fullname)) {
        $errors[] = 'Full Name is required.';
    }
    if (empty($address)) {
        $errors[] = 'Address is required.';
    }
    if (empty($age)) {
        $errors[] = 'Age is required.';
    } elseif ($age == 0) {
        $errors[] = 'Age cannot be 0.';
    }
    if (empty($contactnumber)) {
        $errors[] = 'Contact Number is required.';
    } elseif (!preg_match('/^09\d{9}$/', $contactnumber)) {
        $errors[] = 'Contact Number must start with "09" and be 11 digits long.';
    }
    if (empty($emailadd)) {
        $errors[] = 'Email Address is required.';
    } elseif (!filter_var($emailadd, FILTER_VALIDATE_EMAIL) || !preg_match('/@.*\.com$/', $emailadd)) {
        $errors[] = 'Email Address must contain "@" and end with ".com".';
    }
    if (empty($animaltype)) {
        $errors[] = 'Animal Type is required.';
    }
    if (empty($dateofbite)) {
        $errors[] = 'Date of Bite is required.';
    }
    if (empty($timeofbite)) {
        $errors[] = 'Time of Bite is required.';
    }
    if (empty($placeofincident)) {
        $errors[] = 'Place of Incident is required.';
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM cases WHERE fullname = ?");
        $stmt->bind_param("s", $fullname);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            $nameExists = true; 
        } else {
            $stmt = $conn->prepare("INSERT INTO cases (fullname, address, age, contactnumber, emailadd, animaltype, dateofbite, timeofbite, placeofincident) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssissssss", $fullname, $address, $age, $contactnumber, $emailadd, $animaltype, $dateofbite, $timeofbite, $placeofincident);

            if ($stmt->execute()) {
                $submitted = true; 
            } else {
                $errors[] = 'Error: ' . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Report Form</title>
    <link rel="stylesheet" href="ir.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
</head>
<body>
    <h1>Patient Incident Report</h1>

    <a href="dashboard.php" class="back-button">
        <i class="fas fa-arrow-left"></i> 
    </a>

    <?php if ($nameExists): ?>
        <script>
            alert("A record with the same name already exists.");
        </script>
    <?php endif; ?>

    <form action="" method="POST">
        
        <div class="form-group input-field">
            <label for="fullname">Full Name:</label>
            <input type="text" id="fullname" name="fullname" placeholder="Patient's full name" value="<?php echo htmlspecialchars($fullname); ?>" required>
        </div>

        <div class="form-group input-field">
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" placeholder="Patient's full address" value="<?php echo htmlspecialchars($address); ?>" required>
        </div>

        <div class="form-group input-field">
            <label for="age">Age:</label>
            <input type="number" id="age" name="age" placeholder="Patient's age" value="<?php echo htmlspecialchars($age); ?>" required>
        </div>

        <div class="form-group input-field">
            <label for="contactnumber">Contact Number:</label>
            <input type="tel" id="contactnumber" name="contactnumber" placeholder="Patient's contact number" value="<?php echo htmlspecialchars($contactnumber); ?>" required>
        </div>

        <div class="form-group input-field">
            <label for="emailadd">Email:</label>
            <input type="email" id="emailadd" name="emailadd" placeholder="Patient's email address" value="<?php echo htmlspecialchars($emailadd); ?>" required>
        </div>

        <div class="form-group input-field">
            <label for="animaltype">Animal Type:</label>
            <select id="animaltype" name="animaltype" required>
                <option value=""> - Select Animal Type - </option>
                <option value="Dog" <?php echo ($animaltype == 'Dog') ? 'selected' : ''; ?>>Dog</option>
                <option value="Cat" <?php echo ($animaltype == 'Cat') ? 'selected' : ''; ?>>Cat</option>
            </select>
        </div>

        <div class="form-group input-field">
            <label for="dateofbite">Date of Bite:</label>
            <input type="date" id="dateofbite" name="dateofbite" value="<?php echo htmlspecialchars($dateofbite); ?>" required>
        </div>

        <div class="form-group input-field">
            <label for="timeofbite">Time of Bite:</label>
            <input type="time" id="timeofbite" name="timeofbite" value="<?php echo htmlspecialchars($timeofbite); ?>" required>
        </div>

        <div class="form-group input-field">
            <label for="placeofincident">Place of Incident:</label>
            <select id="placeofincident" name="placeofincident" required>
                <option value=""> - Select Place of Incident - </option>
                <option value="Addition Hills" <?php echo ($placeofincident == 'Addition Hills') ? 'selected' : ''; ?>>Addition Hills</option>
                <option value="Balong-Bato" <?php echo ($placeofincident == 'Balong-Bato') ? 'selected' : ''; ?>>Balong-Bato</option>
                <option value="Batis" <?php echo ($placeofincident == 'Batis') ? 'selected' : ''; ?>>Batis</option>
                <option value="Corazon de Jesus" <?php echo ($placeofincident == 'Corazon de Jesus') ? 'selected' : ''; ?>>Corazon de Jesus</option>
                <option value="Ermitano" <?php echo ($placeofincident == 'Ermitano') ? 'selected' : ''; ?>>Ermitano</option>
                <option value="Greenhills" <?php echo ($placeofincident == 'Greenhills') ? 'selected' : ''; ?>>Greenhills</option>
                <option value="Isabelita" <?php echo ($placeofincident == 'Isabelita') ? 'selected' : ''; ?>>Isabelita</option>
                <option value="Kabayanan" <?php echo ($placeofincident == 'Kabayanan') ? 'selected' : ''; ?>>Kabayanan</option>
                <option value="Little Baguio" <?php echo ($placeofincident == 'Little Baguio') ? 'selected' : ''; ?>>Little Baguio</option>
                <option value="Maytunas" <?php echo ($placeofincident == 'Maytunas') ? 'selected' : ''; ?>>Maytunas</option>
                <option value="Onse" <?php echo ($placeofincident == 'Onse') ? 'selected' : ''; ?>>Onse</option>
                <option value="Pasadena" <?php echo ($placeofincident == 'Pasadena') ? 'selected' : ''; ?>>Pasadena</option>
                <option value="Pedro Cruz" <?php echo ($placeofincident == 'Pedro Cruz') ? 'selected' : ''; ?>>Pedro Cruz</option>
                <option value="Progreso" <?php echo ($placeofincident == 'Progreso') ? 'selected' : ''; ?>>Progreso</option>
                <option value="Rivera" <?php echo ($placeofincident == 'Rivera') ? 'selected' : ''; ?>>Rivera</option>
                <option value="Salapan" <?php echo ($placeofincident == 'Salapan') ? 'selected' : ''; ?>>Salapan</option>
                <option value="Saint Joseph" <?php echo ($placeofincident == 'Saint Joseph') ? 'selected' : ''; ?>>Saint Joseph</option>
                <option value="San Perfecto" <?php echo ($placeofincident == 'San Perfecto') ? 'selected' : ''; ?>>San Perfecto</option>
                <option value="Santa Lucia" <?php echo ($placeofincident == 'Santa Lucia') ? 'selected' : ''; ?>>Santa Lucia</option>
                <option value="Tibagan" <?php echo ($placeofincident == 'Tibagan') ? 'selected' : ''; ?>>Tibagan</option>
                <option value="West Crame" <?php echo ($placeofincident == 'West Crame') ? 'selected' : ''; ?>>West Crame</option>
            </select>
        </div>

        <button type="submit">Submit Report</button>

        <!-- Show success message after form submission -->
        <?php if ($submitted): ?>
            <div class="success-popup">
                <strong>Success!</strong> Your report has been successfully submitted.
            </div>
        <?php endif; ?>
    </form>
</body>
</html>
