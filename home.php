<?php 
    session_start();

    if(!isset($_SESSION['user'])) {
        header( "Location: login.php" );
    }
?>

<html>
    <head>
        <title>Loop : Home</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/index.css?v=<?php echo time(); ?>">
    </head>
    <body>
        
        <h1>Welcome <?php echo $_SESSION['user']; ?> </h1>

        <div style="display: flex;">
            <div class="container_logo" style="flex: 25%; text-align: center;">
                <img src="images/Loop_logo.png" alt="logo here" height="100" weight="100"></img>
            </div>
            
            <div class="container" style="flex: 75%">
                <div class="row" style="padding-top: 25px;">
                    <div class="col-3" >
                        <a class="header">HomeFeed</a>
                    </div>
                    <div class="col-3" >
                        <a class="header">HomeFeed</a>
                    </div>
                    <div class="col-3">
                        <a class="header">HomeFeed</a>
                    </div>
                    <div class="col-3">
                        <a class="header" href="logout.php">Log Out</a>
                    </div>
                
                </div>
            </div>
        </div>
        <form method="post" action="search.php">
            <input type="text" name="name" placeholder="Search for student">
            <input type="submit" value="Search">
        </form>
    </body>
</html>