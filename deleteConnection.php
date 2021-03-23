<html>
    <body>
        <?php

            session_start();

            include ("serverConfig.php");
            $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
            if ($conn -> connect_error) {
                die("Connection failed:" .$conn -> connect_error);
            }

            $secondUserID = $_GET['id'];
            $currentUser = $_SESSION['user'];

            $connectionsSQL = "DELETE FROM connections WHERE userIDFirst=\"{$currentUser}\" AND userIDSecond=\"{$secondUserID}\";";

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