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

// if item added to wishlist, save the id to wishlist cookie
if(isset($_GET) and isset($_GET['id'])) {
    $_SESSION['wishlist'] = $_SESSION['wishlist']. $_GET['id']. ', ';
}

// get all available properties in database
$properties = getAllProperties();

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

    <!-- header -->
    <div class="header">
        <h1>InstaProperty</h1>
        <?php
            // if user is logged in
            if(isset($_SESSION['f_name'])) {
                // show their name and dropdown to navigate to dashboard, wishlist, or logout
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
        <h2>Welcome to your <?= $acctType ?> Dashboard, <?= $_SESSION['f_name'] ?></h2>
        <?php
            // if this is a redirect from adding an item to a wishlist, show success message
            if(isset($_GET) and isset($_GET['id'])) {
                echo "<div class='successAlert'>You've successfully added a property to your wishlist!</div>";
            }
        ?>

        <!-- search bar -->
        <input type="text" id="searchInput" placeholder="Search terms...">

        <div class="properties">
            <?php
                // for each property in properties variable
                foreach($properties as $propertyID => $propertyInfo) {
                    $propertyType = "";
                    // convert the single Character to the full named property type
                    if($propertyInfo[3] == 'H') {
                        $propertyType = 'House ';
                    } elseif($propertyInfo[3] == 'A') {
                        $propertyType = 'Apartment ';
                    } elseif($propertyInfo[3] == 'C') {
                        $propertyType = 'Condo ';
                    } else {
                        $propertyType = 'Townhouse ';
                    }

                    // create card div to store information
                    echo '<div class="card" onclick="return openModal(this)" data-card-keywords="'.$propertyInfo[1].','.$propertyInfo[2].','.substr($propertyType,0, strlen($propertyType)-1).','.$propertyInfo[4].'"><div class="top">';
                    echo '<img src="'.$propertyInfo[0].'" alt="'.strval($propertyID).' Property Image">';
                    echo '<h3>'.$propertyInfo[1].'</h3>
                            <div class="price_type">
                                <h4>$'.strval($propertyInfo[2]).'</h4>
                                <p>';

                    echo $propertyType;

                    // determine whether text should be for sale/rent or off market
                    if($propertyInfo[4] == "Sale" or $propertyInfo[4] == "Rent"){
                        echo 'for '. $propertyInfo[4];
                    } else {
                        echo '| '. $propertyInfo[4];
                    }
                    echo '</p></div></div>';

                    echo '<div class="bottom">
                            <ul>
                                <li><span class="bold">Bds:</span> '.strval($propertyInfo[5]).'</li>
                                <li><span class="bold">Ba:</span> '.strval($propertyInfo[6]).'</li>
                                <li><span class="bold">Sqft:</span> '.strval($propertyInfo[7]).'</li>
                            </ul>
                            <div class="address"><span class="bold">Address:</span> '.$propertyInfo[8].'</div>';
                    // add button to save to wishlist
                    echo '<button onclick="location.href = '."'buyerDashboard.php?id=".$propertyID."'".';" class="btn">Add to Wishlist</button>
                        </div>
                    </div>';

                }
            ?>

        </div>

        <!-- Modal container -->
        <div id="myModal" class="modal hide">
            <div class="modal-content">

                <div id="modalCard" class=".modal-content">
                    <div class="top">
                        <img id="modalImage" src="#" alt="propertyImage">
                        <h3 id="modalTitle"></h3>
                        <div class="price_type">
                            <h4 id="modalPrice">$</h4>
                            <p id="modalType"></p>
                        </div>
                    </div>
                    <div class="bottom">
                        <ul>
                            <li id="modalBds"><span class="bold">Bds:</span> </li>
                            <li id="modalBa"><span class="bold">Ba:</span> </li>
                            <li id="modalSqft"><span class="bold">Sqft:</span> </li>
                        </ul>
                        <div class="address" id="modalAddr"></div>
                    </div>
                </div>

                <span class="close" onclick="closeModal()">&times;</span>
            </div>
        </div>

    </div>

    <!-- footer -->
    <div class="footer">
        <p>All Rights Reserved. 2023</p>
    </div>
</body>

</html>