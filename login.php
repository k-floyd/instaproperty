<?php
include 'access.php';
// if user attempts to submit login form
if (isset($_POST) and isset($_GET) and $_GET['attempt'] === 'true') {
    # attempt to match given user and password with existing accounts
    $success = loginUser(strtolower($_POST['userName']), $_POST['userPassword']);
} 
// if user hasn't attempted, set the variable to false
else {
    $success = False;
}

// if the user is logged in, redirect them to their personal dashboard based on their account type
if(isset($_SESSION['f_name'])) {
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
<title>Login | InstaProperty</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="styles.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<script src="script.js"></script>


<body>

    <!-- header -->
    <div class="header">
        <h1>InstaProperty</h1>
        <?php
            echo "<div class='login_signup'><h2><a href='login.php'>Login</a></h2> | <h2><a href='index.php#signup_form'>Sign Up</a></h2></div>";
        ?>
    </div>

    <!-- main container -->
    <div class="container">

        <h2>Login</h2>

        <form class="login-form" name="login-form" onsubmit="return validateLoginForm()" method="POST" action="login.php?attempt=true">
            <?php
                // if this is a redirect from the signup page, show success message
                if(isset($_GET) and $_GET['signup'] == 'true') {
                    echo "<div class='successAlert'>You've successfully created an account! Please use your login details below to continue.</div>";
                }
            ?>

            <!-- displays an error message if needed -->
            <div id="errorMsg">
                <?php
                    // if an attempt to log in was made and an error is returned
                    if (isset($_POST) and isset($_GET) and $_GET['attempt'] === 'true') {
                        if($success[1] == False) {
                            // show the error here in red text
                            echo $success[0];
                        } else {
                            // otherwise save information to session cookie
                            // and redirect to dashboard if log in was successful
                            session_start();
                            $_SESSION["userID"] = $success[4];
                            $_SESSION["userName"] = $success[0];
                            $_SESSION["f_name"] = $success[1];
                            $_SESSION["l_name"] = $success[2];
                            $_SESSION["accountType"] = $success[3];

                            if($_SESSION["accountType"] === 'S') {
                                header('Location: sellerDashboard.php');
                            } else {
                                header('Location: buyerDashboard.php');
                            }
                            
                        }
                    }
                ?>
            </div>
            
            <label for="username"><b>Username</b></label>
            <input type="text" name="userName" id="userName" placeholder="Enter Username" required>

            <label for="pwd"><b>Password</b></label>
            <input type="password" name="userPassword" id="userPassword" placeholder="Enter Password" required>

            <input class="btn" type="submit" value="Login">
        </form>

    </div>

    <!-- footer -->
    <div class="footer">
        <p>All Rights Reserved. 2023</p>
    </div>
</body>

</html>