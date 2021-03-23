<?php
    session_start();

    if (!(isset($_SESSION["loggedin"])) || $_SESSION["loggedin"] == false) {
        header( "Location: login.html" );
    } 

?>

<html>
    <head>
        <title>Loop : Home</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/home.css?v=<?php echo time(); ?>">
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
        <?php include ("headerTemplate.html");?>
        <h1 class="page-header">Job Feed</h1>
        <hr>
        <div class="page-box">
            <h1 style="visibility: hidden">s</h1>
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
                        print "<div class='vacancy'>";
                        print "<div class='container'>
                                    <div class='row'>
                                        <div class='col-4' >
                                            <img class='job_logo' src='images/job-icon.jpg' alt='logo here'></img>
                                        </div>
                                        <div class='col-8' >
                                        <a class='vacancyDetails' href='company.php?companyID={$row['companyID']}'><b><u>{$row['companyName']}</u></b></a>
                                        <p class='vacancyDetails'><b>Title: </b>{$row['vacancyTitle']}</p>
                                        <p class='vacancyDetails'><b>Description: </b>{$row['vacancyDescription']}</p>
                                        <p class='vacancyDetails'><b>Role: </b>{$row['role']}</p>
                                        <p class='vacancyDetails'><b>Req. Experience: </b>{$row['requiredExperience']}</p>
                                        <button onClick='showSkills({$counter})'>Show Skills</button>";
                                        
                                        $loopedJobSQL = "select * from looped where userID = {$_SESSION['user']} AND companyID = {$row['companyID']} AND vacancyID = {$row['vacancyID']};";
                                        $loopedJobResult = $conn -> query($loopedJobSQL);
                                        $loopedJobRow = $loopedJobResult->fetch_assoc();
                                        if($loopedJobRow) {
                                            print "<img class='img-fluid' src='images/job-icon.jpg' alt='logo here' style='height: 10%;' onClick='unLoopJob(${row['vacancyID']}, ${row['companyID']})'></img>";
                                        } else {
                                            print "<img class='img-fluid' src='images/loop_small.png' alt='logo here' style='height: 10%;' onClick='loopJob(${row['vacancyID']}, ${row['companyID']})'></img>";
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
                                        foreach ($skillsNeeded as $row) 
                                        {   
                                            echo '<tr>';
                                            echo '<td>' . $row['skillTitle'] . '</td>';
                                            echo '<td>' . $row['skillDesc'] . '</td>';
                                            echo '</tr>';
                                        }
                                        print "</table></div></div>";
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