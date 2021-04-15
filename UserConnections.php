<html>
    <head>
        <title>Loop : Search</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/UserConnections.css?v=<?php echo time(); ?>">
    </head>
    
    <script type="text/javascript">
        function showRequests() {
            var modal = document.getElementById("myModal");
            modal.style.display = "block";
            
            var span = document.getElementsByClassName("close")[0];
            span.onclick = function() {
                modal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        }

        function refreshPage() {
            window.location.href = "UserConnections.php";
        }
    </script>
    <body>
        <?php include("headerTemplate.html"); ?>
        <h1 class="page-heading">My Connections</h1>
        <hr>
        <div style='text-align:center'>
            <button class='showRequests' onClick='showRequests()' style='text-align:center'>Show Friend Requests</button>
        </div>
        <div class="page-box">
            <?php
                include ("validateLoggedIn.php");
                include ("serverConfig.php");

                $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
                if ($conn -> connect_error) {
                    die("Connection failed:" .$conn -> connect_error);
                }

                $sql = "SELECT a.userID, a.email, a.username, b.status
                        FROM users a 
                        INNER JOIN connections b
                        ON a.userID = b.userIDFirst
                        WHERE b.userIDSecond = {$_SESSION['user']} AND b.status = 'Accepted';";
                $result = $conn -> query($sql);
                
                while($row = $result->fetch_assoc()) {   
                    print "<div class='user'>";

                    $profileImage = null;

                    if (isset($row['profileImage'])) $profileImage = $row['profileImage'];
                    
                    if($profileImage === null) {
                        print '<img class="userImage" src ="images/blank-profile-picture.png" 
                                alt="profile image"><br>';
                    }
                    else {
                        print "<img class='userImage' src = 'profileImages/{$profileImage}' 
                                alt='profile image'><br>";
                    
                    }

                    print "<a class='userDetails' href='profile.php?userID={$row['userID']}'><b>{$row['username']}</b></a>";
                    $connectionsSQL = "select * from connections where userIDFirst = \"{$_SESSION['user']}%\" AND userIDSecond = \"{$row['userID']}%\";";
                    $result2 = $conn -> query($connectionsSQL);
                    $connectionsRow = $result2->fetch_assoc();
                    print "<div class='friend-req-details'>";
                    if($connectionsRow) {
                        if($connectionsRow['status'] !== "Pending") {
                            print "<button class='btn-unconnect' onClick='deleteConnection({$row['userID']})'> Unconnect </button><br>";
                        }
                        else {
                            //print "<img class='connectionImage' src='images/unconnectedv2.png' alt='logo here' onClick='makeConnection({$row['userID']})'></img><br>";
                            print "<br><a class='pending'> Pending </a><br>";
                        }
                    } 
                    else {
                        print "<button class='btn-friend-req' onClick='makeConnection({$row['userID']})'> Connect </button><br>";
                        // print "<img class='connectionImage' src='images/unconnectedv2.png' alt='logo here' height='20%' weight='20%' onClick='makeConnection({$row['userID']})'></img><br>";
                    }

                    print "</div> </div>";
                }
                $conn->close();

            ?>
        </div>
        <div id='myModal' class='modal'>
            <!-- Modal content -->
            <div class='modal-content'>
                <span class='close close'>&times;</span>
                <table class='userConnections'>
                    <thead>
                        <tr>
                            <th>User Name</th>
                            <th>Accept/Decline Request</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
                        if ($conn -> connect_error) {
                            die("Connection failed:" .$conn -> connect_error);
                        }
                        $sql = "SELECT a.userID, a.email, a.username, b.status, b.userIDFirst
                        FROM users a 
                        INNER JOIN connections b
                        ON a.userID = b.userIDFirst
                        WHERE b.userIDSecond = {$_SESSION['user']} AND b.status = 'Pending';";
                        $result = $conn -> query($sql);
                        if(mysqli_num_rows($result) != 0) {
                            while($row = $result->fetch_assoc())
                            {   
                                echo '<tr>';
                                echo '<td>' . $row['username'] . '</td>';
                                echo "<td>
                                        <a id='decline{$row['userID']}' class='status' href='UserConnections.php?deleteRequest=true&userIDFirst={$row['userIDFirst']}&userIDSecond={$_SESSION['user']}'>&#x2716;</a>
                                        <a id='accept{$row['userID']}' class='status' href='UserConnections.php?acceptRequest=true&userIDFirst={$row['userIDFirst']}&userIDSecond={$_SESSION['user']}'>&#x2714;</a>
                                    </td>";
                                echo '</tr>';
                            }
                        } else {
                            print "<TR>";
                            print "<TD colspan='2'>No Connections</TD>";
                            print "</TR>";
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>

<?php
    if (isset($_GET['deleteRequest'])) changeApplicantStatus("Declined");
    if (isset($_GET['acceptRequest'])) changeApplicantStatus("Accepted");

    function changeApplicantStatus($status) {
        include ("serverConfig.php");
        $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
        if ($conn -> connect_error) {
            die("Connection failed:" .$conn -> connect_error);
        }
        if($status == 'Declined') {
            $declineRequestSQL = "DELETE FROM connections
                                WHERE userIDFirst={$_GET['userIDFirst']} AND userIDSecond={$_GET['userIDSecond']};";
            $conn -> query($declineRequestSQL);
        } else {
            $userIDFirst = $_GET['userIDFirst'];
            $userIDSecond = $_GET['userIDSecond'];
            $acceptRequestSQL = "INSERT INTO connections (userIDFirst, userIDSecond, CreationDate, Status)
                                VALUES ('{$userIDFirst}', '{$userIDSecond}', Now(), 'Accepted'),
                                ('{$userIDSecond}', '{$userIDFirst}', Now(), 'Accepted')";
            $conn -> query($acceptRequestSQL);
            $deletePendingSQL = "DELETE FROM connections
                                WHERE userIDFirst=$userIDFirst AND userIDSecond=$userIDSecond AND Status='Pending';";
            $conn -> query($deletePendingSQL);
        }
        echo "<script> refreshPage(); </script>";
    }
?>