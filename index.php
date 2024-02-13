<?php
include 'access.php';

// initialize tables if they don't already exist
initializeTables();

// if user attempts to submit signup form
if(isset($_POST) and isset($_GET) and $_GET['attempt'] === 'true') {

    // encrypt password
    $p_hash = password_hash($_POST['userPassword'], PASSWORD_DEFAULT);

    // attempt to insert new user into table
    $success = insertUser($_POST['firstName'], 
                $_POST['lastName'], 
                $_POST['email'], 
                strtolower($_POST['userName']), 
                $p_hash, 
                $_POST['acctType'][0]);
} 
// if user hasn't attempted, set the variable to false
else {
    $sucess = False;
}

// get session cookie variables
session_start();

// if a logout call was made, unset session cookie
if(isset($_GET) and $_GET['logout'] === 'true' and isset($_SESSION['f_name'])) {
    session_unset();
}

// if the user is logged in, redirect them to their personal dashboard
if(isset($_SESSION['f_name']) and isset($_SESSION['accountType'])) {
    if($_SESSION["accountType"] == "S") {
        header('Location: sellerDashboard.php');
    } else {
        header('Location: buyerDashboard.php');
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<title>Home | InstaProperty</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="styles.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<script src="script.js"></script>


<body>

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
                    <p><a href='sellerDashboard.php'>View Dashboard</a></p>
                    <p><a href='wishlist.php'>Wishlist</a></p>
                    <p><a href='index.php?logout=true'>Log out</a></p>
                </div>
                </div>";
            }

            // if they're not logged in, show links to login and signup forms
            else {
                echo "<div class='login_signup'><h2><a href='login.php'>Login</a></h2> | <h2><a href='index.php#signup_form'>Sign Up</a></h2></div>";
            }
        ?>
    </div>

    <!-- main container -->
    <div class="container">
        <img src="properties.png" alt="collage of properties">

        <p>Finding your perfect property or selling your cherished home has never been easier. At InstaProperty, we
            understand that buying or selling a property is more than just a transaction; it's a significant moment in
            your life. That's why we've crafted a seamless online portal that caters to the needs of both property
            buyers and sellers, making the entire process as smooth as the keys turning in your new front door.</p>

        <p><span class="bold">For Property Buyers:</span><br>Discover a world of possibilities as you browse through a diverse range of
            properties meticulously curated to suit every lifestyle. Whether you're searching for a cozy apartment, a
            spacious family home, or an investment opportunity, InstaProperty has the perfect match for you. Our
            intuitive search features and detailed property listings ensure you find your dream home with ease.</p>

        <p><span class="bold">For Property Sellers:</span><br>
            Say goodbye to the hassle of selling your property. InstaProperty empowers sellers with a user-friendly
            platform to showcase their homes to a vast audience of potential buyers. Our advanced marketing tools and
            expert guidance help you list, market, and sell your property efficiently. Maximize your property's exposure
            and unlock its full potential.</p>

        <?php
            // if they are logged in and for some reason haven't been redirected to dashboard
            if(isset($_SESSION['f_name'])) {
                // provide a link to go to the dashboard and hide sign-up form
                echo '<button onclick="location.href = '."'sellerDashboard.php'".';" class="btn">See Dashboard</button>';
                echo '<form id="signup_form" class="signup-form hide" name="signup-form" onsubmit="return validateSignUpForm()" method="POST" action="index.php?attempt=true">';
            }
            // default case: show sign-up form
            else {
                echo "<p>Ready to make your move? Sign up now and embark on the exciting journey of buying or selling your property
                with InstaProperty! Your dream home awaits.</p>";
                echo '<form id="signup_form" class="signup-form" name="signup-form" onsubmit="return validateSignUpForm()" method="POST" action="index.php?attempt=true">';
            }
        
        ?>

            <!-- displays an error message if needed -->
            <div id="errorMsg">
                <?php
                    // if an attempt to sign up was made and an error is returned
                    if(isset($_POST) and isset($_GET) and $_GET['attempt'] === 'true') {
                        if($success[1] == False) {
                            // show the error here in red text
                            echo $success[0];
                        } elseif (isset($_POST) and $success[1] == True) {
                            // otherwise redirect to login page if signup was successful
                            header('Location: login.php?signup=true');
                        }
                    }
                ?>
            </div>

            <!-- account information -->
            <p class="bold">Account Information</p>
            <input type="text" name="firstName" id="firstName" placeholder="First Name" required>
            <input type="text" name="lastName" id="lastName" placeholder="Last Name" required>
            <input type="text" name="email" id="email" placeholder="Enter Email" required>
            <select id="acctType" name="acctType">
                <option name="acctType" value="Seller">Seller</option>
                <option name="acctType" value="Buyer">Buyer</option>
                <option name="acctType" value="Admin">Admin</option>
            </select>

            <!-- login information -->
            <p class="bold">Login Information</p>
            <input type="text" name="userName" id="userName" placeholder="Create Username" required>
            <input type="password" name="userPassword" id="userPassword" placeholder="Enter Password" required>
            <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password" required>

            <!-- privacy information -->
            <label id="checkbox-text">
                <input type="checkbox" checked="checked" name="remember" id="checkbox" style="margin-bottom:15px" required>I
                accept the <a href="#">Terms of Use</a> and <a href="#">Privacy Policy</a>
            </label>

            <input class="btn" type="submit" value="Sign Up">
        </form>

    </div>

    <!-- footer -->
    <div class="footer">
        <p>All Rights Reserved. 2023</p>
    </div>
</body>

</html>