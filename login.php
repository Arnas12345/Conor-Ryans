<html>
    <head>
        <title>Login</title>
    </head>
    <body>
        <?php
            
            session_start();

            include ("serverConfig.php");
            $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
            if ($conn -> connect_error) {
                die("Connection failed:" .$conn -> connect_error);
            }

            $email = $_POST['email'];
            $password = $_POST['pass'];
            echo $email;
            $sql = "select * from Users where email=\"{$email}\";";
            $result = $conn -> query($sql);
            $row = $result->fetch_assoc();
            $userID = $row["userID"];
            $sqlEmail = $row["email"];
            $sqlPass = $row["password"];

            function emailMatches ($inputEmail, $DBEmail) {
                return strcasecmp($inputEmail, $DBEmail);
            }

            if(emailMatches($email, $sqlEmail) == 0 && password_verify($password, $sqlPass)) {
                
                $_SESSION['user'] = $userID;
               // $_SESSION['username'] = $row['username'];
                header( "Location: home.php" );
            }
            else {
                header( "Location: login.html" );
            }

            $conn->close();
            
            ?>
    </body>
</html>