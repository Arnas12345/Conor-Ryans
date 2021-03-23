<html>
    <head>
        <title>Loop : Search</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/UserConnections.css?v=<?php echo time(); ?>">

    </head>
    <body>
        <?php
            session_start();
            include ("serverConfig.php");
            $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
            if ($conn -> connect_error) {
                die("Connection failed:" .$conn -> connect_error);
            }

            
            $vacancyID = $_GET['vacancyID'];
            $companyID = $_GET['companyID'];
            $currentUser = $_SESSION['user'];

            $connectionsSQL = "DELETE FROM looped WHERE userID = {$currentUser} AND companyID = {$companyID} AND vacancyID = {$vacancyID};";

            if ($conn->query($connectionsSQL) === TRUE) {
                echo "Sucessful";
                header( "Location: home.php" );

            } 
            else {
                echo "Error: " . $connectionsSQL . "<br>" . $conn->error;
            }

            header( "Location: home.php" );
            $conn->close();
        ?>
    </body>
</html>