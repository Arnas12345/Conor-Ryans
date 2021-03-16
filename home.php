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

                $sql = "select a.vacancyTitle, a.vacancyDescription, a.requiredExperience, a.role, a.timeAdded, b.companyName
                from Vacancies a
                INNER JOIN companies b
                ON a.companyID = b.companyID;";
                $result = $conn -> query($sql);
                
                if(mysqli_num_rows($result) != 0) {
                    while($row = $result->fetch_assoc())
                    {   
                        print "<div class='vacancy'>";
                        print "<div class='container'>
                                    <div class='row'>
                                        <div class='col-4' >
                                            <img class='job_logo' src='images/job-icon.jpg' alt='logo here'></img>
                                        </div>
                                        <div class='col-8' >
                                        <p class='vacancyDetails'><b><u>{$row['companyName']}</u></b></p>
                                        <p class='vacancyDetails'><b>Title: </b>{$row['vacancyTitle']}</p>
                                        <p class='vacancyDetails'><b>Description: </b>{$row['vacancyDescription']}</p>
                                        <p class='vacancyDetails'><b>Role: </b>{$row['role']}</p>
                                        <p class='vacancyDetails'><b>Req. Experience: </b>{$row['requiredExperience']}</p>
                                        </div>
                                    </div>
                                </div>";
                        print "</div>";
                    }
                } else {
                    print "<h1>No Vacanies Found.</h1>";
                }
                    $conn->close();
            ?>
        </div>
    </body>
</html>