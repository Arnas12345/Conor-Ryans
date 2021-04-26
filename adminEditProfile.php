

<html>
    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/profile_user.css?v=<?php echo time(); ?>">
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico"/>

        <meta content="width=device-width, initial-scale=1" name="viewport" />

        <title>Loop : Admin</title>

    </head>
    <script type="text/javascript">
        function refreshPage(variable) {
            window.location.href = "adminEditProfile.php?userID=" + variable;
        }

        function goToProfile(variable) {
            window.location.href = "adminUserView.php?userID=" + variable;
        }
    </script>
    <body>
        <?php 
            
            include ("validateLoggedIn.php");

            function getUserData($uID) {
                include ("serverConfig.php");
                $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
                if ($conn -> connect_error) {
                    die("Connection failed:" .$conn -> connect_error);
                }

                $sql = "select * from users where userID =\"{$uID}%\";";
                $result = $conn -> query($sql);
                $conn -> close();
                $row = $result->fetch_assoc();
                return $row;
            }

            include("adminTemplate.html");
            
        ?>

        <h1 class="page-heading">Edit Profile</h1>
        <hr>
        <div class = "profile-container" >
            <div class = "profileImage">
                <?php

                    $userID = $_GET["userID"];

                    $row = getUserData($userID);
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

            <div class="changePicture">
            <?php
                    $userID = $_GET["userID"];
                    print "<form method='post' action='adminEditProfile.php?userID={$userID}' enctype='multipart/form-data'>";?>
                    <input class="file" type="file" name="image">
                    <input class="edit" type="submit" name="submitImage" value="Upload">
                </form>
            </div>
        </div>

        <div class = "description-container">
            <div class = "bio-description">
                <?php 
                    $userID = $_GET["userID"];
                    print "<form method='post' action='adminEditProfile.php?userID={$userID}'>";?>
                    <h3>Enter Bio:</h3>
                    <?php

                        include ("serverConfig.php");
                        $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
                        if ($conn -> connect_error) {
                            die("Connection failed:" .$conn -> connect_error);
                        }

                        $userID = $_GET['userID'];
                        $sql = "select * from users where userID={$userID};";
                        $result = $conn -> query($sql);

                        if(isset($_POST['submitImage']) && $_FILES["image"]["name"]) {
                            
                            $profileImageName = time() . "_" . $_FILES["image"]["name"];
                            $target = "profileImages/" . $profileImageName;

                            str_replace(" ", "", $target);
                            if(copy($_FILES["image"]["tmp_name"], $target)) {
                                $userProfileImage ="UPDATE users 
                                                    SET profileImage='$profileImageName'
                                                    WHERE userID={$userID};";
                            }
                            
                            if($conn->query($userProfileImage)) {
                                header( "Location: adminUserView.php?userID={$row['userID']}" );
                            }
                        }

                        //Sets the description if one exists
                        $description = '';
                        if(isset($_SESSION['description'])) $description = $_SESSION['description'];
                        print "<textarea id='description' class='description-textarea'
                                    name='description' pattern='[A-Za-z0-9]{6,255}' 
                                    title='Please input between 6 and 255 characters. Letters and numbers only.'>{$description}</textarea>
                                <br>";

                        //User can select all skills they want
                        print '<hr><h3>Select Skills</h3>
                                <p>Please Hold Ctrl to select multiple skills</p>
                                <select class="skills" name="skills[]" multiple>';
                        $skillsSql = "select * from skills;";
                        $skillsResult = $conn -> query($skillsSql);
                        
                        $getUsersSkillsSql = "select * from userskills WHERE userID={$userID};";
                        $getUsersSkillsResult = $conn -> query($getUsersSkillsSql);
                        $skillsArray = array();
                        while($getUsersSkillsRow = $getUsersSkillsResult->fetch_assoc()) {
                            $skillsArray[] = $getUsersSkillsRow['skillID'];
                        }
                        while($skillsRow = $skillsResult->fetch_assoc())
                        {   
                            if(in_array($skillsRow['skillID'], $skillsArray))  print "<option value='{$skillsRow['skillTitle']}' selected>{$skillsRow['skillTitle']}</option>";
                            else print "<option value='{$skillsRow['skillTitle']}'>{$skillsRow['skillTitle']}</option>";
                        }
                        print '</select><br><hr>';

                        //User can select employer, if current one exists automatically selected
                        print '<h3>Select Current Employer</h3>
                                <select class="employer" name="currentEmployer">
                                <option name="None">None</option>';
                        $currentEmployerSQL = "select * from companies;";
                        $currentEmployerResult = $conn -> query($currentEmployerSQL);
                        while($currentEmployerRow = $currentEmployerResult->fetch_assoc())
                        {   
                            if(isset($_SESSION['currentEmployer']) && $_SESSION['currentEmployer'] == $currentEmployerRow['companyName']) {
                                print "<option name='{$currentEmployerRow['companyName']}' selected>{$currentEmployerRow['companyName']}</option>";
                            } else print "<option name='{$currentEmployerRow['companyName']}'>{$currentEmployerRow['companyName']}</option>";
                        }
                        print '</select><br>
                        <hr>
                        <input class="button" type="submit" name="submit" value="Submit Edit"/>
                        </form>';

                        //Added qualifications
                        print "<hr><h3>Qualifications</h3>
                                <form action='adminEditProfile.php?userID={$userID}' method='post'> ";
                                print '
                                    <input class="text-input" type="text" placeholder="Enter University Name" name="University" required></input>
                                    <br>
                                    <input class="text-input" type="text" placeholder="Enter Course Name" name="Course" required></input>
                                    <br>
                                    <input class="text-input" type="text" placeholder="Enter NFQ Level" name="Level" required></input>
                                    <br>
                                    <label for="DateCompleted" style="padding-left:1%">Date Completed:</label>
                                    <br>
                                    <input class="calendar" type="date" name="DateCompleted" required>
                                    <br>
                                    <input class="button" type="submit" name="addQualification" value="Add Qualification"/>
                                </form>
                                <br>
                                <p style="margin-top: 1%">Current Qualifcations:</p><br>';

                        $qualificationSQL = "SELECT a.academicID, a.academicTitle, a.academicDescription, a.academicLevel, b.completionDate
                            FROM accademicdegrees a
                            INNER JOIN userqualificaion b
                            ON a.academicID = b.academicID
                            WHERE b.userID = {$_GET['userID']};";
                        $qualificationResult = $conn -> query($qualificationSQL);
                        if(mysqli_num_rows($qualificationResult) != 0) {
                            while($qualificationRow = $qualificationResult->fetch_assoc()) {
                                print "<div class='qualification'>
                                <p class='graduated'>Graduated {$qualificationRow['academicDescription']}, {$qualificationRow['academicLevel']} at {$qualificationRow['academicTitle']} on {$qualificationRow['completionDate']}</p>
                                <a style='margin-top: 1%'id='deletedQualification' href='adminEditProfile.php?deletedQualification=true&userID={$userID}&academicID={$qualificationRow['academicID']}'>&#x2716;</a></div>";
                            }
                        }
                        
                        //User can select previous history
                        print "<form action='adminEditProfile.php?userID={$userID}' method='post'>
                                <hr><h3>Select Job History</h3>
                                <select class='employer' name='employementHistory'>
                                <option name='None'>None</option>";
                        $employerSQL = "select * from companies;";
                        $employerResult = $conn -> query($employerSQL);
                        while($employerRow = $employerResult->fetch_assoc())
                        {   
                            print "<option name='{$employerRow['companyName']}'>{$employerRow['companyName']}</option>";
                        }
                        print '</select>
                                <br>
                                <label for="dateStarted" style="padding-top:1%">Job Start:</label>
                                <br>
                                <input class="calendar" type="date" name="dateStarted" required>
                                <br>
                                <label for="dateEnded">Job End:</label>
                                <br>
                                <input class="calendar" type="date" name="dateEnded" required>
                                <br>
                                <input class="button" type="submit" name="addJobHistory" value="Add Employment History"/>
                                </form>
                                <br>
                                <p style="margin-top: 1%">Current Qualifcations:</p><br>';

                        $previousHistorySQL = "SELECT a.FromDate, a.ToDate, b.companyName, b.companyID
                            FROM jobhistory a
                            INNER JOIN companies b
                            ON a.companyID = b.companyID
                            WHERE a.userID = {$userID};";
                        $previousHistoryResult = $conn -> query($previousHistorySQL);
                        if(mysqli_num_rows($previousHistoryResult) != 0) {
                            while($previousHistoryRow = $previousHistoryResult->fetch_assoc()) {
                                print "<div class='qualification'>
                                <p class='graduated'>{$previousHistoryRow['companyName']}, {$previousHistoryRow['FromDate']} - {$previousHistoryRow['ToDate']}</p>
                                <a style='margin-top: 1%'id='deleteJobHistory' href='adminEditProfile.php?deleteJobHistory=true&userID={$userID}&companyID={$previousHistoryRow['companyID']}'>&#x2716;</a></div>";
                            }
                        }

                        $conn -> close();
                    ?>
            </div>
        </div>
    </body>
</html>

<?php 

    include ("serverConfig.php");
    $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
    if ($conn -> connect_error) {
        die("Connection failed:" .$conn -> connect_error);
    }

    function updateProfile($conn) {
        //Update user description
        $userID = $_GET['userID'];
        $sql = "UPDATE users
                SET description = '{$_POST['description']}'
                WHERE userID = {$userID}";
        
        $_SESSION['description'] = $_POST['description'];
        //Updates the skills if selected
        if(isset($_POST['skills'])) {
            $values = $_POST['skills'];
            
            $deleteSkills = "DELETE FROM userskills WHERE userID={$userID};";
            $conn -> query($deleteSkills);
            if ($conn->query($deleteSkills) === TRUE) {
                foreach($values as $value) {
                    $skillsSqlForm = "select * from skills where skillTitle=\"{$value}\";";
                    $skillsResultForm = $conn -> query($skillsSqlForm);
                    $skillsRowForm = $skillsResultForm->fetch_assoc();

                    $userSkillsSQL = "INSERT INTO userskills (userID, skillID)
                    VALUES ('{$_GET['userID']}', '{$skillsRowForm['skillID']}')";
                    $conn->query($userSkillsSQL);
                }
            }
        } else {
            $deleteSkills = "DELETE FROM UserSkills WHERE userID={$userID};";
            $conn -> query($deleteSkills);
        }

        //Update users current employer
        if(isset($_POST['currentEmployer'])) {
            $currentEmployerSQLForm = "select * from companies where companyName=\"{$_POST['currentEmployer']}\";";
            $currentEmployerResultForm = $conn -> query($currentEmployerSQLForm);
            $currentEmployerRowForm = $currentEmployerResultForm->fetch_assoc();
            $companyName = $currentEmployerRowForm['companyID'];
            if($_POST['currentEmployer'] === "None") $companyName = 'NULL';
            $userCurrentEmployerSQL = "UPDATE users
            SET companyID = {$companyName}
            WHERE userID = {$userID}";
            $conn->query($userCurrentEmployerSQL);
        }


        if ($conn->query($sql) === TRUE) {

        } 
        else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        
        echo "<script> goToProfile({$userID}); </script>";
    }

    if(isset($_POST["submit"])) updateProfile($conn);

    if(isset($_POST["addQualification"])) {
        //Adds a qualification
        if(isset($_POST['University']) && isset($_POST['Course']) && isset($_POST['Level']) && isset($_POST['DateCompleted'])) {
            $accademicSQL = "INSERT INTO accademicdegrees (academicTitle, academicDescription, academicLevel)
            VALUES ('{$_POST['University']}', '{$_POST['Course']}', '{$_POST['Level']}')";
            $conn->query($accademicSQL);
            
            $accademicSQLGet = "select * from accademicdegrees where academicTitle=\"{$_POST['University']}\"
            AND academicDescription=\"{$_POST['Course']}\" AND academicLevel=\"{$_POST['Level']}\";";
            $accademicSQLGetResult = $conn -> query($accademicSQLGet);
            $accademicSQLGetRow = $accademicSQLGetResult->fetch_assoc();

            $insertAcademicIntoUser = "INSERT INTO userqualificaion (userID, academicID, CompletionDate)
            VALUES ('{$userID}', '{$accademicSQLGetRow['academicID']}', '{$_POST['DateCompleted']}')";
            $conn->query($insertAcademicIntoUser);
            $userID = $_GET['userID'];
            echo "<script> refreshPage({$userID}); </script>";
        }
    };

    if(isset($_POST["addJobHistory"])) {
        //Adds a qualification
        if(isset($_POST['employementHistory']) && isset($_POST['dateStarted']) && isset($_POST['dateEnded'])) {
            $companySQL = "select * from companies where companyName=\"{$_POST['employementHistory']}\";";
            $companySQLResult = $conn -> query($companySQL);
            $companySQLRow = $companySQLResult->fetch_assoc();

            $jobHistorySQL = "INSERT INTO jobhistory (userID, companyID, FromDate, ToDate)
            VALUES ('{$userID}', '{$companySQLRow['companyID']}', '{$_POST['dateStarted']}', '{$_POST['dateEnded']}')";
            $conn->query($jobHistorySQL);
            $userID = $_GET['userID'];
            echo "<script> refreshPage({$userID}); </script>";
        }
    };

    if (isset($_GET['deletedQualification'])) {
        $deleteUserQualification = "DELETE FROM userqualificaion WHERE userID={$_GET['userID']} AND academicID={$_GET['academicID']};";
        $conn -> query($deleteUserQualification);
        $userID = $_GET['userID'];
        echo "<script> refreshPage({$userID}); </script>";
    }

    if (isset($_GET['deleteJobHistory'])) {
        $deleteUserJobHistory = "DELETE FROM jobhistory WHERE userID={$_GET['userID']} AND companyID={$_GET['companyID']};";
        $conn -> query($deleteUserJobHistory);
        $userID = $_GET['userID'];
        echo "<script> refreshPage({$userID}); </script>";
    }

    $conn ->close();
?>