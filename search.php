<html>
    <head>
        <title>Loop : Search</title>
        <link rel="stylesheet" type="text/css" href="css/index.css?v=<?php echo time() ?>">
    </head>
    <body>
        <?php
            session_start();
            include ("serverConfig.php");
            $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
            if ($conn -> connect_error) {
                die("Connection failed:" .$conn -> connect_error);
            }

            $name = $_POST["name"];

            $sql = "select * from Users where username LIKE \"{$name}%\";";
            $result = $conn -> query($sql);
            while($row = $result->fetch_assoc())
            {
                print "<a href='profile.php?link={$row['userID']}'>{$row['email']}</a><br>";
            }
            $conn->close();

        ?>
    </body>
</html>