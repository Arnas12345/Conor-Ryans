<html>
    <head>
        <title>Loop : Company Profie</title>
        
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/companyEditProfile.css?v=<?php echo time() ?>">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico"/>
        
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
            $companyID = $_SESSION["company"];

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
        
            <div class="editProfile">
                <form action="editCompany.php">
                    <input class="edit" type="submit" value="Edit Organization" />
                </form>
            </div>
        </div>
        <div class = "description-container">
            <div class = "bio-description">
                <h3 class='text-left'>Description:</h3>
                <?php

                    $companyID = $_SESSION["company"];
                    
                    $row = getCompanyData($companyID);

                    if(isset($row['Description']) ){
                        print "<p class='userDetails text-left'>{$row['Description']}</p>";
                        setcookie("companyDescription", $row['Description'], time() + 3600);
                    }
                    else{
                        print "<p class='userDetails text-left'>No Description Given.</p>";
                    }

                    print "<h3 class='text-left'>Address:</h3>";
                    if(isset($row['address']) ){
                        print "<p class='userDetails text-left'>{$row['address']}</p>";
                        setcookie("address", $row['address'], time() + 3600);
                    }
                    else{
                        print "<p class='userDetails text-left'>No Address Given.</p>";
                    }

                    print "<h3 class='text-left'>Email:</h3>";
                    print "<p class='userDetails text-left'>{$row['email']}</p>";
                    setcookie("email", $row['email'], time() + 3600);

                    print "<h3 class='text-left'>Contact Number:</h3>";
                    if(isset($row['ContactNo']) ){
                        print "<p class='userDetails text-left'>{$row['ContactNo']}</p>";
                        setcookie("contactNo", $row['ContactNo'], time() + 3600);
                    }
                    else {
                        print "<p class='userDetails text-left'>No Contact Number Given.</p>";
                    }

                ?>
            </div>
        </div>
        
        <div class="page-box">
            <h1 class="page-heading">All Job Listings</h1>
            <?php
                include ("serverConfig.php");
                $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
                if ($conn -> connect_error) {
                    die("Connection failed:" .$conn -> connect_error);
                }

                $sql = "select a.vacancyTitle, a.vacancyDescription, a.requiredExperience, a.role, a.timeAdded, b.companyName, a.vacancyID, b.companyID
                from vacancies a
                INNER JOIN companies b
                ON a.companyID = b.companyID
                WHERE b.companyID = {$companyID}
                ORDER BY timeAdded DESC;";
                $result = $conn -> query($sql);
                
                if(mysqli_num_rows($result) != 0) {
                    while($row = $result->fetch_assoc())
                    {   
                        $skillsNeeded = array();
                        $skillsSql = "select a.skillTitle, a.skillDescription
                        from skills a
                        INNER JOIN skillsforvacancy b
                        ON a.skillID = b.skillID
                        INNER JOIN vacancies c
                        ON b.vacancyID = c.vacancyID
                        WHERE c.vacancyID= {$row['vacancyID']}";
                        $skillsResult = $conn -> query($skillsSql);
                        while($skillsRow = $skillsResult -> fetch_assoc()) {
                            $skillsNeeded[] = $skillsRow['skillTitle'];
                        }
                        
                        print "<div class='container vacancy'>
                                    <div class='row'>
                                        <div class='col-12' >
                                        <p class='vacancyDetails text-left'><b>Title: </b>{$row['vacancyTitle']}</p>
                                        <p class='vacancyDetails text-left'><b>Description: </b>{$row['vacancyDescription']}</p>
                                        <p class='vacancyDetails text-left'><b>Role: </b>{$row['role']}</p>
                                        <p class='vacancyDetails text-left'><b>Req. Experience: </b>{$row['requiredExperience']}</p>";
                                        
                                        print "</div></div></div>";
                    }
                } 
                else {
                    print "<h1>No Vacanies Found.</h1>";
                }
                
                $conn->close();

            ?>
        </div>
    </body>
</html>