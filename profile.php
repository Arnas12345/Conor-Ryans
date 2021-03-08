<html>
    <head>
        <title>Loop : User Profie</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/home.css?v=<?php echo time() ?>">
    </head>
    <body>
        <div class="container_logo" style="text-align: center;">
            <img src="images/Loop_logo.png" alt="logo here" height="20%" weight="20%"></img>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm">
                    <a class="header"><b>HomeFeed1</b></a>
                </div>
                <div class="col-sm" >
                    <a class="header"><b>HomeFeed2</b></a>
                </div>
                <div class="col-sm">
                    <a class="header"><b>HomeFeed3</b></a>
                </div>
                <div class="col-sm">
                    <a class="header"><b>HomeFeed4</b></a>
                </div>
                <div class="col-sm">
                    <a class="header" href="logout.php"><b>Log Out</b></a>
                </div>
            </div>
        </div>
        <hr>
        <form method="post" action="search.php">
            <select name="selectVal">
                <option value="name">Name</option>
                <option value="skill">Skill</option>
                <option value="previousHistory">Previous History</option>
                <option value="currentlyEmployed">Currently Employed</option>
            </select>
            <input type="text" name="value" placeholder="Search for User">
            <input type="submit" value="Search">
        </form>
        <?php

            session_start();

            include ("serverConfig.php");
            $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
            if ($conn -> connect_error) {
                die("Connection failed:" .$conn -> connect_error);
            }

            $userID = $_GET["userID"];

            $sql = "select * from Users where userID=\"{$userID}\";";
            $result = $conn -> query($sql);
            $row = $result->fetch_assoc();
            echo "User Name :" . $row['username'] . "<br>";
            echo "Email :" . $row['email'];
            $conn->close();

        ?>

    </body>
</html>