

<html>
    <head>
        <title>Loop : Home</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/profile_user.css?v=<?php echo time(); ?>">
    </head>
    <script type="text/javascript">
        function refreshPage() {
            window.location.href = "editProfile.php";
        }
    </script>
    <body>
        <?php include("headerTemplate.html"); ?>
        <h1 class="page-header">Edit Profile</h1>
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
                <form method="post" action="editProfile.php">
                    <h3>Enter Bio:</h3>
                    <?php
                        session_start();
                        include ("serverConfig.php");
                        $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
                        if ($conn -> connect_error) {
                            die("Connection failed:" .$conn -> connect_error);
                        }

                        $userID = $_SESSION['user'];
                        $sql = "select * from Users where userID={$userID};";
                        $result = $conn -> query($sql);

                        //Sets the description if one exists
                        $description = '';
                        if(isset($_COOKIE['description'])) $description = $_COOKIE['description'];
                        print "<textarea id='description' rows='5' cols='60' name='description'>{$description}</textarea><br>";

                        //User can select all skills they want
                        print '<h3>Select Skills</h3>
                                <select name="skills[]" multiple>';
                        $skillsSql = "select * from Skills;";
                        $skillsResult = $conn -> query($skillsSql);
                        while($skillsRow = $skillsResult->fetch_assoc())
                        {   
                            $getUsersSkillsSql = "select * from Userskills WHERE userID={$userID}";
                            $getUsersSkillsResult = $conn -> query($getUsersSkillsSql);
                            //Loop is incorrect prints all skills for how many times the user has skills
                            // while($getUsersSkillsRow = $getUsersSkillsResult->fetch_assoc()) {
                            // if($skillsRow['skillID'] == $getUsersSkillsRow['skillID']) print "<option value='{$skillsRow['skillTitle']}' selected>{$skillsRow['skillTitle']}</option>";
                            print "<option value='{$skillsRow['skillTitle']}'>{$skillsRow['skillTitle']}</option>";
                            // else print "<option value='{$skillsRow['skillTitle']}'>{$skillsRow['skillTitle']}</option>";
                            // }
                        }
                        print '</select><br>';

                        //User can select employer, if current one exists automatically selected
                        print '<h3>Select Current Employer</h3>
                                <select name="currentEmployer">
                                <option name="None">None</option>';
                        $currentEmployerSQL = "select * from Companies;";
                        $currentEmployerResult = $conn -> query($currentEmployerSQL);
                        while($currentEmployerRow = $currentEmployerResult->fetch_assoc())
                        {   
                            if(isset($_COOKIE['currentEmployer']) && $_COOKIE['currentEmployer'] == $currentEmployerRow['companyName']) {
                                print "<option name='{$currentEmployerRow['companyName']}' selected>{$currentEmployerRow['companyName']}</option>";
                            } else print "<option name='{$currentEmployerRow['companyName']}'>{$currentEmployerRow['companyName']}</option>";
                        }
                        print '</select><br>';

                        //Added qualifications
                        print '<h3>Qualifications</h3>
                                <input type="text" placeholder="Enter University Name" name="University"></input>
                                <input type="text" placeholder="Enter Course Name" name="Course"></input>
                                <input type="text" placeholder="Enter QCA Level Name" name="Level"></input>
                                <input type="text" placeholder="Enter Date Completed" name="DateCompleted"></input>
                                <input type="submit" name="addQualification" value="Add Qualification"/>
                                <br>';

                        $qualificationSQL = "SELECT a.academicID, a.academicTitle, a.academicDescription, a.academicLevel, b.completionDate
                            FROM accademicdegrees a
                            INNER JOIN userqualificaion b
                            ON a.academicID = b.academicID
                            WHERE b.userID = {$_SESSION['user']};";
                        $qualificationResult = $conn -> query($qualificationSQL);
                        if(mysqli_num_rows($qualificationResult) != 0) {
                            while($qualificationRow = $qualificationResult->fetch_assoc()) {
                                print "<p>Graduated {$qualificationRow['academicDescription']}, {$qualificationRow['academicLevel']} at {$qualificationRow['academicTitle']} on {$qualificationRow['completionDate']}</p>
                                <a id='deletedQualification' href='editProfile.php?deletedQualification=true&currentUser={$userID}&academicID={$qualificationRow['academicID']}'>&#x2716;</a>";
                            }
                        }
                        
                        //User can select previous history
                        print '<h3>Select Current Employer</h3>
                                <select name="employementHistory">
                                <option name="None">None</option>';
                        $employerSQL = "select * from Companies;";
                        $employerResult = $conn -> query($employerSQL);
                        while($employerRow = $employerResult->fetch_assoc())
                        {   
                            print "<option name='{$employerRow['companyName']}'>{$employerRow['companyName']}</option>";
                        }
                        print '</select>
                                <input type="text" placeholder="Job Start Date" name="dateStarted"></input>
                                <input type="text" placeholder="Job End Date" name="dateEnded"></input>
                                <input type="submit" name="addJobHistory" value="Add Employment History"/>
                                <br>';

                        $previousHistorySQL = "SELECT a.FromDate, a.ToDate, b.companyName, b.companyID
                            FROM jobHistory a
                            INNER JOIN companies b
                            ON a.companyID = b.companyID
                            WHERE a.userID = {$userID};";
                        $previousHistoryResult = $conn -> query($previousHistorySQL);
                        if(mysqli_num_rows($previousHistoryResult) != 0) {
                            while($previousHistoryRow = $previousHistoryResult->fetch_assoc()) {
                                print "<p>Graduated {$previousHistoryRow['companyName']}, {$previousHistoryRow['FromDate']} at {$previousHistoryRow['ToDate']}</p>
                                <a id='deletedQualification' href='editProfile.php?deleteJobHistory=true&currentUser={$userID}&companyID={$previousHistoryRow['companyID']}'>&#x2716;</a>";
                            }
                        }
                    ?>
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
        $userID = $_SESSION['user'];
        $sql = "UPDATE Users
                SET description = '{$_POST['description']}'
                WHERE UserID = {$userID}";

        //Updates the skills if selected
        if(isset($_POST['skills'])) {
            $values = $_POST['skills'];
            
            $deleteSkills = "DELETE FROM UserSkills WHERE userID={$userID};";
            $conn -> query($deleteSkills);
            if ($conn->query($deleteSkills) === TRUE) {
                foreach($values as $value) {
                    $skillsSqlForm = "select * from Skills where skillTitle=\"{$value}\";";
                    $skillsResultForm = $conn -> query($skillsSqlForm);
                    $skillsRowForm = $skillsResultForm->fetch_assoc();

                    $userSkillsSQL = "INSERT INTO Userskills (userID, skillID)
                    VALUES ('{$_SESSION['user']}', '{$skillsRowForm['skillID']}')";
                    $conn->query($userSkillsSQL);
                }
            }
        } else {
            $deleteSkills = "DELETE FROM UserSkills WHERE userID={$userID};";
            $conn -> query($deleteSkills);
        }

        //Update users current employer
        if(isset($_POST['currentEmployer'])) {
            $currentEmployerSQLForm = "select * from Companies where companyName=\"{$_POST['currentEmployer']}\";";
            $currentEmployerResultForm = $conn -> query($currentEmployerSQLForm);
            $currentEmployerRowForm = $currentEmployerResultForm->fetch_assoc();
            $companyName = $currentEmployerRowForm['companyID'];
            //Fix NULL VALue
            if($_POST['currentEmployer'] === "None") $companyName = 'NULL';
            $userCurrentEmployerSQL = "UPDATE Users
            SET companyID = {$companyName}
            WHERE UserID = {$userID}";
            $conn->query($userCurrentEmployerSQL);
        }


        if ($conn->query($sql) === TRUE) {
            // header( "Location: profile_user.php" );

        } 
        else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        
    }

    if(isset($_POST["submit"])) updateProfile();

    if(isset($_POST["addQualification"])) {
        //Adds a qualification
        if(isset($_POST['University']) && isset($_POST['Course']) && isset($_POST['Level']) && isset($_POST['DateCompleted'])) {
            $accademicSQL = "INSERT INTO Accademicdegrees (academicTitle, academicDescription, academicLevel)
            VALUES ('{$_POST['University']}', '{$_POST['Course']}', '{$_POST['Level']}')";
            $conn->query($accademicSQL);
            
            $accademicSQLGet = "select * from Accademicdegrees where academicTitle=\"{$_POST['University']}\"
            AND academicDescription=\"{$_POST['Course']}\" AND academicLevel=\"{$_POST['Level']}\";";
            $accademicSQLGetResult = $conn -> query($accademicSQLGet);
            $accademicSQLGetRow = $accademicSQLGetResult->fetch_assoc();

            $insertAcademicIntoUser = "INSERT INTO userqualificaion (userID, academicID, CompletionDate)
            VALUES ('{$userID}', '{$accademicSQLGetRow['academicID']}', '{$_POST['DateCompleted']}')";
            $conn->query($insertAcademicIntoUser);
            echo "<script> refreshPage(); </script>";
        }
    };

    if(isset($_POST["addJobHistory"])) {
        //Adds a qualification
        if(isset($_POST['employementHistory']) && isset($_POST['dateStarted']) && isset($_POST['dateEnded']) && $_POST['employementHistory'] != 'None') {
            $companySQL = "select * from Companies where companyName=\"{$_POST['employementHistory']}\";";
            $companySQLResult = $conn -> query($companySQL);
            $companySQLRow = $companySQLResult->fetch_assoc();

            $jobHistorySQL = "INSERT INTO jobhistory (userID, companyID, FromDate, ToDate)
            VALUES ('{$userID}', '{$companySQLRow['companyID']}', '{$_POST['dateStarted']}', '{$_POST['dateEnded']}')";
            $conn->query($jobHistorySQL);

            echo "<script> refreshPage(); </script>";
        }
    };

    if (isset($_GET['deletedQualification'])) {
        $deleteUserQualification = "DELETE FROM userqualificaion WHERE userID={$_GET['currentUser']} AND academicID={$_GET['academicID']};";
        $conn -> query($deleteUserQualification);
        echo "<script> refreshPage(); </script>";
    }

?>