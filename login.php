<html>
    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/login.css?v=<?php echo time(); ?>">
    </head>
    <body>
        <div class="wrapper">
            <div class="banner">
                    <div class="login">
                        <h2 style="text-align: left;">Login</h2>
                        <form method="post" action="login.php">
                            <input class="input" type="email" name="email" placeholder="Email" required><br>
                            <input class="input" type="password" name="pass" placeholder="Password" required><br>
                            <input class="button" name="submit" type="submit" value="Login" style="margin-left: 27%;">
                        </form>
                            <div class="buttons">
                                <div class="register">
                                    <h5><u>New Here?</u></h5>
                                    <button class="button" onClick="location.href='register.html'">Register</button>
                                </div>
                            </div>
                        <a href="organizationLogin.php" style="color: white;">Are you a Organization? Click here</a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

<?php

    if(isset($_POST['submit'])) {

        include ("serverConfig.php");
        
        $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
        if ($conn -> connect_error) {
            die("Connection failed:" .$conn -> connect_error);
        }

        session_start();

        $email = $_POST['email'];
        $password = $_POST['pass'];
        
        $sql = "select * from users where email=\"{$email}\";";
        $result = $conn -> query($sql);
        $row = $result->fetch_assoc();
        $userID = $row["userID"];
        $sqlEmail = $row["email"];
        $sqlPass = $row["password"];
        $isAdmin = $row["Admin"];

        function emailMatches ($inputEmail, $DBEmail) {
            return strcasecmp($inputEmail, $DBEmail) == 0;
        }
        
        if(emailMatches($email, $sqlEmail) && $isAdmin !== NULL && password_verify($password, $sqlPass) ) {
            $_SESSION['user'] = $userID;
            $_SESSION['username'] = $row['username'];
            $_SESSION['loggedin'] = true;
            $_SESSION['admin'] = true;
            header( "Location: admin.php" );
        }
        else if(emailMatches($email, $sqlEmail)) {
        // else if(emailMatches($email, $sqlEmail) && password_verify($password, $sqlPass)) {
            $_SESSION['user'] = $userID;
            $_SESSION['username'] = $row['username'];
            $_SESSION['loggedin'] = true;
            header( "Location: home.php" );
        }
        else {
            print "<h4 class='alert' style='text-align:center;'>
                        You have entered the wrong email or password please try again.
                    </h4>";
        }

        $conn->close(); 
    }

?>