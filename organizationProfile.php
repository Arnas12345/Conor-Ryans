<html>
    <head>
        <title>Loop : User Profie</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/profile_user.css?v=<?php echo time() ?>">
    </head>
    <body>
        <?php include("companyTemplate.html"); ?>
        <h1 class="page-header">Organization Profile</h1>
        <hr>
        <div class = "profile-container" >
            <div class = "profileImage" >
                <img src = "images/ellipse.png" alt = "profile image" height="20%" weight="20%" >
            </div>
            <div class="editProfile">
                <form action="editCompany.php">
                    <input type="submit" value="Edit Organization" />
                </form>
            </div>
        </div>
        <div class = "description-container">
            <div class = "description-heading">
                <H1 style = "text-align: center;">Description</H1>
            </div>
            <div class = "bio-description">
                <h3>Description:</h3>
                <?php
                    session_start();

                    $companyID = $_SESSION["company"];
                    include ("serverConfig.php");
                    $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
                    
                    if ($conn -> connect_error) {
                        die("Connection failed:" .$conn -> connect_error);
                    }

                    $sql = "select * from companies where companyID={$companyID};";
                    $result = $conn -> query($sql);
                    $row = $result->fetch_assoc();

                    if(isset($row['Description']) ){
                        print "<p class='userDetails'>{$row['Description']}</p>";
                        setcookie("companyDescription", $row['Description'], time() + 3600);
                    }
                    else{
                        print "<p class='userDetails'>No Description Given.</p>";
                    }

                    print "<h3>Address:</h3>";
                    if(isset($row['address']) ){
                        print "<p class='userDetails'>{$row['address']}</p>";
                        setcookie("address", $row['address'], time() + 3600);
                    }
                    else{
                        print "<p class='userDetails'>No Address Given.</p>";
                    }

                    print "<h3>Email:</h3>";
                    print "<p class='userDetails'>{$row['email']}</p>";
                    setcookie("email", $row['email'], time() + 3600);

                    print "<h3>Contact Number:</h3>";
                    if(isset($row['ContactNo']) ){
                        print "<p class='userDetails'>{$row['ContactNo']}</p>";
                        setcookie("contactNo", $row['ContactNo'], time() + 3600);
                    }
                    else {
                        print "<p class='userDetails'>No Contact Number Given.</p>";
                    }

                    $conn->close();
                ?>
            </div>
        </div>
        
        <div class="page-box">
            <h1>All Job Listings</h1>
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
                        
                        print "<div class='vacancy'>";
                        print "<div class='container'>
                                    <div class='row'>
                                        <div class='col-4' >
                                            <img class='job_logo' src='images/job-icon.jpg' alt='logo here'></img>
                                        </div>
                                        <div class='col-8' >
                                        <p class='vacancyDetails'><b>Title: </b>{$row['vacancyTitle']}</p>
                                        <p class='vacancyDetails'><b>Description: </b>{$row['vacancyDescription']}</p>
                                        <p class='vacancyDetails'><b>Role: </b>{$row['role']}</p>
                                        <p class='vacancyDetails'><b>Req. Experience: </b>{$row['requiredExperience']}</p>";
                                        
                                        print "</div></div></div></div>";
                    }
                } else {
                    print "<h1>No Vacanies Found.</h1>";
                }
                    $conn->close();
            ?>
        </div>
    </body>
</html>