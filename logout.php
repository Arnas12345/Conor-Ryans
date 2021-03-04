<html>
    <head>
        <title>Logout</title>
    </head>

    <body>
        <?php
            session_start();
            
            include ("serverConfig.php");

            $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
            if ($conn -> connect_error) {
                die("Connection failed:" .$conn -> connect_error);
            }

            session_destroy();

            $conn->close();
            header( "Location: login.html" );
        ?>
    </body>
</html>