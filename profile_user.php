

<html>
    <head>
        <title>Loop : Home</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/profile_user.css?v=<?php echo time(); ?>">
    </head>
    <body>
        <?php include("headerTemplate.html"); ?>
        <h1 class="page-header">My Profile</h1>
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
                <h3>Bio:</h3>
                <?php
                    session_start();
                    include ("serverConfig.php");
                    $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
                    if ($conn -> connect_error) {
                        die("Connection failed:" .$conn -> connect_error);
                    }
                        $sql = "select * from Users where userID =\"{$_SESSION['user']}%\";";
                        $result = $conn -> query($sql);
                        if($row = $result->fetch_assoc()) {
                            print "<p class='userDetails'>{$row['description']}</p>";
                            $conn->close();
                        } else {
                            print "<h1>No Bio found.</h1>";
                        }
                ?>
            </div>
            <div class = "skills-description">
                <h3>Skills:</h3>
                <?php
                    include ("serverConfig.php");
                    $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
                    if ($conn -> connect_error) {
                        die("Connection failed:" .$conn -> connect_error);
                    }
                    $sql = "SELECT a.skillTitle
                    FROM skills a
                    INNER JOIN userskills b
                    ON a.skillID = b.skillID
                    WHERE b.userID = {$_SESSION['user']};";
                    $result = $conn -> query($sql);
                    if(mysqli_num_rows($result) != 0) {
                        while($resultRow = $result->fetch_assoc()) {
                            print "<p>{$resultRow['skillTitle']}</p>";
                        }
                    } else {
                        print "<h1>No Skills Found.</h1>";
                    }
                ?>
            </div>
            
            <div class = "Qualifications-description">
                <h3>Employment History:</h3>
                <?php
                    include ("serverConfig.php");
                    $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
                    if ($conn -> connect_error) {
                        die("Connection failed:" .$conn -> connect_error);
                    }
                    $sql = "SELECT a.companyName, b.FromDate, b.ToDate
                    FROM companies a
                    INNER JOIN jobhistory b
                    ON a.companyID = b.companyID
                    WHERE b.userID = {$_SESSION['user']};";
                    $result = $conn -> query($sql);
                    if(mysqli_num_rows($result) != 0) {
                        while($resultRow = $result->fetch_assoc()) {
                            print "<p>{$resultRow['companyName']}, {$resultRow['FromDate']} - {$resultRow['ToDate']}</p>";
                        }
                    } else {
                        print "<h1>No Previous Job History Found.</h1>";
                    }
                ?>
            </div>

            <div class = "Certs-description">
            <h3>Qualifications:</h3>
                <?php
                    include ("serverConfig.php");
                    $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
                    if ($conn -> connect_error) {
                        die("Connection failed:" .$conn -> connect_error);
                    }
                    $sql = "SELECT a.academicTitle, a.academicDescription, a.academicLevel, b.completionDate
                    FROM accademicdegrees a
                    INNER JOIN userqualificaion b
                    ON a.academicID = b.academicID
                    WHERE b.userID = {$_SESSION['user']};";
                    $result = $conn -> query($sql);
                    if(mysqli_num_rows($result) != 0) {
                        while($resultRow = $result->fetch_assoc()) {
                            print "<p>Graduated {$resultRow['academicDescription']}, {$resultRow['academicLevel']} at {$resultRow['academicTitle']} on {$resultRow['completionDate']}</p>";
                        }
                    } else {
                        print "<h1>No Previous Job History Found.</h1>";
                    }
                ?>
            </div>

            <div class = "Qualifications-description">
                <h3>Current Employer:</h3>
                <?php
                    include ("serverConfig.php");
                    $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
                    if ($conn -> connect_error) {
                        die("Connection failed:" .$conn -> connect_error);
                    }
                    $sql = "SELECT a.companyName
                    FROM companies a
                    INNER JOIN users b
                    ON a.companyID = b.companyID
                    WHERE b.userID = {$_SESSION['user']};";
                    $result = $conn -> query($sql);
                    if($row = $result->fetch_assoc()) {
                        print "<p class='userDetails'>{$row['companyName']}</p>";
                        $conn->close();
                    } else {
                        print "<h1>No Current Employer.</h1>";
                    }
                ?>
            </div>
        </div>
    </body>
</html>