<html>
    <body>
        <?php

            include ("validateAdmin.php");
            include ("serverConfig.php");

            $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
            if ($conn -> connect_error) {
                die("Connection failed:" .$conn -> connect_error);
            }

            $vacancyToDelete = $_GET['id'];

            $connectionsSQL = "DELETE FROM vacancies WHERE vacancyID={$vacancyToDelete};";

            if ($conn->query($connectionsSQL) === TRUE) {
                echo "Sucessful";
                header( "Location: adminRemoveVacancy.php" );

            } 
            else {
                echo "Error: " . $connectionsSQL . "<br>" . $conn->error;
            }

            header( "Location: adminRemoveVacancy.php" );
            $conn->close();
        ?>
    </body>
</html>