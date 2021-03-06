<html>
    <head>
        <title>Loop : Search</title>
        <link rel="stylesheet" type="text/css" href="css/index.css?v=<?php echo time() ?>">
    </head>
    <body>
        <?php
            session_start();
            include ("serverConfig.php");
            $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
            if ($conn -> connect_error) {
                die("Connection failed:" .$conn -> connect_error);
            }

            if ($_POST["selectVal"] == "name") {
                $name = $_POST["value"];

                $sql = "select * from Users where username LIKE \"{$name}%\";";
                $result = $conn -> query($sql);
                if($result -> fetch_assoc()) {
                    while($row = $result->fetch_assoc())
                    {
                        print "<a href='profile.php?link={$row['userID']}'>{$row['email']}</a><br>";
                    }
                    $conn->close();
                } else {
                    print "<h1>No Users found.</h1>";
                }
            }

            if ($_POST["selectVal"] == "skill") {
                $skill = $_POST["value"];
                $skillSql = "select * from Skills where skillTitle LIKE \"{$skill}%\";";
                $skillResult = $conn -> query($skillSql);
                $skillRow = $skillResult->fetch_assoc();
                if($skillRow) {

                    $usersSkillsSql = "select * from userskills where skillID =\"{$skillRow["skillID"]}%\";";
                    $usersSkillsResult = $conn -> query($usersSkillsSql);
                    if($usersSkillsResult -> fetch_assoc()) {
                        while($usersSkillsRow = $usersSkillsResult->fetch_assoc()) {
                            $usersSql = "select * from Users where userID =\"{$usersSkillsRow["userID"]}%\";";
                            $usersResult = $conn -> query($usersSql);
                            $usersRow = $usersResult -> fetch_assoc();
                            print "<a href='profile.php?userID={$usersRow['userID']}'>{$usersRow['username']} - {$usersRow['email']}</a><br>";
                        }
                    } else {
                        print "<h1>No Users found with the skill \"{$skill}\".</h1>";
                    }
                } else {
                    print "<h1>No Users found with the skill \"{$skill}\".</h1>";
                }
                $conn->close();
            }

            if ($_POST["selectVal"] == "currentlyEmployed") {
                $currentlyEmployed = $_POST["value"];
                $currentlyEmployedSql = "select * from Companies where companyName LIKE \"{$currentlyEmployed}%\";";
                $currentlyEmployedResult = $conn -> query($currentlyEmployedSql);
                $currentlyEmployedRow = $currentlyEmployedResult->fetch_assoc();
                if($currentlyEmployedRow) {
                    $usersSql = "select * from Users where companyID =\"{$currentlyEmployedRow["companyID"]}%\";";
                    $usersResult = $conn -> query($usersSql);
                    while($usersRow = $usersResult->fetch_assoc())
                    {
                        print "<a href='profile.php?userID={$usersRow['userID']}'>{$usersRow['username']} - {$usersRow['email']}</a><br>";
                    }
                } else {
                    print "<h1>No Users found with \"{$currentlyEmployed}\" as their current employer.</h1>";
                }
                $conn->close();
            }

            if ($_POST["selectVal"] == "previousHistory") {
                $previousHistory = $_POST["value"];
                $previousHistorySQL = "select * from Jobhistory where companyName LIKE \"{$previousHistory}%\";";
                $previousHistoryResult = $conn -> query($previousHistorySQL);
                if($previousHistoryResult->fetch_assoc()) {
                    while($previousHistoryRow = $previousHistoryResult->fetch_assoc()) {
                        $usersSql = "select * from Users where userID =\"{$previousHistoryRow["userID"]}%\";";
                        $usersResult = $conn -> query($usersSql);
                        $usersRow = $usersResult -> fetch_assoc();
                        print "<a href='profile.php?userID={$usersRow['userID']}'>{$usersRow['username']} - {$usersRow['email']}</a><br>";
                    }
                }
                else {
                    print "<h1>No Users found with the previous job history at \"{$previousHistory}\".</h1>";
                }
                $conn->close();
            }
        ?>
    </body>
</html>