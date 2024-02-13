<?php
include 'access.php';

session_start();

// get wishlist items
if(isset($_SESSION['wishlist'])) {
    $property_ids = explode(",", $_SESSION['wishlist']);
    $properties = getWishlistProperties($property_ids);
} else {
    $properties = [];
}

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
                    <p><a href='buyerDashboard.php'>View Dashboard</a></p>
                    <p><a href='wishlist.php'>Wishlist</a></p>
                    <p><a href='index.php?logout=true'>Log out</a></p>
                </div>
                </div>";
            }
        ?>
    </div>

    <!-- main container -->
    <div class="container">
        <h2>Your Wishlist, <?= $_SESSION['f_name'] ?></h2>
        <?php
            if(count($properties) == 2 and $properties[1] == False) {
                echo "<div class='errorMsg'>".$properties[0]."</div>";
            }
        ?>

        <div class="properties">

            <?php
                foreach($properties as $propertyID => $propertyInfo) {
                    echo '<div class="card"><div class="top">';
                    echo '<img src="'.$propertyInfo[0].'" alt="'.strval($propertyID).' Property Image">';
                    echo '<h3>'.$propertyInfo[1].'</h3>
                            <div class="price_type">
                                <h4>$'.strval($propertyInfo[2]).'</h4>
                                <p>';

                    if($propertyInfo[3] == 'H') {
                        echo 'House ';
                    } elseif($propertyInfo[3] == 'A') {
                        echo 'Apartment ';
                    } elseif($propertyInfo[3] == 'C') {
                        echo 'Condo ';
                    } else {
                        echo 'Townhouse ';
                    }

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
                    echo '</div></div>';

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