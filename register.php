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

            $username = $_POST['name'];
            $email = $_POST['email'];
            $hashedPass = password_hash($_POST['pass'], PASSWORD_DEFAULT);

            $sql = "INSERT INTO Users (username, email, password)
            VALUES ('{$username}', '{$email}', '{$hashedPass}')";

            if ($conn->query($sql) === TRUE) {
                
                $sql = "select * from Users where email=\"{$email}\";";
                $result = $conn -> query($sql);
                $row = $result->fetch_assoc();
                $userID = $row["userID"];

                $_SESSION['user'] = $userID;
                $_SESSION['username'] = $username;
                $_SESSION['loggedin'] = true;
                header( "Location: home.php" );

            } 
            else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $conn->close();

            header( "Location: home.php" );
        ?>
    </body>
</html>