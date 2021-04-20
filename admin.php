<html>
    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/admin.css?v=<?php echo time(); ?>">
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico"/>

        <meta content="width=device-width, initial-scale=1" name="viewport" />

        <title>Loop : Admin</title>
       
        <script type="text/javascript">
            function unbanUser(variable) {
                if (confirm("Are you sure you want to unban this User?") == true) {
                window.location.href= 'adminUnbanUser.php?id=' + variable;
                };
            }

            function BanUser(variable) {
                if (confirm("Are you sure you want to ban this User?") == true) {
                    window.location.href= 'adminBanUser.php?id=' + variable;
                };
            }

            function DeleteUser(variable) {
                if (confirm("Are you sure you want to delete this User?") == true) {
                    window.location.href= 'adminDeleteUser.php?id=' + variable;
                };
            }

            function BanCompany(variable) {
                if (confirm("Are you sure you want to ban this Company?") == true) {
                    window.location.href= 'adminBanCompany.php?id=' + variable;
                };
            }

            function deleteOldPictures() {
                if (confirm("Are you sure you want to delete all Unused Profile Images?") == true) {
                    window.location.href= 'flushOldProfilePictures.php';
                };
            }

            function unBanCompany(variable) {
                if (confirm("Are you sure you want to unban this Company?") == true) {
                    window.location.href= 'adminUnbanCompany.php?id=' + variable;
                };
            }

            function deleteCompany(variable) {
                if (confirm("Are you sure you want to unban this Company?") == true) {
                    window.location.href= 'adminDeleteCompany.php?id=' + variable;
                };
            }
        </script>
  

    </head>
    <body>
        <?php
            include ("validateAdmin.php");
            include ("serverConfig.php");
            include ("adminTemplate.html");
        ?>
        <h1 class="page-heading">User Options</h1>
        <hr>
        <div class="nav-links" style="margin-left:auto;">
            <button type="button" style="float: right; margin-right:5%;" class="btn btn-danger" onclick="deleteOldPictures();">Delete Unused Images</button>
        </div> 
        <form class="search" method="post" action="admin.php">
            <select class="select" name="selectVal">
                <option value="name">Name</option>
                <option value="companyName">Company Name</option>
            </select>
            <input class="input" type="text" name="value" placeholder="Search for User">
            <input class="submit" type="submit" value="Search">
        </form>
            
        <div class="page-box">
            <?php

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

                        if(mysqli_num_rows($result) != 0) {
                            while($row = $result->fetch_assoc())
                            {   
                                print "<div class='user'>";

                                $profileImage = null;

                                if (isset($row['profileImage'])) $profileImage = $row['profileImage'];
                                
                                if($profileImage === null) {
                                    print '<img class="userImage" src ="images/blank-profile-picture.png" 
                                            alt="profile image"  >';
                                }
                                else {
                                    print "<img class='userImage' src = 'profileImages/{$profileImage}' 
                                            alt='profile image' >";
                                }
                                print "<a class='userDetails' href='adminUserView.php?userID={$row['userID']}'><b>{$row['username']}</b></a>";
                                $connectionsSQL = "select * from banneduser where userID = {$row['userID']};";
                                $result2 = $conn -> query($connectionsSQL);
                                $connectionsRow = $result2->fetch_assoc();
                                if($connectionsRow) {
                                    print "<br><button type='button' class='btn btn-success' style ='margin-top:6%;' onClick='unbanUser({$row['userID']})'>Unban User</button>";
                                } else {
                                    print "<br><button type='button' class='btn btn-warning' style ='margin-top:6%;' onClick='BanUser({$row['userID']})'>Ban User</button>";
                                    print "<br><button type='button' class='btn btn-danger' style ='margin-top:2%;' onClick='DeleteUser({$row['userID']})'>Delete User</button>";
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
                                print "<a class='userDetails' href='adminCompany.php?companyID={$row['companyID']}'><b>{$row['companyName']} - {$row['email']}</b></a>";

                                $connectionsSQL = "select * from bannedcompany where companyID = {$row['companyID']};";
                                $result2 = $conn -> query($connectionsSQL);
                                $connectionsRow = $result2->fetch_assoc();
                                if($connectionsRow) {
                                    print "<br><button type='button' class='btn btn-success' style ='margin-top:6%;' onClick='unBanCompany({$row['companyID']})'>Unban Company</button>";
                                } else {
                                    print "<br><button type='button' class='btn btn-warning' style ='margin-top:6%;' onClick='BanCompany({$row['companyID']})'>Ban Company</button>";
                                    print "<br><button type='button' class='btn btn-danger' style ='margin-top:2%;' onClick='deleteCompany({$row['companyID']})'>Delete Company</button>";
                                }
                                print "</div>";
                            }
                            $conn->close();
                            
                        } else {
                            print "<h1>No Company found.</h1>";
                        }
                    }
                }
                
                function printSearchFor($searchVal, $searchTerm) {
                    print "<h1 class='page-header'>Search by {$searchVal} for {$searchTerm}</h1>";
                }
            ?>
        </div>
    </body>
</html>