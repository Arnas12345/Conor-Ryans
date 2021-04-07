<html>
    <head>
        <title>Loop : Login</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/login.css?v=<?php echo time() ?>">
    </head>
    <body>
        <div class="side">
            <div class="container_logo">
                <img class="loop_logo" src="images/Loop_logo.png" alt="logo here" height="50%" width="50%"></img>
                <h1 class="newHere">New Here?</h1>
                <button type="button" class="signUp" style="background-color: rgb(191, 191, 191);" onClick="location.href='organizationRegistration.html'">Sign Up</button>
                <br>
                <a href="login.php" style="color: white;">Are you a User? Click here</a>
            </div>
        </div>
        <div class="login">
            <h1 style="text-align: left; padding-left: 5%">Organization Login</h1>
            <form method="post" action="organizationLogin.php">
                <input class="input" type="email" name="email" placeholder="Organization Email" style="color: white" required><br>
                <input class="input" type="password" name="pass" placeholder="Password" style="color: white" required><br>
                <input class="submitButton" name="submit" type="submit" value="Login">
            </form>
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

        $companyEmail = $_POST['email'];
        $password = $_POST['pass'];
        
        $sql = "select * from companies where email=\"{$companyEmail}\";";
        $result = $conn -> query($sql);
        $row = $result->fetch_assoc();
        $companyID = $row["companyID"];
        $sqlEmail = $row["email"];
        $sqlPass = $row["password"];

        function emailMatches ($inputEmail, $DBEmail) {
            return strcasecmp($inputEmail, $DBEmail);
        }

        if(emailMatches($email, $sqlEmail) == 0 && password_verify($password, $sqlPass)) {
            $_SESSION['company'] = $companyID;
            $_SESSION['companyName'] = $row['companyName'];
            $_SESSION['loggedin'] = true;
            header( "Location: organizationHome.php" );
        }
        else {
            print "<h4 class='alert' style='text-align:center;'>
                        You have entered the wrong email or password please try again.
                    </h4>";
        }

        $conn->close(); 
        
    }
?>