<?php
include 'access.php';

session_start();
$acctType = "";

// save account type to variable
if($_SESSION['accountType'] === 'S') {
    $acctType = "Seller";
} elseif ($_SESSION['accountType'] === 'B') {
    $acctType = "Buyer";
} else {
    $acctType = "Admin";
}

// get properties connected to this seller in database
$properties = getProperties($_SESSION['userID']);

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
                    <p><a href='sellerDashboard.php'>View Dashboard</a></p>
                    <p><a href='index.php?logout=true'>Log out</a></p>
                </div>
                </div>";
            }
        ?>
    </div>

    <!-- main container -->
    <div class="container">
        <h2>Welcome to your <?= $acctType ?> Dashboard, <?= $_SESSION['f_name'] ?></h2>
        <?php
            // if this is a redirect from the add property page, show success message
            if(isset($_GET) and $_GET['addedProperty'] == 'true') {
                echo "<div class='successAlert'>You've successfully added a property!</div>";
            }
            if(count($properties) == 2 and $properties[1] == False) {
                echo "<div class='errorMsg'>".$properties[0]."</div>";
            }
        ?>

        <div class="properties">

            <!-- add property prompt -->
            <div class="card">
                <div class="top">
                    <div class="addProperty" onclick="location.href = 'addProperty.php';">+</div>
                </div>
                <div class="bottom">
                    <h3>Add a Property</h3>
                </div>
            </div>

            <?php
                // for each property in properties varibale
                foreach($properties as $propertyID => $propertyInfo) {
                    echo '<div class="card"><div class="top">';
                    echo '<img src="'.$propertyInfo[0].'" alt="'.strval($propertyID).' Property Image">';
                    echo '<h3>'.$propertyInfo[1].'</h3>
                            <div class="price_type">
                                <h4>$'.strval($propertyInfo[2]).'</h4>
                                <p>';

                    // convert the single Character to the full named property type
                    if($propertyInfo[3] == 'H') {
                        echo 'House ';
                    } elseif($propertyInfo[3] == 'A') {
                        echo 'Apartment ';
                    } elseif($propertyInfo[3] == 'C') {
                        echo 'Condo ';
                    } else {
                        echo 'Townhouse ';
                    }

                    // determine whether text should be for sale/rent or off market
                    if($propertyInfo[4] == "Sale" or $propertyInfo[4] == "Rent"){
                        echo 'for '. $propertyInfo[4];
                    } else {
                        echo $propertyInfo[4];
                    }
                    echo '</p></div></div>';

                    echo '<div class="bottom">
                            <ul>
                                <li><span class="bold">Bds:</span> '.strval($propertyInfo[5]).'</li>
                                <li><span class="bold">Ba:</span> '.strval($propertyInfo[6]).'</li>
                                <li><span class="bold">Sqft:</span> '.strval($propertyInfo[7]).'</li>
                            </ul>
                            <div class="address"><span class="bold">Address:</span> '.$propertyInfo[8].'</div>';
                    echo '
                        </div>
                    </div>';

                }
            ?>

        </div>

    </div>

    <!-- footer -->
    <div class="footer">
        <p>All Rights Reserved. 2023</p>
    </div>
</body>

</html>