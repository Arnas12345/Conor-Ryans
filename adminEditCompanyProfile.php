

<html>
    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/profile_user.css?v=<?php echo time(); ?>">
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico"/>

        <meta content="width=device-width, initial-scale=1" name="viewport" />

        <title>Loop : Admin</title>
    </head>
    
    <body>

        <?php 

            include ("validateLoggedIn.php");
                

            function getCompanyData($cID) {
                include ("serverConfig.php");
                $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
                if ($conn -> connect_error) {
                    die("Connection failed:" .$conn -> connect_error);
                }

                $sql = "select * from companies where companyID =\"{$cID}%\";";
                $result = $conn -> query($sql);
                $conn->close();

                return $result->fetch_assoc();
            }

            if(isset($_SESSION['user'])) include("headerTemplate.html");
            else include("companyTemplate.html");
            $companyID = $_GET["companyID"];
            $row = getCompanyData($companyID);
            print "<h1 class='page-heading'>{$row['companyName']}</h1>";
                
        ?>

        <hr>

        <div class = "profile-container" >

            <div class = "profileImage" >
                <?php

                    $row = getCompanyData($companyID);
                    $profileImage = null;

                    if (isset($row['profileImage'])) $profileImage = $row['profileImage'];

                    if($profileImage === null) {
                        print '<img src = "images/blank-profile-picture.png" alt="profile image" height="25%" width="18%" style="min-width:160px; min-height:160px; border-radius:50%;" >';
                    }
                    else {
                        print "<img src = 'profileImages/{$profileImage}' alt='profile image' height='25%' width='18%' style='min-width:160px; min-height:160px; border-radius:50%; object-fit: cover; overflow:hidden;' >";
                    }

                ?>
            </div>
            <div class="changeProfileImage"><?php
                    $companyID = $_GET["companyID"];
                    print "<form method='post' action='adminEditCompanyProfile.php?companyID={$companyID}' enctype='multipart/form-data'>";?>
                    <input class="file" type="file" name="image">
                    <input class="edit" type="submit" name="submitImage" value="Upload">
                </form>
            </div>
        </div>
        <div class = "description-container">
            <div class = "description-heading">
                <H1 style = "text-align: center;">Description</H1>
            </div>
            <div class = "bio-description">
                    <?php
                        $companyID = $_GET["companyID"];
                        print "<form method='post' action='adminEditCompanyProfile.php?companyID={$companyID}'>";
                        include ("serverConfig.php");
                        $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
                        if ($conn -> connect_error) {
                            die("Connection failed:" .$conn -> connect_error);
                        }

                        $companyID = $_GET['companyID'];

                        if(isset($_POST['submitImage']) && $_FILES["image"]["name"]) {
                            
                            $profileImageName = time() . "_" . $_FILES["image"]["name"];
                            $target = "profileImages/" . $profileImageName;

                            str_replace(" ", "", $target);
                            if(copy($_FILES["image"]["tmp_name"], $target)) {
                                $userProfileImage ="UPDATE companies 
                                                    SET profileImage='$profileImageName'
                                                    WHERE companyID={$companyID};";
                            }
                            
                            if($conn->query($userProfileImage)) {
                                header( "Location: organizationProfile.php" );
                            }
                        }

                        //Sets the description if one exists
                        $description = '';
                        if(isset($_COOKIE['companyDescription'])) $description = $_COOKIE['companyDescription'];
                        print "<textarea id='description' rows='5' cols='60' name='description'>{$description}</textarea><br>";

                        //Sets the address if one exists
                        print "<h3>Company Address:</h3>";
                        $address = '';
                        if(isset($_COOKIE['address'])) $address = $_COOKIE['address'];
                        print "<textarea id='description' rows='5' cols='60' name='address'>{$address}</textarea><br>";
                        
                        print "<h3>Company Contact Number:</h3>";
                        $contactNo = '';
                        if(isset($_COOKIE['contactNo'])) $contactNo = $_COOKIE['contactNo'];
                        
                        print "<input class='text-input' type='text' placeholder='Enter Contact Number' name='ContactNo' value='$contactNo' pattern='[0-9]{10}'></input>";

                        $conn -> close();

                    ?>

                    <br>
                    <br>
                    <input class="button" type="submit" name="submit" value="Submit Edit"/>
                </form>
            </div>
        </div>
    </body>
</html>

<?php 

    function updateProfile() {
        include ("serverConfig.php");
        $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
        if ($conn -> connect_error) {
            die("Connection failed:" .$conn -> connect_error);
        }
        //Update user description
        $companyID = $_GET['companyID'];

        $description = 'NULL';
        if(isset($_POST['description'])) $description = $_POST['description'];

        $address = 'NULL';
        if(isset($_POST['address'])) $address = $_POST['address'];
        
        // $contactNo = "NULL";
        $contactNo = $_POST['ContactNo'];
        if(!isset($_POST['ContactNo'])) $contactNo = 'NULL';

        $sql = "UPDATE companies
                SET Description = '{$description}', address = '{$address}', ContactNo = '{$contactNo}'
                WHERE companyID = {$companyID};";

        $conn->query($sql);

        if ($conn->query($sql) === TRUE) {
            header( "Location: adminCompany.php?companyID={$companyID}" );

        } 
        else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    if(isset($_POST["submit"])) updateProfile();
?>