<html>
    <head>
        <title>Loop : Search</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/search.css?v=<?php echo time(); ?>">
       
        <script type="text/javascript">
            function unbanUser(variable) {
                if (confirm("Are you sure you want to unban this User?") == true) {
                window.location.href= 'adminUnbanUser.php?id=' + variable;
                };
            }
        </script>
        <script type="text/javascript">
            function BanUser(variable) {
                if (confirm("Are you sure you want to ban this User?") == true) {
                    window.location.href= 'adminBanUser.php?id=' + variable;
                };
            }
        </script>

        <script type="text/javascript">
            function DeleteUser(variable) {
                if (confirm("Are you sure you want to delete this User?") == true) {
                    window.location.href= 'adminDeleteUser.php?id=' + variable;
                };
            }
        </script>

        <script type="text/javascript">
            function BanCompany(variable) {
                if (confirm("Are you sure you want to ban this Company?") == true) {
                    window.location.href= 'adminBanCompany.php?id=' + variable;
                };
            }
        </script>

        <script type="text/javascript">
            function unBanCompany(variable) {
                if (confirm("Are you sure you want to unban this Company?") == true) {
                    window.location.href= 'adminUnbanCompany.php?id=' + variable;
                };
            }
        </script>

        <script type="text/javascript">
            function deleteCompany(variable) {
                if (confirm("Are you sure you want to unban this Company?") == true) {
                    window.location.href= 'adminUnbanCompany.php?id=' + variable;
                };
            }
        </script>
  

    </head>
    <body>
        <h1 class="page-header">Admin Page</h1>
        <hr>
        <div class="nav-links">
            <a class="delete-link" href="flushOldProfilePictures.php"> 
                Delete Old Images
            </a>    
            <a class="logout-link" href="logout.php"> 
                Logout
            </a> 
        </div> 
        <form class="search" method="post" action="admin.php">
            <select class="select" name="selectVal">
                <option value="name">Name</option>
                <option value="companyName">Company Name</option>
                <option value="skill">Skill</option>
                <option value="previousHistory">Previous History</option>
                <option value="currentlyEmployed">Currently Employed</option>
            </select>
            <input class="input" type="text" name="value" placeholder="Search for User">
            <input class="submit" type="submit" value="Search">
        </form>
            
        <div class="page-box">
            <?php

                include ("validateAdmin.php");
                include ("serverConfig.php");

                if(isset($_POST["selectVal"])) {
                    
                    $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
                    if ($conn -> connect_error) {
                        die("Connection failed:" .$conn -> connect_error);
                    }

                    if ($_POST["selectVal"] == "name") {
                        $name = $_POST["value"];
                        printSearchFor($_POST["selectVal"], $name);
                        $sql = "select * from users where username LIKE \"{$name}%\" AND userID !={$_SESSION['user']};";
                        $result = $conn -> query($sql);

                        $profileImage = null;

                        if(mysqli_num_rows($result) != 0) {
                            while($row = $result->fetch_assoc())
                            {   
                                print "<div class='user'>";

                                if (isset($row['profileImage'])) $profileImage = $row['profileImage'];
                                
                                if($profileImage === null) {
                                    print '<img class="userImage" src ="images/blank-profile-picture.png" 
                                            alt="profile image" height="25%" width="25%" 
                                            style="min-width:180px; min-height:180px; border-radius:50%;" >';
                                }
                                else {
                                    print "<img class='userImage' src = 'profileImages/{$profileImage}' 
                                            alt='profile image' height='25%' width='25%' 
                                            style='min-width:180px; min-height:180px; border-radius:50%; 
                                            object-fit: cover; overflow:hidden;' >";
                                }
                                print "<a class='userDetails' href='profile.php?userID={$row['userID']}'><b>{$row['username']} - {$row['email']}</b></a>";
                                $connectionsSQL = "select * from banneduser where userID = {$row['userID']};";
                                $result2 = $conn -> query($connectionsSQL);
                                $connectionsRow = $result2->fetch_assoc();
                                if($connectionsRow) {
                                    print "<img class='connectionImage' src='images/Delete.png' alt='Delete connection' height='20%' weight='20%' onClick='DeleteUser({$row['userID']})'></img><br>";
                                    print "<img class='connectionImage' src='images/tick.png' alt='logo here' height='20%' weight='20%' onClick='unbanUser({$row['userID']})'></img><br>";
                                } else {
                                    print "<img class='connectionImage' src='images/ban.png' alt='logo here' height='20%' weight='20%' onClick='BanUser({$row['userID']})'></img><br>";
                                }
                                
                                print "</div>";
                            }
                            $conn->close();
                        } else {
                            print "<h1>No Users found.</h1>";
                        }
                    }

                    if ($_POST["selectVal"] == "companyName") {
                        $companyName = $_POST["value"];
                        printSearchFor($_POST["selectVal"], $companyName);
                        $sql = "select * from companies where companyName LIKE \"{$companyName}%\";";
                        $result = $conn -> query($sql);
                        if(mysqli_num_rows($result) != 0) {
                            while($row = $result->fetch_assoc())
                            {   
                                print "<div class='user'>";
                                print "<a class='userDetails' href='company.php?companyID={$row['companyID']}'><b>{$row['companyName']} - {$row['email']}</b></a>";
                                print "</div>";

                                $connectionsSQL = "select * from bannedcompany where companyID = {$row['companyID']};";
                                $result2 = $conn -> query($connectionsSQL);
                                $connectionsRow = $result2->fetch_assoc();
                                if($connectionsRow) {
                                    print "<img class='connectionImage' src='images/Delete.png' alt='Delete connection' height='20%' weight='20%' onClick='deleteCompany({$row['companyID']})'></img><br>";
                                    print "<img class='connectionImage' src='images/tick.png' alt='logo here' height='20%' weight='20%' onClick='unBanCompany({$row['companyID']})'></img><br>";
                                } else {
                                    print "<img class='connectionImage' src='images/ban.png' alt='logo here' height='20%' weight='20%' onClick='BanCompany({$row['companyID']})'></img><br>";
                                }
                            }
                            $conn->close();
                            
                        } else {
                            print "<h1>No Company found.</h1>";
                        }
                    }

                    if ($_POST["selectVal"] == "skill") {
                        $skill = $_POST["value"];
                        printSearchFor($_POST["selectVal"], $skill);
                        $SQL = "select a.userID, a.email
                                from users a
                                INNER JOIN userskills b
                                ON a.userID = b.userID
                                INNER JOIN skills c
                                ON b.skillID = c.skillID
                                WHERE a.userID != {$_SESSION['user']} AND c.skillTitle LIKE \"{$skill}%\";";
                        $skillResult = $conn -> query($SQL);
                        if(mysqli_num_rows($skillResult) != 0) {
                                while($skillRow = $skillResult->fetch_assoc()) {
                                    print "<div class='user'>";
                                    print "<a class='userDetails' href='profile.php?userID={$skillRow['userID']}'><b>{$skillRow['email']}</b></a>";
                                    $connectionsSQL = "select * from connections where userIDFirst = \"{$_SESSION['user']}%\" AND userIDSecond = \"{$skillRow['userID']}%\";";
                                    $result2 = $conn -> query($connectionsSQL);
                                    $connectionsRow = $result2->fetch_assoc();
                                    if($connectionsRow) {
                                        print "<img class='connectionImage' src='images/ban.png' alt='logo here' height='20%' weight='20%' onClick='BanUser({$row['userID']})'></img><br>";
                                        print "<img class='connectionImage' src='images/Delete.png' alt='Delete connection' height='20%' weight='20%' onClick='DeleteUser({$row['userID']})'></img><br>";
                                        print "<img class='connectionImage' src='images/tick.png' alt='logo here' height='20%' weight='20%' onClick='unbanUser({$row['userID']})'></img><br>";
                                    } else {
                                        print "<img class='connectionImage' src='images/unconnectedv2.png' alt='logo here' height='20%' weight='20%' onClick='makeConnection({$skillRow['userID']})'></img><br>";
                                    }
                                    print "</div>";
                                }
                        } else {
                            print "<h1>No Users found with the skill \"{$skill}\".</h1>";
                        }
                        $conn->close();
                    }

                    if ($_POST["selectVal"] == "currentlyEmployed") {
                        $currentlyEmployed = $_POST["value"];
                        printSearchFor($_POST["selectVal"], $currentlyEmployed);
                        $SQL = "select a.userID, a.email
                                from users a
                                INNER JOIN companies b
                                ON a.companyID = b.companyID
                                WHERE a.userID != {$_SESSION['user']} AND b.companyName LIKE \"{$currentlyEmployed}%\";";
                        $currentlyEmployedResult = $conn -> query($SQL);
                        if(mysqli_num_rows($currentlyEmployedResult) != 0) {
                                while($currentlyEmployedRow = $currentlyEmployedResult->fetch_assoc()) {
                                    print "<div class='user'>";
                                    print "<a class='userDetails' href='profile.php?userID={$currentlyEmployedRow['userID']}'><b>{$currentlyEmployedRow['email']}</b></a>";
                                    $connectionsSQL = "select * from connections where userIDFirst = \"{$_SESSION['user']}%\" AND userIDSecond = \"{$currentlyEmployedRow['userID']}%\";";
                                    $result2 = $conn -> query($connectionsSQL);
                                    $connectionsRow = $result2->fetch_assoc();
                                    if($connectionsRow) {
                                        print "<img class='connectionImage' src='images/ban.png' alt='logo here' height='20%' weight='20%' onClick='BanUser({$row['userID']})'></img><br>";
                                        print "<img class='connectionImage' src='images/Delete.png' alt='Delete connection' height='20%' weight='20%' onClick='DeleteUser({$row['userID']})'></img><br>";
                                        print "<img class='connectionImage' src='images/tick.png' alt='logo here' height='20%' weight='20%' onClick='unbanUser({$row['userID']})'></img><br>";
                                    } else {
                                        print "<img class='connectionImage' src='images/unconnectedv2.png' alt='logo here' height='20%' weight='20%' onClick='makeConnection({$currentlyEmployedRow['userID']})'></img><br>";
                                    }
                                    print "</div>";
                                }
                        } else {
                            print "<h1>No Users found with the skill \"{$currentlyEmployed}\".</h1>";
                        }
                        $conn->close();
                    }

                    if ($_POST["selectVal"] == "previousHistory") {
                        $previousHistory = $_POST["value"];
                        printSearchFor($_POST["selectVal"], $previousHistory);
                        $SQL = "select a.userID, a.email
                                from users a
                                INNER JOIN jobhistory b
                                ON a.userID = b.userID
                                INNER JOIN companies c
                                On b.companyID = c.companyID
                                WHERE a.userID != {$_SESSION['user']} AND c.companyName LIKE \"{$previousHistory}%\";";
                        $previousHistoryResult = $conn -> query($SQL);
                        if(mysqli_num_rows($previousHistoryResult) != 0) {
                                while($previousHistoryRow = $previousHistoryResult->fetch_assoc()) {
                                    print "<div class='user'>";
                                    print "<a class='userDetails' href='profile.php?userID={$previousHistoryRow['userID']}'><b>{$previousHistoryRow['email']}</b></a>";
                                    $connectionsSQL = "select * from connections where userIDFirst = \"{$_SESSION['user']}%\" AND userIDSecond = \"{$previousHistoryRow['userID']}%\";";
                                    $result2 = $conn -> query($connectionsSQL);
                                    $connectionsRow = $result2->fetch_assoc();
                                    if($connectionsRow) {
                                        print "<img class='connectionImage' src='images/ban.png' alt='logo here' height='20%' weight='20%' onClick='BanUser({$row['userID']})'></img><br>";
                                        print "<img class='connectionImage' src='images/Delete.png' alt='Delete connection' height='20%' weight='20%' onClick='DeleteUser({$row['userID']})'></img><br>";
                                        print "<img class='connectionImage' src='images/tick.png' alt='logo here' height='20%' weight='20%' onClick='unbanUser({$row['userID']})'></img><br>";
                                    } else {
                                        print "<img class='connectionImage' src='images/unconnectedv2.png' alt='logo here' height='20%' weight='20%' onClick='makeConnection({$previousHistoryRow['userID']})'></img><br>";
                                    }
                                    print "</div>";
                                }
                        } else {
                            print "<h1>No Users found with the skill \"{$previousHistory}\".</h1>";
                        }
                        $conn->close();
                    }
                }

                function printSearchFor($searchVal, $searchTerm) {
                    print "<h1 class='page-header'>Search by {$searchVal} for {$searchTerm}</h1>";
                }
            ?>
        </div>
    </body>
</html>