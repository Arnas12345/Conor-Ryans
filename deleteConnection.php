<html>
    <body>
        <?php

            include ("validateLoggedIn.php");
            include ("serverConfig.php");
            
            $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
            if ($conn -> connect_error) {
                die("Connection failed:" .$conn -> connect_error);
            }

            $secondUserID = $_GET['id'];
            $currentUser = $_SESSION['user'];

            $connectionsSQL = "DELETE FROM connections WHERE userIDFirst=\"{$currentUser}\" AND userIDSecond=\"{$secondUserID}\" AND Status='Accepted';";
            $secondSQL = "DELETE FROM connections WHERE userIDFirst=\"{$secondUserID}\" AND userIDSecond=\"{$currentUser}\" AND Status='Accepted';";

            if ($conn->query($connectionsSQL) === TRUE && $conn->query($secondSQL) === TRUE) {
                echo "Sucessful";
                header( "Location: UserConnections.php" );

            } 
            else {
                echo "Error: " . $connectionsSQL . "<br>" . $conn->error;
            }

            header( "Location: UserConnections.php" );
            $conn->close();
        ?>
    </body>
</html>