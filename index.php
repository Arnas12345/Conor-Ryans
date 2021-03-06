<?php
    session_start();

    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == 1) {
        header( "Location: home.php" );
    } else {
    header( "Location: index.html" );
    }
?>
<html>
    <head>
        <title>Loop : Register</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/register.css?v=<?php echo time() ?>">
    </head>
    <body>
        <div class="side">
            <div class="container_logo">
                <img class="loop_logo" src="images/Loop_logo.png" alt="logo here" height="50%" width="50%"></img>
                <h1 class="newHere"><b>Welcome Back</b></h1>
                <button type="button" class="signUp" onClick="location.href='login.html'">Sign In</button>
                <br>
                <a href="login.html" style="color: white;">Are you a Organization? Click here</a>
            </div>
        </div>
        <div class="login">
            <h1 style="text-align: left; padding-left: 45%">Create Account</h1>
            <form method="post" action="register.php">
                <input class="input" type="text" name="name" pattern="[a-zA-z\s]{2,100}" title="Must be between 2 and 100 chars" placeholder="Full Name" required><br>
                <input class="input" type="email" name="email" placeholder="Email" required><br>
                <input class="input" type="password" name="pass" placeholder="Password"
                    pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,16}" title="Must be between 6 and 16 chars and include xxx" required><br>
                <input class="input" type="password" name="passConfirm" placeholder="Confirm Password"
                    pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,16}" title="Must be between 6 and 16 chars and include xxx" required><br>
                <input class="submitButton" type="submit" value="Register">
            </form>
        </div>
    </body>
</html>