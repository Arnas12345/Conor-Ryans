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

            $sql = "INSERT INTO connections (userIDFirst, userIDSecond, CreationDate, Status)
            VALUES ('{$currentUser}', '{$secondUserID}', Now(), 'Pending')";

            if ($conn->query($sql) === TRUE) {
                header( "Location: profile.php?userID=$secondUserID" );

            } 
            else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            header( "Location: profile.php?userID=$secondUserID" );
            $conn->close();
        ?>
    </body>
</html>