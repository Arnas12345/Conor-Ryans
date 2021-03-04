<html>
    <head>
        <title>Register</title>
    </head>
    <body>
        <?php
        
            session_start();
            include ("serverConfig.php");
            $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
            if ($conn -> connect_error) {
                die("Connection failed:" .$conn -> connect_error);
            }
            
            $hashedPass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
            $sql = "INSERT INTO Users (username, email, password)
            VALUES ('{$_POST['name']}', '{$_POST['email']}', '{$hashedPass}')";
            if ($conn->query($sql) === TRUE) {
                echo "New account created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            $conn->close();

            header( "Location: home.php" );
        ?>
    </body>
</html>