<html>
    <head>
        <title>Loop : Company Home</title>
        
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/companyHome.css?v=<?php echo time(); ?>">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico"/>

    </head>
    <script type="text/javascript">
        function showSkills(modalNumber) {
            var modal = document.getElementById("myModal" + modalNumber);
            modal.style.display = "block";
            
            var span = document.getElementsByClassName("close")[0];
            span.onclick = function() {
                modal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        }
        
        
        function showApplicants(modalNumber) {
            var modal = document.getElementById("applicantsModal" + modalNumber);
            modal.style.display = "block";
            
            var span = document.getElementsByClassName("close")[0];
            span.onclick = function() {
                modal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        }

        
        function refreshPage(modalNumber) {
            window.location.href = "organizationHome.php";

        }
    </script>
    <body>

        <?php

            include ("validateLoggedIn.php");
            include ("companyTemplate.html");
        ?>

        <h1 class="page-heading">Vacancies Page</h1>
        <hr>
        
        <div class="page-box">
            <?php
                include ("serverConfig.php");
                $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
                if ($conn -> connect_error) {
                    die("Connection failed:" .$conn -> connect_error);
                }
                $currentCompany = $_SESSION['company'];
                $sql = "select a.vacancyTitle, a.vacancyDescription, a.requiredExperience, a.role, a.timeAdded, b.companyName, a.vacancyID, b.companyID
                from vacancies a
                INNER JOIN companies b
                ON a.companyID = b.companyID
                WHERE b.companyID = {$currentCompany}
                ORDER BY timeAdded DESC;";
                $result = $conn -> query($sql);
                
                if(mysqli_num_rows($result) != 0) {
                    $counter = 0;
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
                            $skill = array('skillTitle' => $skillsRow['skillTitle'], 'skillDesc' => $skillsRow['skillDescription']);
                            $skillsNeeded[] = $skill;
                        }
                        $counter++;
                        print "<div class='container vacancy'>
                                    <div class='row'>
                                        <div class='col-12' >
                                        <p class='vacancyDetails text-left'><b>Title: </b>{$row['vacancyTitle']}</p>
                                        <p class='vacancyDetails text-left'><b>Description: </b>{$row['vacancyDescription']}</p>
                                        <p class='vacancyDetails text-left'><b>Role: </b>{$row['role']}</p>
                                        <p class='vacancyDetails text-left'><b>Req. Experience: </b>{$row['requiredExperience']}</p>
                                        <button class='showskills' onClick='showSkills({$counter})'>Show Skills</button>
                                        <button class='showskills' onClick='showApplicants({$counter})'>Show Applicants</button>";
                                        
                                        //skills Modal
                                        print "<div id='myModal{$counter}' class='modal'>
                                                <!-- Modal content -->
                                                <div class='modal-content'>
                                                    <span class='close'>&times;</span>
                                                    <table class='skillsTable'>
                                                        <thead>
                                                            <tr>
                                                                <th>Skills Required</th>
                                                                <th>Skills Description</th>
                                                            </tr>
                                                        </thead>";
                                        
                                        if(!empty($skillsNeeded)) {
                                            foreach ($skillsNeeded as $skill) 
                                            {   
                                                echo '<tr>';
                                                echo '<td>' . $skill['skillTitle'] . '</td>';
                                                echo '<td>' . $skill['skillDesc'] . '</td>';
                                                echo '</tr>';
                                            }
                                        } else echo "<tr><td colspan='2'>No Specific Skills Required</td></tr>";
                                        print "</table></div></div>";

                                        //Applicants modal
                                        print "<div id='applicantsModal{$counter}' class='modal'>
                                                <!-- Modal content -->
                                                <div class='modal-content'>
                                                    <span class='close'>&times;</span>
                                                    <table class='skillsTable'>
                                                        <thead>
                                                            <tr>
                                                                <th>Applicants</th>
                                                                <th>Status</th>
                                                            </tr>
                                                        </thead>";
                                        $getApplicantsSQL = "Select a.username, a.userID, b.status, b.vacancyID
                                                            FROM users a
                                                            INNER JOIN looped b
                                                            ON a.userID = b.userID
                                                            WHERE b.companyID={$currentCompany} AND b.vacancyID={$row['vacancyID']}";
                                        
                                        $getApplicantsResult = $conn -> query($getApplicantsSQL);
                                        
                                        if(mysqli_num_rows($getApplicantsResult) != 0) {
                                            while($getApplicantsRow = $getApplicantsResult -> fetch_assoc()) {
                                                echo '<tr>';
                                                echo "<td><a id='applicant{$getApplicantsRow['userID']}' class='applicant' href='profile.php?userID={$getApplicantsRow['userID']}'>{$getApplicantsRow['username']}</a></td>";
                                                if($getApplicantsRow['status'] == 'Pending') {
                                                    echo "<td>
                                                            <a id='decline{$getApplicantsRow['userID']}' class='status' href='organizationHome.php?deleteUser=true&userID={$getApplicantsRow['userID']}&vacancyID={$getApplicantsRow['vacancyID']}'>&#x2716;</a>
                                                            <a id='accept{$getApplicantsRow['userID']}' class='status' href='organizationHome.php?acceptUser=true&userID={$getApplicantsRow['userID']}&vacancyID={$getApplicantsRow['vacancyID']}'>&#x2714;</a>
                                                        </td>";
                                                } else echo "<td><p>{$getApplicantsRow['status']}</p></td>";
                                                echo '</tr>';
                                            }
                                        } else echo "<tr><td colspan='2'>No Applicants.</td></tr>";
                                        print "</table></div></div>";
                                        print "</div></div></div>";
                                        
                    }
                } else {
                    print "<h1>No Vacanies Found.</h1>";
                }
                    $conn->close();
            ?>
        </div>

        
    </body>
</html>

<?php
    if (isset($_GET['deleteUser'])) changeApplicantStatus("Declined");
    if (isset($_GET['acceptUser'])) changeApplicantStatus("Accepted");

    function changeApplicantStatus($status) {
        include ("serverConfig.php");
        $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
        if ($conn -> connect_error) {
            die("Connection failed:" .$conn -> connect_error);
        }
        $declineApplicationSQL = "UPDATE looped 
                                SET status='{$status}'
                                WHERE userID={$_GET['userID']} AND vacancyID={$_GET['vacancyID']};";
        $conn -> query($declineApplicationSQL);
        echo "<script> refreshPage(); </script>";
    }
?>