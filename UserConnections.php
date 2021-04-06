<html>
    <head>
        <title>Loop : Search</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/UserConnections.css?v=<?php echo time(); ?>">

    </head>
    <body>
        <?php include("headerTemplate.html"); ?>
        <h1 class="page-header">My Connections</h1>
        <hr>
       
        <div class="page-box">
            <?php
                include ("validateLoggedIn.php");
                include ("serverConfig.php");
                
                session_start();

                $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
                if ($conn -> connect_error) {
                    die("Connection failed:" .$conn -> connect_error);
                }

                $sql = "SELECT a.userID, a.email, a.username
                        FROM users a 
                        INNER JOIN connections b
                        ON a.userID = b.userIDSecond
                        WHERE b.userIDFirst = {$_SESSION['user']};";
                $result = $conn -> query($sql);
                
                    while($row = $result->fetch_assoc())
                    {   
                        print "<div class='user'>";
                        print "<a class='userDetails text-center' href='profile.php?userID={$row['userID']}'><b><p>{$row['username']} - {$row['email']}</p></b></a>";
                        print "</div>";
                    }
                    $conn->close();

            ?>
        </div>
    </body>
</html>