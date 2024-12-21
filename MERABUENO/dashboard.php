<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pawtection | Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  
</head>
<body>
 
    <div class="menu-bar">
       
        <button class="menu-btn"><i class="fas fa-bars"></i></button>
 
        <div class="menu-header">
            Dashboard
        </div>
 
        <button class="menu-btn" id="logoutBtn">Logout</button>
    </div>

    <h2 class="center-title">Centralized Data-Driven for San Juan City ABC Animal Bite Center with Prescriptive Analytics</h2>
 
    <div class="dashboard-container">
        <div class="card-container">
 
           
            <div class="card" id="incidentReportBtn">
                <div class="card-icon"><i class="fas fa-pen"></i></div>
                <p class="card-label">Incident Report</p>
            </div>
           
            <div class="card" id="dataBtn">
                <div class="card-icon"><i class="fas fa-chart-bar"></i></div>
                <p class="card-label">Data</p>
            </div>
           
            <div class="card" id="treatmentBtn">
                <div class="card-icon"><i class="fas fa-heart"></i></div>
                <p class="card-label">Treatment</p>
            </div>
           
            <div class="card" id="profileBtn">
                <div class="card-icon"><i class="fas fa-user"></i></div>
                <p class="card-label">Profiles</p>
            </div>
 
        </div>
    </div>
    
</div>
 
    <script>
        document.getElementById('incidentReportBtn').addEventListener('click', function() {
            window.location.href = 'incidentreport.php';
        });
 
        document.getElementById('dataBtn').addEventListener('click', function() {
            window.location.href = 'data.php';
        });
 
        document.getElementById('treatmentBtn').addEventListener('click', function() {
            window.location.href = 'treatment.php';
        });
 
        document.getElementById('profileBtn').addEventListener('click', function() {
            window.location.href = 'profiles.php';
        });
 
        document.getElementById('logoutBtn').addEventListener('click', function() {
            window.location.href = 'welcome.php';
        });
    </script>
</body>
</html>