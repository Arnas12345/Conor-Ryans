

<html>
    <head>
        <title>Loop : Home</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/profile_user.css?v=<?php echo time(); ?>">
    </head>
    <body>
        <?php include("companyTemplate.html"); ?>
        <h1 class="page-header">Edit Organization</h1>
        <hr>
        <div class = "profile-container" >
            <div class = "profileImage" >
                <img src = "images/ellipse.png" alt = "profile image" height="20%" weight="20%" >
            </div>
        </div>
        <div class = "description-container">
            <div class = "description-heading">
                <H1 style = "text-align: center;">Description</H1>
            </div>
            <div class = "bio-description">
                <form method="post" action="editCompany.php">
                    <h3>Company Description:</h3>
                    <?php
                        session_start();
                        include ("serverConfig.php");
                        $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
                        if ($conn -> connect_error) {
                            die("Connection failed:" .$conn -> connect_error);
                        }

                        $companyID = $_SESSION['company'];
                        $sql = "select * from companies where companyID={$companyID};";
                        $result = $conn -> query($sql);

                        //Sets the description if one exists
                        $description = '';
                        if(isset($_COOKIE['companyDescription'])) $description = $_COOKIE['companyDescription'];
                        print "<textarea id='description' rows='5' cols='60' name='description'>{$description}</textarea><br>";

                        //Sets the address if one exists
                        print "<h3>Company Address:</h3>";
                        $address = '';
                        if(isset($_COOKIE['address'])) $address = $_COOKIE['address'];
                        print "<textarea id='address' rows='5' cols='60' name='address'>{$address}</textarea><br>";
                        
                        print "<h3>Company Contact Number:</h3>";
                        $contactNo = '';
                        if(isset($_COOKIE['contactNo'])) $contactNo = $_COOKIE['contactNo'];
                        
                        print "<input type='text' placeholder='Enter Contact Number' name='ContactNo' value='$contactNo' pattern='[0-9]{10}'></input>";
                    ?>
                    <br>
                    <br>
                    <input type="submit" name="submit" value="Submit Edit"/>
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
        $companyID = $_SESSION['company'];

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
            // header( "Location: organizationProfile.php" );

        } 
        else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    if(isset($_POST["submit"])) updateProfile();
?>