
<html>
    <head>
        <title>Loop : Home</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/home.css?v=<?php echo time(); ?>">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico"/>

        
    </head>
    <script type="text/javascript">
        function loopJob(vacancyID, companyID) {
            window.location.href= 'loopJob.php?vacancyID=' + vacancyID +'&companyID=' + companyID;
        }

        function unLoopJob(vacancyID, companyID) {
            window.location.href= 'unLoopJob.php?vacancyID=' + vacancyID +'&companyID=' + companyID;
                if (confirm("Are you sure you want to delete this looped job?") == true) {
                    window.location.href= 'unLoopJob.php?vacancyID=' + vacancyID +'&companyID=' + companyID;
                };
        }

        function showSkills(modalNumber) {
            var modal = document.getElementById("myModal" + modalNumber);
            modal.style.display = "block";
            
            var span = document.getElementsByClassName("close" + modalNumber)[0];
            span.onclick = function() {
                modal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        }
    </script>
    <body>
        <?php 
            include ("validateLoggedIn.php");
            include ("headerTemplate.html");
        ?>

        <h1 class="page-heading">Job Feed</h1>
       

    </div>
        <hr>
        <div class="page-box">
            <?php
                
                include ("serverConfig.php");
                $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
                if ($conn -> connect_error) {
                    die("Connection failed:" .$conn -> connect_error);
                }
                
                print '
                <form method="post" action="home.php?sortBySkills=true">
                <h3 style="color: black">Sort By Skills</h3>
                <div class="custom-select">
                <select class="skillSelect" name="skill">';
                $skillsSql = "select * from skills;";
                $skillsResult = $conn -> query($skillsSql);
                print "<option class='option' value=''>Select A Skill</option>";
                
                while($skillsRow = $skillsResult->fetch_assoc()) {   

                    print "<option value='{$skillsRow['skillTitle']}'>{$skillsRow['skillTitle']}</option>";
                
                }
                
                print '</select><input class="sort" type="submit" name="sortBySkills" value="Sort"></div></form><br>';
                $sql = "";

                //Sort By Skills
                if (isset($_GET['sortBySkills'])) {
                    //Gets the vacancies that match the skill
                    if(!empty($_POST['skill'])) {
                        print "<h2 style='color: black'>Sorting by {$_POST['skill']}";
                        $sql = "select a.vacancyTitle, a.vacancyDescription, a.requiredExperience, a.role, a.timeAdded, b.companyName, a.vacancyID, b.companyID, d.skillTitle, d.skillDescription
                        from vacancies a
                        INNER JOIN companies b
                        ON a.companyID = b.companyID
                        INNER JOIN skillsforvacancy c
                        ON a.vacancyID = c.vacancyID
                        INNER JOIN skills d
                        ON c.skillID = d.skillID
                        WHERE d.skillTitle = '{$_POST['skill']}'
                        ORDER BY timeAdded DESC;";
                    } 
                    else { //If no skill is selected show all vacancies
                        $sql = "select a.vacancyTitle, a.vacancyDescription, a.requiredExperience, a.role, a.timeAdded, b.companyName, a.vacancyID, b.companyID
                        from vacancies a
                        INNER JOIN companies b
                        ON a.companyID = b.companyID
                        ORDER BY timeAdded DESC;";
                    }
                    $result = $conn -> query($sql);
                    
                    //If it finds vacancies
                    if(mysqli_num_rows($result) != 0) {
                        $counter = 0;
                        //print them all out
                        while($row = $result->fetch_assoc()) {

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
                                                <a class='head vacancyDetails text-lg-center' href='company.php?companyID={$row['companyID']}'><b><p>{$row['companyName']}</p></b></a>
                                                <p class='vacancyDetails text-left'><b>Title: </b>{$row['vacancyTitle']}</p>
                                                <p class='vacancyDetails text-left'><b>Description: </b>{$row['vacancyDescription']}</p>
                                                <p class='vacancyDetails text-left'><b>Role: </b>{$row['role']}</p>
                                                <p class='vacancyDetails text-left'><b>Req. Experience: </b>{$row['requiredExperience']}</p>
                                                <button class='showskills' onClick='showSkills({$counter})'>Show Skills</button>";
                                            
                                            $loopedJobSQL = "select * from looped where userID = {$_SESSION['user']} AND companyID = {$row['companyID']} AND vacancyID = {$row['vacancyID']};";
                                            $loopedJobResult = $conn -> query($loopedJobSQL);
                                            $loopedJobRow = $loopedJobResult->fetch_assoc();
                                            if($loopedJobRow) {
                                                print "<img class='img-fluid' src='images/cancel_loop.png' alt='logo here' style='height: 12%;' onClick='unLoopJob(${row['vacancyID']}, ${row['companyID']})'></img>";
                                            } 
                                            else {
                                                print "<img class='img-fluid' src='images/Like_Loop_small.png' alt='logo here'  onClick='loopJob(${row['vacancyID']}, ${row['companyID']})'></img>";
                                            }
                                            print "<div id='myModal{$counter}' class='modal'>
                                                    <!-- Modal content -->
                                                    <div class='modal-content'>
                                                        <span class='close{$counter} close'>&times;</span>
                                                        <table class='skillsTable'>
                                                        <thead>
                                                            <tr>
                                                                <th>Skills Required</th>
                                                                <th>Skills Description</th>
                                                            </tr>
                                                        </thead>";
                                            if(!empty($skillsNeeded)) {
                                                foreach ($skillsNeeded as $row) 
                                                {   
                                                    echo '<tr>';
                                                    echo '<td>' . $row['skillTitle'] . '</td>';
                                                    echo '<td>' . $row['skillDesc'] . '</td>';
                                                    echo '</tr>';
                                                }
                                            }
                                            else echo "<tr><td colspan='3'>No Specific Skills Required</td></tr>";
                                            print "</table></div></div>";
                                            
                                            
                                            
                                            print "</div></div></div>";
                                            
                        }
                    } 
                    else {
                        print "<h1>No Vacanies Found.</h1>";
                    }
                }
                else {
                    //If it is not sorting by skills run this code, i.e. on page open
                    $counter = 0;
                    //Shows the automatically suggested jobs first that you have a skill in
                    $userSkills = "select a.skillTitle
                    from skills a
                    INNER JOIN userskills b
                    ON a.skillID = b.skillID
                    WHERE b.userID = {$_SESSION['user']}";
                    $vacancies = array();
                    $allVacancies = array();
                    $skills = array();
                    $userSkillResults = $conn -> query($userSkills);
                    //If the user has skills
                    if(mysqli_num_rows($userSkillResults) != 0) {
                        print "<h2 style='color: black'>Automatically Suggested Jobs</h2>";
                        while($userSkillRow = $userSkillResults->fetch_assoc()) {
                            $skills[] = $userSkillRow['skillTitle'];
                            $vacanciesSQL = "select a.vacancyID
                            from vacancies a
                            INNER JOIN companies b
                            ON a.companyID = b.companyID
                            INNER JOIN skillsforvacancy c
                            ON a.vacancyID = c.vacancyID
                            INNER JOIN skills d
                            ON c.skillID = d.skillID
                            WHERE d.skillTitle = \"{$userSkillRow['skillTitle']}\"
                            ORDER BY timeAdded DESC;";
                            $vacanciesResult = $conn -> query($vacanciesSQL);
                            //Get all vacancies with the users skills
                            if(mysqli_num_rows($vacanciesResult) != 0) {
                                while($vacanciesRow = $vacanciesResult->fetch_assoc()){
                                    $vacancies[] = $vacanciesRow['vacancyID'];
                                }   
                            } 
                        }
                        // get the rest of the vacancies that dont have the users skills
                        $vacancies = array_unique($vacancies);
                        $numOfVacancies = count($vacancies);
                        $y = 0;
                        $vacanciesWithNoSkillsSQL = "select vacancyID, timeAdded
                            from vacancies
                            WHERE NOT EXISTS (SELECT vacancyID FROM skillsforvacancy where skillsforvacancy.vacancyID = vacancies.vacancyID)";
                        $otherVacanciesSQL = "UNION
                            select a.vacancyID, a.timeAdded
                            from vacancies a
                            INNER JOIN companies b
                            ON a.companyID = b.companyID
                            INNER JOIN skillsforvacancy c
                            ON a.vacancyID = c.vacancyID
                            INNER JOIN skills d
                            ON c.skillID = d.skillID
                            WHERE ";
                            foreach($skills as $skill) {
                                $otherVacanciesSQL .= "d.skillTitle != \"{$skill}\" AND ";
                            }
                            foreach($vacancies as $vacancy) {
                                if(++$y === $numOfVacancies) $otherVacanciesSQL .= "a.vacancyID != \"{$vacancy}\" ";
                                else $otherVacanciesSQL .= "a.vacancyID != \"{$vacancy}\" AND ";
                            }
                        $otherVacanciesSQL .= "ORDER BY timeAdded DESC;";
                        $vacanciesWithNoSkillsSQL .= $otherVacanciesSQL;
                        $otherVacanciesResult = $conn -> query($vacanciesWithNoSkillsSQL);
                        

                        if(mysqli_num_rows($otherVacanciesResult) != 0) {
                            while($otherVacanciesRow = $otherVacanciesResult->fetch_assoc()){
                                $allVacancies[] = $otherVacanciesRow['vacancyID'];
                            }   
                        }
                        if (!empty($vacancies)) {
                            $vacancies = array_unique($vacancies);
                            foreach($vacancies as $vacancy) {
                                $vacancySQL = "select a.vacancyTitle, a.vacancyDescription, a.requiredExperience, a.role, a.timeAdded, b.companyName, a.vacancyID, b.companyID
                                from vacancies a
                                INNER JOIN companies b
                                ON a.companyID = b.companyID
                                WHERE a.vacancyID = {$vacancy}
                                ORDER BY timeAdded DESC;";
                                $vacancyResult = $conn -> query($vacancySQL);
                                
                                while($row = $vacancyResult->fetch_assoc())
                                {   
                                    $skillsNeeded = array();
                                    $skillsSql = "select a.skillTitle, a.skillDescription
                                    from skills a
                                    INNER JOIN skillsforvacancy b
                                    ON a.skillID = b.skillID
                                    INNER JOIN vacancies c
                                    ON b.vacancyID = c.vacancyID
                                    WHERE c.vacancyID = {$vacancy}";
                                    $skillsResult = $conn -> query($skillsSql);
                                    while($skillsRow = $skillsResult -> fetch_assoc()) {
                                        $skill = array('skillTitle' => $skillsRow['skillTitle'], 'skillDesc' => $skillsRow['skillDescription']);
                                        $skillsNeeded[] = $skill;
                                    }
                                    $counter++;
            
                                    print "<div class='container vacancy'>
                                                <div class='row'>
                                                    <div class='col-12' >
                                                        <a class='head vacancyDetails text-lg-center' href='company.php?companyID={$row['companyID']}'><b><p>{$row['companyName']}</p></b></a>
                                                        <p class='vacancyDetails text-left'><b>Title: </b>{$row['vacancyTitle']}</p>
                                                        <p class='vacancyDetails text-left'><b>Description: </b>{$row['vacancyDescription']}</p>
                                                        <p class='vacancyDetails text-left'><b>Role: </b>{$row['role']}</p>
                                                        <p class='vacancyDetails text-left'><b>Req. Experience: </b>{$row['requiredExperience']}</p>
                                                        <button class='showskills' onClick='showSkills({$counter})'>Show Skills</button>";
                                                    
                                                    $loopedJobSQL = "select * from looped where userID = {$_SESSION['user']} AND companyID = {$row['companyID']} AND vacancyID = {$row['vacancyID']};";
                                                    $loopedJobResult = $conn -> query($loopedJobSQL);
                                                    $loopedJobRow = $loopedJobResult->fetch_assoc();
                                                    if($loopedJobRow) {
                                                        print "<img class='img-fluid' src='images/cancel_loop.png' alt='logo here' style='height: 12%;' onClick='unLoopJob(${row['vacancyID']}, ${row['companyID']})'></img>";
                                                    } else {
                                                        print "<img class='img-fluid' src='images/Like_Loop_small.png' alt='logo here'  onClick='loopJob(${row['vacancyID']}, ${row['companyID']})'></img>";
                                                    }
                                                    print "<div id='myModal{$counter}' class='modal'>
                                                            <!-- Modal content -->
                                                            <div class='modal-content'>
                                                                <span class='close{$counter} close'>&times;</span>
                                                                <table class='skillsTable'>
                                                                <thead>
                                                                    <tr>
                                                                        <th>Skills Required</th>
                                                                        <th>Skills Description</th>
                                                                    </tr>
                                                                </thead>";
                                                    if(!empty($skillsNeeded)) {
                                                        foreach ($skillsNeeded as $row) 
                                                        {   
                                                            echo '<tr>';
                                                            echo '<td>' . $row['skillTitle'] . '</td>';
                                                            echo '<td>' . $row['skillDesc'] . '</td>';
                                                            echo '</tr>';
                                                        }
                                                    } else echo "<tr><td colspan='3'>No Specific Skills Required</td></tr>";
                                                    print "</table></div></div>";
                                                    
                                                    
                                                    
                                                    print "</div></div></div>";
                                                    
                                }
                            }
                        }
                    } else {
                        $vacanciesSQL = "select *
                        from vacancies 
                        ORDER BY timeAdded DESC;";
                        $vacanciesResult = $conn -> query($vacanciesSQL);
                        if(mysqli_num_rows($vacanciesResult) != 0) {
                            while($vacanciesRow = $vacanciesResult->fetch_assoc()){
                                $allVacancies[] = $vacanciesRow['vacancyID'];
                            }   
                        }
                    }
                    print "<h2 style='color: black'>Other Jobs</h2>";
                    $allVacancies = array_unique($allVacancies);
                    if (!empty($allVacancies)) {
                        $vacancies = array_unique($vacancies);
                        foreach($allVacancies as $vacancy) {
                            $vacancySQL = "select a.vacancyTitle, a.vacancyDescription, a.requiredExperience, a.role, a.timeAdded, b.companyName, a.vacancyID, b.companyID
                            from vacancies a
                            INNER JOIN companies b
                            ON a.companyID = b.companyID
                            WHERE a.vacancyID = {$vacancy}
                            ORDER BY timeAdded DESC;";
                            $vacancyResult = $conn -> query($vacancySQL);
                            
                            while($row = $vacancyResult->fetch_assoc())
                            {   
                                $skillsNeeded = array();
                                $skillsSql = "select a.skillTitle, a.skillDescription
                                from skills a
                                INNER JOIN skillsforvacancy b
                                ON a.skillID = b.skillID
                                INNER JOIN vacancies c
                                ON b.vacancyID = c.vacancyID
                                WHERE c.vacancyID = {$vacancy}";
                                $skillsResult = $conn -> query($skillsSql);
                                while($skillsRow = $skillsResult -> fetch_assoc()) {
                                    $skill = array('skillTitle' => $skillsRow['skillTitle'], 'skillDesc' => $skillsRow['skillDescription']);
                                    $skillsNeeded[] = $skill;
                                }
                                $counter++;
        
                                print "<div class='container vacancy'>
                                            <div class='row'>
                                                <div class='col-12' >
                                                    <a class='head vacancyDetails text-lg-center' href='company.php?companyID={$row['companyID']}'><b><p>{$row['companyName']}</p></b></a>
                                                    <p class='vacancyDetails text-left'><b>Title: </b>{$row['vacancyTitle']}</p>
                                                    <p class='vacancyDetails text-left'><b>Description: </b>{$row['vacancyDescription']}</p>
                                                    <p class='vacancyDetails text-left'><b>Role: </b>{$row['role']}</p>
                                                    <p class='vacancyDetails text-left'><b>Req. Experience: </b>{$row['requiredExperience']}</p>
                                                    <button class='showskills' onClick='showSkills({$counter})'>Show Skills</button>";
                                                
                                                $loopedJobSQL = "select * from looped where userID = {$_SESSION['user']} AND companyID = {$row['companyID']} AND vacancyID = {$row['vacancyID']};";
                                                $loopedJobResult = $conn -> query($loopedJobSQL);
                                                $loopedJobRow = $loopedJobResult->fetch_assoc();
                                                if($loopedJobRow) {
                                                    print "<img class='img-fluid' src='images/cancel_loop.png' alt='logo here' style='height: 12%;' onClick='unLoopJob(${row['vacancyID']}, ${row['companyID']})'></img>";
                                                } else {
                                                    print "<img class='img-fluid' src='images/Like_Loop_small.png' alt='logo here'  onClick='loopJob(${row['vacancyID']}, ${row['companyID']})'></img>";
                                                }
                                                print "<div id='myModal{$counter}' class='modal'>
                                                        <!-- Modal content -->
                                                        <div class='modal-content'>
                                                            <span class='close{$counter} close'>&times;</span>
                                                            <table class='skillsTable'>
                                                            <thead>
                                                                <tr>
                                                                    <th>Skills Required</th>
                                                                    <th>Skills Description</th>
                                                                </tr>
                                                            </thead>";
                                                if(!empty($skillsNeeded)) {
                                                    foreach ($skillsNeeded as $row) 
                                                    {   
                                                        echo '<tr>';
                                                        echo '<td>' . $row['skillTitle'] . '</td>';
                                                        echo '<td>' . $row['skillDesc'] . '</td>';
                                                        echo '</tr>';
                                                    }
                                                } else echo "<tr><td colspan='3'>No Specific Skills Required</td></tr>";
                                                print "</table></div></div>";
                                                
                                                
                                                
                                                print "</div></div></div>";
                                                
                            }
                        }
                    } else print "<h1>No Vacanies To Suggest.</h1>";
                    
                }
                $conn->close();
            ?>
        </div>
    </body>
</html>