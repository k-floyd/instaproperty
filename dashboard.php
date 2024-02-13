<?php
include 'access.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<title>InstaProperty | Project 4 - Final</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="styles.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<script src="script.js"></script>

<body>

    <div class="alert">

    </div>

    <!-- header -->
    <div class="header">
        <h1>InstaProperty</h1>
        <?php
            // if user is logged in
            if(isset($_SESSION['f_name'])) {
                // show their name and dropdown to navigate to dashboard or logout
                echo "<div class='dropdown'>
                <h2>".$_SESSION['f_name']. " ". $_SESSION['l_name']. "</h2>
                <i id='hoverIcon' class='arrow down'></i>
                <div id='dropdownContent' class='dropdownContent hide'>
                    <p><a href='dashboard.php'>View Dashboard</a></p>
                    <p><a href='index.php?logout=true'>Log out</a></p>
                </div>
                </div>";
            }
        ?>
    </div>

    <!-- main container -->
    <div class="container">
        <h1>Dashboard</h1>
        <h2>Welcome <?= $_SESSION['f_name'] ?></h2>
    </div>

    <!-- footer -->
    <div class="footer">
        <p>All Rights Reserved. 2023</p>
    </div>
</body>

</html>