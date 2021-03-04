<html>
    <head>
        <title>Loop : Login</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/login.css?v=<?php echo time() ?>">
    </head>
    <body>
        <?php
            include ("serverConfig.php");
            $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
            if ($conn -> connect_error) {
                die("Connection failed:" .$conn -> connect_error);
            }

            session_start();

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
                $_SESSION['username'] = $row['username'];
                $_SESSION['loggedin'] = true;
                header( "Location: home.php" );
            }
            else {
                header( "Location: login.php" );
            }

            $conn->close();
            
        ?>
        <div class="side">
            <div class="container_logo">
                <img src="images/Loop_logo.png" alt="logo here" height="50%"></img>
                <h1 class="newHere">New Here?</h1>
                <button type="button" class="signUp" style="background-color: rgb(191, 191, 191);" onClick="location.href='index.php'">Sign Up</button>
                <br>
                <a href="index.php" style="color: white;">Are you a Organization? Click here</a>
            </div>
        </div>
        <div class="login">
            <h1 style="text-align: left; padding-left: 5%">Login</h1>
            <form method="post" action="#">
                <input class="input" type="email" name="email" placeholder="Email" style="color: white" required><br>
                <input class="input" type="password" name="pass" placeholder="Password" style="color: white"><br>
                <input class="submitButton" type="submit" value="Login">
            </form>
        </div>
    </body>
</html>