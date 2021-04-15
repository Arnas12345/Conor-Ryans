<html>
    <head>
        <title>Loop : Search</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/UserConnections.css?v=<?php echo time(); ?>">

    </head>
    <body>
        <?php

            include ("validateAdmin.php");
            include ("serverConfig.php");

            $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
            if ($conn -> connect_error) {
                die("Connection failed:" .$conn -> connect_error);
            }

            
            $userToban = $_GET['id'];
            print $userToban;
            $sql = "INSERT INTO banneduser (userID)
            VALUES ('{$userToban}')";

            if ($conn->query($sql) === TRUE) {
               header( "Location: admin.php" );

            } 
            else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            header( "Location: admin.php" );
            $conn->close();
        ?>
    </body>
</html>