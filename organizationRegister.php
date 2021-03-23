<html>
    <body>
        <?php

            session_start();

            include ("serverConfig.php");
            $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
            if ($conn -> connect_error) {
                die("Connection failed:" .$conn -> connect_error);
            }

            $companyName = $_POST['name'];
            $companyEmail = $_POST['email'];
            $hashedPass = password_hash($_POST['pass'], PASSWORD_DEFAULT);

            $sql = "INSERT INTO companies (companyName, email, password)
            VALUES ('{$companyName}', '{$companyEmail}', '{$hashedPass}')";

            if ($conn->query($sql) === TRUE) {
                
                $sql = "select * from companies where email=\"{$companyEmail}\";";
                $result = $conn -> query($sql);
                $row = $result->fetch_assoc();
                $companyID = $row["companyID"];

                $_SESSION['company'] = $companyID;
                $_SESSION['companyName'] = $companyName;
                $_SESSION['loggedin'] = true;
                header( "Location: organizationHome.php" );

            } 
            else {
                echo "Error: " . $sql . "<br>" . $conn->error;
                header( "Location: index.php" );
            }

            $conn->close();
        ?>
    </body>
</html>