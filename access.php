<?php
    function runQuery($sql) {
        $host = "localhost";
        $user = "rmuse1";
        $pass = "rmuse1";
        $dbname = "rmuse1";

        $conn = new mysqli($host, $user, $pass, $dbname);

        if($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $conn->query($sql);

        if($result === FALSE) {
            $conn->close();
            return ["There was an error on our server. Please try again.", False];
        }

        $conn->close();

        return $result;
    }

    function initializeTables(){
        // if tables don't exist:
        $create_users = "CREATE TABLE IF NOT EXISTS Users (
            userID INT UNSIGNED AUTO_INCREMENT,
            f_name VARCHAR(30) NOT NULL,
            l_name VARCHAR(30) NOT NULL,
            email VARCHAR(70) NOT NULL,
            userName VARCHAR(30) NOT NULL,
            userPassword VARCHAR(255) NOT NULL,
            accountType CHAR(1) NOT NULL,
            PRIMARY KEY(userID)
        )";

        $create_properties = "CREATE TABLE IF NOT EXISTS Properties (
            propertyID INT UNSIGNED AUTO_INCREMENT,
            p_img VARCHAR(150),
            p_name VARCHAR(30) NOT NULL,
            p_address VARCHAR(100) NOT NULL,
            p_price INT UNSIGNED NOT NULL,
            p_beds INT UNSIGNED NOT NULL,
            p_baths FLOAT UNSIGNED NOT NULL,
            p_sqft INT UNSIGNED,
            p_type CHAR(1),
            p_status VARCHAR(30) NOT NULL,
            seller_id INT UNSIGNED NOT NULL,
            buyer_id INT UNSIGNED,
            PRIMARY KEY(propertyID),
            FOREIGN KEY(seller_id) REFERENCES Users(userID),
            FOREIGN KEY(buyer_id) REFERENCES Users(userID)
        )";

        // run create tables commands
        $userReturn = runQuery($create_users);
        $propertyReturn = runQuery($create_properties);

        return [$userReturn, $propertyReturn];
    }
    
    function checkUsernameAvailable($u) {
        $sql = "SELECT * FROM Users WHERE userName = '". $u."'";

        // check if username is already taken
        $res = runQuery($sql);

        if($res->num_rows === 0 ) {
            return True;
        } else {
            return False;
        }
    }

    function checkAddressInTable($address) {
        $sql = "SELECT * FROM Properties WHERE p_address = '". $address. "'";

        // check if address is already taken
        $res = runQuery($sql);

        if($res->num_rows === 0 ) {
            return True;
        } else {
            return False;
        }
    }

    function insertUser($f_name, $l_name, $email, $userName, $encryptedPassword, $account_type) {
        $userAvailable = checkUsernameAvailable($userName);

        // if the user name isn't already claimed
        if($userAvailable) {
            $sql = 'INSERT INTO Users (f_name, l_name, email, userName, userPassword, accountType) VALUES ("'. $f_name.'", "'.$l_name.'", "'.$email.'", "'.$userName.'", "'.$encryptedPassword.'", "'.$account_type.'")';
            // add account
            $insertReturn = runQuery($sql);
            if($insertReturn[1] === False) {
                return $insertReturn;
            }
            return ["You've successfully signed up!", True];
        } else {
            // return error message
            return ["This username is unavailable.", False];
        }
    }

    function loginUser($userName, $password) {
        $sql = "SELECT userID, f_name, l_name, userName, userPassword, accountType FROM Users WHERE userName = '".$userName."'";

        // get user information given username
        $result = runQuery($sql);
        // if error when running query, return error
        if(is_array($result) and count($result) == 2 and $result[1] === False) {
            return $result;
        }

        $row = $result->fetch_assoc();

        // otherwise
        if($result->num_rows > 0) {
            // check that the password matches
            $passwordCorrect = password_verify($password, $row['userPassword']);

            // if it matches, return information
            if($passwordCorrect) {
                return [$row['userName'], $row['f_name'], $row['l_name'], $row['accountType'], $row['userID']];
            } else {
                // otherwise return error
                return ["Incorrect username/password.", False];
            }
        } else {
            // if no user is returned, username doesn't exist
            return ["Username does not exist.", False];
        }

    }

    function insertProperty($p_name, 
                            $p_address, 
                            $p_address_city, 
                            $p_address_state, 
                            $p_address_zc, 
                            $p_price, 
                            $p_type, 
                            $p_status, 
                            $p_bds, 
                            $p_ba, 
                            $p_sqft, 
                            $p_image,
                            $sellerID)
    {
        $address = $p_address.', '.$p_address_city.', '.$p_address_state.', '.$p_address_zc;
        $addressAvailable = checkAddressInTable($address);

        // if the address isn't already in database
        if($addressAvailable) {
            $sql = 'INSERT INTO Properties (p_img, p_name, p_address, p_price, p_beds, p_baths, p_sqft, p_type, p_status, seller_id) VALUES ("'. $p_image.'", "'.$p_name.'", "'.$address.'", "'.$p_price.'", "'.$p_bds.'", "'.$p_ba.'", "'.$p_sqft.'", "'.$p_type[0].'", "'.$p_status.'", "'.$sellerID.'")';

            // add property
            $insertReturn = runQuery($sql);
            
            if($insertReturn[1] === False) {
                return $insertReturn;
            }
            return ["You've successfully added a property!", True];
        } else {
            // return error message
            return ["This property address already exists in our database.", False];
        }
    }

    function getProperties($sellerID) {
        $sql = "SELECT propertyID, p_img, p_name, p_price, p_type, p_status, p_beds, p_baths, p_sqft, p_address FROM Properties where seller_id = '". $sellerID . "'";

        // get property information given seller id
        $result = runQuery($sql);

        // if error when running query, return error
        if(is_array($result) and count($result) == 2 and $result[1] === False) {
            return $result;
        }

        // otherwise
        if($result->num_rows > 0) {
            // return information
            $properties = [];
            while($row = $result->fetch_assoc()) {
                $properties[$row['propertyID']] = [$row['p_img'], $row['p_name'], $row['p_price'], $row['p_type'], $row['p_status'], $row['p_beds'], $row['p_baths'], $row['p_sqft'], $row['p_address']];
            }
            return $properties;
        } else {
            // if no user is returned, username doesn't exist
            return ["This seller has no properties.", False];
        }
    }

    function getAllProperties() {
        $sql = "SELECT propertyID, p_img, p_name, p_price, p_type, p_status, p_beds, p_baths, p_sqft, p_address FROM Properties";

        // get property information given seller id
        $result = runQuery($sql);

        // if error when running query, return error
        if(is_array($result) and count($result) == 2 and $result[1] === False) {
            return $result;
        }

        // otherwise
        if($result->num_rows > 0) {
            // return information
            $properties = [];
            while($row = $result->fetch_assoc()) {
                $properties[$row['propertyID']] = [$row['p_img'], $row['p_name'], $row['p_price'], $row['p_type'], $row['p_status'], $row['p_beds'], $row['p_baths'], $row['p_sqft'], $row['p_address']];
            }
            return $properties;
        } else {
            // if no user is returned, username doesn't exist
            return ["There are no properties to list.", False];
        }
    }

    function getWishlistProperties($propertyID) {

        $properties = [];

        for ($i=0; $i < count($propertyID)-1; $i++) { 
            $sql = "SELECT propertyID, p_img, p_name, p_price, p_type, p_status, p_beds, p_baths, p_sqft, p_address FROM Properties where propertyID = ". $propertyID[$i];
            // get property information given seller id
            $result = runQuery($sql);

            // if error when running query, return error
            if(is_array($result) and count($result) == 2 and $result[1] === False) {
                return $result;
            }

            // return information
            while($row = $result->fetch_assoc()) {
                $properties[$row['propertyID']] = [$row['p_img'], $row['p_name'], $row['p_price'], $row['p_type'], $row['p_status'], $row['p_beds'], $row['p_baths'], $row['p_sqft'], $row['p_address']];
            }
        }

        return $properties;
    }
?>