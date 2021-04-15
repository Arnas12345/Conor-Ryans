<html>

    <head> 
        <title> 
            DANGER
        </title>
    </head>

    <body style="text-align:center; width:960px; margin:auto;">
        
        <?php

        include ("validateAdmin.php");
        include ("adminTemplate.html");
        include ("serverConfig.php");

        $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
        if ($conn -> connect_error) {
            die("Connection failed:" .$conn -> connect_error);
        }

        $files = glob('profileImages/*.{jpg,png,gif,svg}', GLOB_BRACE);
        
        foreach($files as $file) {
            
            $tmpfile = substr($file, strpos($file, "/")+1, strlen($file) );
            
            $userQuery = "SELECT * FROM users WHERE profileImage='$tmpfile'";
            $companyQuery = "SELECT * FROM companies WHERE profileImage='$tmpfile'";

            $uResult = mysqli_query($conn,$userQuery);
            $uNum_row = mysqli_num_rows($uResult);

            $cResult = mysqli_query($conn,$companyQuery);
            $cNum_row = mysqli_num_rows($cResult);

            if( $uNum_row > 0 || $cNum_row > 0) {
                print "<h3>Keep file in use: " . $tmpfile . "</h3>" . '<br><hr>';          
            }
            else {
                print "<h3>Deleting unused file: " . $tmpfile . "</h3>" . '<br><hr>';
                unlink($file);
            }
        }
        ?>
    </body>

</html>