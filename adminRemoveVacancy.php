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

        function deleteVacancy(variable) {
            if (confirm("Are you sure you want to delete this Vacancy?") == true) {
            window.location.href= 'adminDeleteVacancy.php?id=' + variable;
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
            include ("adminTemplate.html");
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
                
                $sql = "select a.vacancyTitle, a.vacancyDescription, a.requiredExperience, a.role, a.timeAdded, b.companyName, a.vacancyID, b.companyID
                from vacancies a
                INNER JOIN companies b
                ON a.companyID = b.companyID
                ORDER BY timeAdded DESC;";
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
                                    <div class='col-2' ></div>
                                        <div class='col-8' >
                                            
                                            <a class='head vacancyDetails text-lg-center' href='adminCompany.php?companyID={$row['companyID']}'><b><p>{$row['companyName']}</p></b></a>
                                            <p class='vacancyDetails text-left'><b>Title: </b>{$row['vacancyTitle']}</p>
                                            <p class='vacancyDetails text-left'><b>Description: </b>{$row['vacancyDescription']}</p>
                                            <p class='vacancyDetails text-left'><b>Role: </b>{$row['role']}</p>
                                            <p class='vacancyDetails text-left'><b>Req. Experience: </b>{$row['requiredExperience']}</p>
                                            <button type='button' class='btn btn-danger' style='margin-bottom:1%; float:right;' onClick='deleteVacancy({$row['vacancyID']})'>Delete</button>";
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