<html>
    <head>
        <title>Loop : Search</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/home.css?v=<?php echo time(); ?>">
        <script type="text/javascript">
            function makeConnection(variable) {
                window.location.href= 'addConnection.php?id=' + variable;
            }
        </script>
        <script type="text/javascript">
            function deleteConnection(variable) {
                if (confirm("Are you sure you want to delete this connection?") == true) {
                    window.location.href= 'deleteConnection.php?id=' + variable;
                };
            }
        </script>
    </head>
    <body>
        <div class="container_logo" style="text-align: center;">
            <img src="images/Loop_logo.png" alt="logo here" height="20%" weight="20%"></img>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm" >
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
        <h1 class="page-header">Search Page</h1>
        <hr>
        <form class="search" method="post" action="search.php">
            <select class="select" name="selectVal">
                <option value="name">Name</option>
                <option value="skill">Skill</option>
                <option value="previousHistory">Previous History</option>
                <option value="currentlyEmployed">Currently Employed</option>
            </select>
            <input class="input" type="text" name="value" placeholder="Search for User">
            <input class="submit" type="submit" value="Search">
        </form>
        <div class="page-box">
            <?php
                session_start();
                include ("serverConfig.php");
                $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
                if ($conn -> connect_error) {
                    die("Connection failed:" .$conn -> connect_error);
                }

                if ($_POST["selectVal"] == "name") {
                    $name = $_POST["value"];

                    $sql = "select * from Users where username LIKE \"{$name}%\" AND userID != \"{$_SESSION['user']}%\";";
                    $result = $conn -> query($sql);
                    if($result -> fetch_assoc()) {
                        while($row = $result->fetch_assoc())
                        {
                            print "<a href='profile.php?userID={$row['userID']}'>{$row['email']}</a>";
                            $connectionsSQL = "select * from Connections where userIDFirst = \"{$_SESSION['user']}%\" AND userIDSecond = \"{$row['userID']}%\";";
                            $result2 = $conn -> query($connectionsSQL);
                            $connectionsRow = $result2->fetch_assoc();
                            if($connectionsRow) {
                                print "<img src='images/Loop_logo.png' alt='logo here' height='20%' weight='20%' onClick='deleteConnection({$row['userID']})'></img><br>";
                            } else {
                                print "<img src='images/connection.png' alt='logo here' height='20%' weight='20%' onClick='makeConnection({$row['userID']})'></img><br>";
                            }
                        }
                        $conn->close();
                    } else {
                        print "<h1>No Users found.</h1>";
                    }
                }

                if ($_POST["selectVal"] == "skill") {
                    $skill = $_POST["value"];
                    $SQL = "select a.userID, a.email
                            from users a
                            INNER JOIN userSkills b
                            ON a.userID = b.userID
                            INNER JOIN skills c
                            ON b.skillID = c.skillID
                            WHERE a.userID != {$_SESSION['user']} AND c.skillTitle LIKE \"{$skill}%\";";
                    $skillResult = $conn -> query($SQL);
                    if(mysqli_num_rows($skillResult) != 0) {
                            while($skillRow = $skillResult->fetch_assoc()) {
                                print "<a href='profile.php?userID={$skillRow['userID']}'>{$skillRow['email']}</a>";
                                $connectionsSQL = "select * from Connections where userIDFirst = \"{$_SESSION['user']}%\" AND userIDSecond = \"{$skillRow['userID']}%\";";
                                $result2 = $conn -> query($connectionsSQL);
                                $connectionsRow = $result2->fetch_assoc();
                                if($connectionsRow) {
                                    print "<img src='images/Loop_logo.png' alt='logo here' height='20%' weight='20%' onClick='deleteConnection({$skillRow['userID']})'></img><br>";
                                } else {
                                    print "<img src='images/connection.png' alt='logo here' height='20%' weight='20%' onClick='makeConnection({$skillRow['userID']})'></img><br>";
                                }
                            }
                    } else {
                        print "<h1>No Users found with the skill \"{$skill}\".</h1>";
                    }
                    $conn->close();
                }

                if ($_POST["selectVal"] == "currentlyEmployed") {
                    $currentlyEmployed = $_POST["value"];
                    $SQL = "select a.userID, a.email
                            from users a
                            INNER JOIN companies b
                            ON a.companyID = b.companyID
                            WHERE a.userID != {$_SESSION['user']} AND b.companyName LIKE \"{$currentlyEmployed}%\";";
                    $currentlyEmployedResult = $conn -> query($SQL);
                    if(mysqli_num_rows($currentlyEmployedResult) != 0) {
                            while($currentlyEmployedRow = $currentlyEmployedResult->fetch_assoc()) {
                                print "<a href='profile.php?userID={$currentlyEmployedRow['userID']}'>{$currentlyEmployedRow['email']}</a>";
                                $connectionsSQL = "select * from Connections where userIDFirst = \"{$_SESSION['user']}%\" AND userIDSecond = \"{$currentlyEmployedRow['userID']}%\";";
                                $result2 = $conn -> query($connectionsSQL);
                                $connectionsRow = $result2->fetch_assoc();
                                if($connectionsRow) {
                                    print "<img src='images/Loop_logo.png' alt='logo here' height='20%' weight='20%' onClick='deleteConnection({$currentlyEmployedRow['userID']})'></img><br>";
                                } else {
                                    print "<img src='images/connection.png' alt='logo here' height='20%' weight='20%' onClick='makeConnection({$currentlyEmployedRow['userID']})'></img><br>";
                                }
                            }
                    } else {
                        print "<h1>No Users found with the skill \"{$currentlyEmployed}\".</h1>";
                    }
                    $conn->close();
                }

                if ($_POST["selectVal"] == "previousHistory") {
                    $previousHistory = $_POST["value"];
                    $previousHistorySQL = "select * from Companies where companyName LIKE \"{$previousHistory}%\";";
                    $previousHistoryResult = $conn -> query($previousHistorySQL);
                    $previousHistoryRow = $previousHistoryResult->fetch_assoc();
                    if($previousHistoryRow) {
                        $jobHistorySQL = "select * from Jobhistory where companyID = \"{$previousHistoryRow['companyID']}%\";";
                        $jobHistoryResult = $conn -> query($jobHistorySQL);
                        while($jobHistoryRow = $jobHistoryResult->fetch_assoc()) 
                        {
                            $usersSql = "select * from Users where companyID =\"{$jobHistoryRow["userID"]}%\";";
                            $usersResult = $conn -> query($usersSql);
                            while($usersRow = $usersResult->fetch_assoc())
                            {
                                if ($_SESSION['user'] != $usersRow['userID'])
                                {
                                    print "<a href='profile.php?userID={$usersRow['userID']}'>{$usersRow['email']}</a>";
                                    $connectionsSQL = "select * from Connections where userIDFirst = \"{$_SESSION['user']}%\" AND userIDSecond = \"{$usersRow['userID']}%\";";
                                    $result2 = $conn -> query($connectionsSQL);
                                    $connectionsRow = $result2->fetch_assoc();
                                    if($connectionsRow) {
                                        print "<img src='images/Loop_logo.png' alt='logo here' height='20%' weight='20%' onClick='deleteConnection({$usersRow['userID']})'></img><br>";
                                    } else {
                                        print "<img src='images/connection.png' alt='logo here' height='20%' weight='20%' onClick='makeConnection({$usersRow['userID']})'></img><br>";
                                    }
                                } else {
                                    print "<h1>No Users found.</h1>";
                                }
                            }
                        }
                    }
                    else {
                        print "<h1>No Users found with the previous job history at \"{$previousHistory}\".</h1>";
                    }
                    $conn->close();
                }
            ?>
        </div>
    </body>
</html>