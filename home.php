<?php
    session_start();

    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == 1) {
    } else {
    header( "Location: login.html" );
    }
?>

<html>
    <head>
        <title>Loop : Home</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/home.css?v=<?php echo time(); ?>">
    </head>
    <body>
        <div class="container_logo" style="text-align: center;">
            <img src="images/Loop_logo.png" alt="logo here" height="20%" weight="20%"></img>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm" >
                    <a class="header">HomeFeed1</a>
                </div>
                <div class="col-sm" >
                    <a class="header">HomeFeed2</a>
                </div>
                <div class="col-sm">
                    <a class="header">HomeFeed3</a>
                </div>
                <div class="col-sm">
                    <a class="header">HomeFeed4</a>
                </div>
                <div class="col-sm">
                    <a class="header" href="logout.php">Log Out</a>
                </div>
            </div>
        </div>
        <hr>
        <form method="post" action="search.php">
            <select name="selectVal">
                <option value="name">Name</option>
                <option value="skill">Skill</option>
                <option value="previousHistory">Previous History</option>
            </select>
            <input type="text" name="value" placeholder="Search for User">
            <input type="submit" value="Search">
        </form>
        <h1>Welcome <?php echo $_SESSION['username']; ?> </h1>
    </body>
</html>