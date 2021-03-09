<html>
    <head>
        <title>Loop : User Profie</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/home.css?v=<?php echo time() ?>">
    </head>
    <body>
        <?php include("headerTemplate.html"); ?>
        <h1 class="page-header">Profile Page</h1>
        <hr>
        <?php
            session_start();

            include ("serverConfig.php");
            $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
            if ($conn -> connect_error) {
                die("Connection failed:" .$conn -> connect_error);
            }

            $userID = $_GET["userID"];

            $sql = "select * from Users where userID=\"{$userID}\";";
            $result = $conn -> query($sql);
            $row = $result->fetch_assoc();
            echo "User Name :" . $row['username'] . "<br>";
            echo "Email :" . $row['email'];
            $conn->close();

        ?>

    </body>
</html>