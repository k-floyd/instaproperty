<?php 
include 'access.php';
session_start();
// if user attempts to submit property form
if (isset($_POST) and isset($_GET) and $_GET['attempt'] === 'true') {
    # attempt to add property to database
    $success = insertProperty($_POST['p_name'], 
    $_POST['p_address'], 
    $_POST['p_address_city'], 
    $_POST['p_address_state'], 
    $_POST['p_address_zc'], 
    $_POST['p_price'], 
    $_POST['p_type'], 
    $_POST['p_status'], 
    $_POST['p_bds'], 
    $_POST['p_ba'], 
    $_POST['p_sqft'], 
    $_POST['p_image'],
    $_SESSION['userID']);
} 
// if user hasn't attempted, set the variable to false
else {
    $success = False;
}
?>

<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<title>Add a Property | InstaProperty</title>
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
                    <p><a href='index.php?logout=true'>Log out</a></p>
                </div>
                </div>";
            }
        ?>
    </div>

    <!-- main container -->
    <div class="container">
        <h1>Add a Property</h1>
        <br>
        <form class="property_container" name="property-form" onsubmit="return validatePropertyForm()" method="POST" action="addProperty.php?attempt=true">

            <!-- displays an error message if needed -->
            <div id="errorMsg">
                <?php
                    // if an attempt to add a property was made and an error is returned
                    if(isset($_POST) and isset($_GET) and $_GET['attempt'] === 'true') {
                        if($success[1] == False) {
                            // show the error here in red text
                            echo $success[0];
                        } elseif (isset($_POST) and $success[1] == True) {
                            // if the insert is successful, redirect them to their personal dashboard
                            header('Location: sellerDashboard.php?addedProperty=true');
                        }
                    }
                ?>
            </div>

            <!-- account information -->
            <p class="bold">Property Information</p>
            <input type="text" name="p_name" id="p_name" placeholder="Property Name" required>

            <p>Address</p>
            <input type="text" name="p_address" id="p_address" placeholder="1234 Street Rd." required>
            <div class="address_info">
                <input type="text" name="p_address_city" id="p_address_city" placeholder="City" size="20" required>
                <select id="p_address_state" name="p_address_state" size="1" required>
                    <option value="" selected>State</option>
                    <option value="GA">GA</option>
                    <option value="TN">TN</option>
                    <option value="AL">AL</option>
                    <option value="FL">FL</option>
                    <option value="SC">SC</option>
                </select>
                <input type="text" name="p_address_zc" id="p_address_zc" placeholder="Zipcode" size="5" required>
            </div>
            
            <p>Price</p>
            <input type="number" name="p_price" id="p_price" placeholder="Price" min="0" size="9" required>

            <!-- login information -->
            <p class="bold">Property Details</p>


            <div class="details_selects">
                <select id="p_type" name="p_type" size="1" required>
                    <option value="" selected>Listing Type</option>
                    <option value="House">House</option>
                    <option value="Apartment">Apartment</option>
                    <option value="Condo">Condo</option>
                    <option value="Townhouse">Townhouse</option>
                </select>
    
                <select id="p_status" name="p_status" size="1" required>
                    <option value="" selected>Listing Status</option>
                    <option value="Rent">For Rent</option>
                    <option value="Sale">For Sale</option>
                    <option value="Off_Market">Off Market</option>
                </select>
            </div>

            <div class="details">
                <input type="number" name="p_bds" id="p_bds" placeholder="# of Beds" min="0" max="100"  required>
                <input type="number" name="p_ba" id="p_ba" placeholder="# of Baths" min="0" max="100" required>
                <input type="number" name="p_sqft" id="p_sqft" placeholder="Sqr. Feet" min="0">
            </div>
            

            <!-- privacy information -->
            <p class="bold">Photo of Property</p>
            <input type="text" name="p_image" id="p_image" placeholder="Enter property image as an absolute URL of PNG or JPG type (has to be CODD Link)" required>

            <input class="btn" type="submit" value="Add Property">
        </form>
    </div>

    <!-- footer -->
    <div class="footer">
        <p>All Rights Reserved. 2023</p>
    </div>
</body>

</html>