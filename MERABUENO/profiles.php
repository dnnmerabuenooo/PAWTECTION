<?php
include 'db_connect.php';

// Handle deletion of a case if requested
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']); // Validate input

    // Use prepared statements to prevent SQL injection
    $delete_stmt = $conn->prepare("DELETE FROM cases WHERE id = ?");
    $delete_stmt->bind_param("i", $delete_id);

    if ($delete_stmt->execute()) {
        echo "<script>alert('Case deleted successfully'); window.location='profiles.php';</script>";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
    $delete_stmt->close();
}

// Handle update of a case if form is submitted
if (isset($_POST['update'])) {
    $id = intval($_POST['id']); // Validate input
    $fullname = $_POST['fullname'];
    $address = $_POST['address'];
    $age = intval($_POST['age']);
    $contactnumber = $_POST['contactnumber'];
    $emailadd = $_POST['emailadd'];
    $animaltype = $_POST['animaltype'];
    $dateofbite = $_POST['dateofbite'];
    $timeofbite = $_POST['timeofbite'];
    $placeofincident = $_POST['placeofincident'];

    // Use prepared statements to prevent SQL injection
    $update_stmt = $conn->prepare("UPDATE cases SET fullname = ?, address = ?, age = ?, contactnumber = ?, emailadd = ?, animaltype = ?, dateofbite = ?, timeofbite = ?, placeofincident = ? WHERE id = ?");
    $update_stmt->bind_param("ssissssssi", $fullname, $address, $age, $contactnumber, $emailadd, $animaltype, $dateofbite, $timeofbite, $placeofincident, $id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Case updated successfully'); window.location='profiles.php';</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $update_stmt->close();
}

// Fetch all cases from the database
$sql = "SELECT * FROM cases ORDER BY fullname ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reported Cases</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #8b0000; /* Set the background color to red */
        }

        .container {
            max-width: 900px; /* Adjust container width */
            margin: 20px auto;
            padding: 20px;
            background: none; /* No background color for the main container */
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: 4px solid #F7C958;
        }

        h1 {
            font-size: 50px;
            font-weight: bold;
            color: #DD8523;
            -webkit-text-stroke: 1px #F7C958;
            text-align: center;
            border-radius: 2px;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.5);
        }

        .case {
            border: 1px solid #ddd;
            border-radius: 20px;
            padding: 15px;
            margin-bottom: 20px;
            background: white; /* Each patient's container is white */
        }

        .case h2 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #333;
        }

        .case p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }

        .button-container {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        button, a {
            text-decoration: none;
            font-size: 14px;
            padding: 8px 12px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }

        .update-btn {
            background-color: #007bff;
            color: #fff;
            border: 1px solid #007bff;
            transition: background-color 0.3s ease;
        }

        .update-btn:hover {
            background-color: #0056b3;
        }

        .delete-btn {
            background-color: #dc3545;
            color: #fff;
            border: 1px solid #dc3545;
            transition: background-color 0.3s ease;
        }

        .delete-btn:hover {
            background-color: #a71d2a;
        }

        #updateFormContainer {
            margin-top: 20px;
            padding: 20px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        #updateFormContainer h3 {
            font-size: 18px;
            margin-bottom: 15px;
            color: #444;
        }

        #updateFormContainer form input {
            width: calc(100% - 20px);
            margin-bottom: 10px;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        #updateFormContainer form button {
            width: 100px;
            margin-bottom: 10px;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        #updateFormContainer form input:focus,
        #updateFormContainer form button:focus {
            outline: none;
            border-color: #007bff;
        }

        #updateFormContainer form button {
            background-color: #0056b3;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        #updateFormContainer form button:hover {
            background-color: #0056b3;
        }

        /* Back button style */
        .back-btn {
            background-color:rgb(20, 21, 20);
            color: white;
            font-size: 14px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
            margin-top: 20px;
            margin-left: 10px;
        }

        .back-btn:hover {
            background-color:rgb(0, 0, 0);
        }

        @media (max-width: 600px) {
            .container {
                padding: 10px;
            }

            button, a {
                font-size: 12px;
                padding: 6px 10px;
            }

            #updateFormContainer form input,
            #updateFormContainer form button {
                font-size: 12px;
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <!-- Back button outside the container -->
    <a href="dashboard.php" class="back-btn">Back to Dashboard</a>

    <div class="container">
        <h1>Reported Cases</h1>

        <div class="cases-list">
            <?php
            // Check if there are any reported cases
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='case'>";
                    echo "<h2>" . htmlspecialchars($row['fullname']) . "</h2>";
                    echo "<p><strong>Address:</strong> " . htmlspecialchars($row['address']) . "</p>";
                    echo "<p><strong>Age:</strong> " . htmlspecialchars($row['age']) . "</p>";
                    echo "<p><strong>Contact Number:</strong> " . htmlspecialchars($row['contactnumber']) . "</p>";
                    echo "<p><strong>Email Address:</strong> " . htmlspecialchars($row['emailadd']) . "</p>";
                    echo "<p><strong>Animal Type:</strong> " . htmlspecialchars($row['animaltype']) . "</p>";
                    echo "<p><strong>Date of Bite:</strong> " . htmlspecialchars($row['dateofbite']) . "</p>";
                    echo "<p><strong>Time of Bite:</strong> " . htmlspecialchars($row['timeofbite']) . "</p>";
                    echo "<p><strong>Place of Incident:</strong> " . htmlspecialchars($row['placeofincident']) . "</p>";

                    echo "<div class='button-container'>";
                    echo "<a href='profiles.php?delete_id=" . $row['id'] . "' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this case?\")'>Delete</a>";
                    echo "<button onclick='showUpdateForm(" . $row['id'] . ")' class='update-btn'>Update</button>";
                    echo "</div>";

                    echo "</div>";
                }
            } else {
                echo "<p>No reported cases found.</p>";
            }
            ?>
        </div>

        <!-- Update form section -->
        <div id="updateFormContainer" style="display:none;">
            <h3>Update Case Details</h3>
            <form action="" method="POST">
                <input type="hidden" name="id" id="updateId">
                <input type="text" name="fullname" id="updateFullname" placeholder="Full Name" required>
                <input type="text" name="address" id="updateAddress" placeholder="Address" required>
                <input type="number" name="age" id="updateAge" placeholder="Age" required>
                <input type="text" name="contactnumber" id="updateContactNumber" placeholder="Contact Number" required>
                <input type="email" name="emailadd" id="updateEmailAdd" placeholder="Email Address" required>
                <input type="text" name="animaltype" id="updateAnimalType" placeholder="Animal Type" required>
                <input type="date" name="dateofbite" id="updateDateOfBite" placeholder="Date of Bite" required>
                <input type="time" name="timeofbite" id="updateTimeOfBite" placeholder="Time of Bite" required>
                <input type="text" name="placeofincident" id="updatePlaceOfIncident" placeholder="Place of Incident" required>
                <button type="submit" name="update">Update Case</button>
            </form>
        </div>
    </div>

    <script>
        function showUpdateForm(id) {
            var updateFormContainer = document.getElementById('updateFormContainer');
            updateFormContainer.style.display = 'block';
            var updateId = document.getElementById('updateId');
            var updateFullname = document.getElementById('updateFullname');
            var updateAddress = document.getElementById('updateAddress');
            var updateAge = document.getElementById('updateAge');
            var updateContactNumber = document.getElementById('updateContactNumber');
            var updateEmailAdd = document.getElementById('updateEmailAdd');
            var updateAnimalType = document.getElementById('updateAnimalType');
            var updateDateOfBite = document.getElementById('updateDateOfBite');
            var updateTimeOfBite = document.getElementById('updateTimeOfBite');
            var updatePlaceOfIncident = document.getElementById('updatePlaceOfIncident');

            // Set the form fields with the current case data
            updateId.value = id;

            // Optional: Use AJAX to fetch case details from the database and populate the form.
        }
    </script>
</body>
</html>
