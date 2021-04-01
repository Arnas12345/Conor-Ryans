<html>
    <head> 
        <title> 
            DANGER
        </title>
    </head>

    <body style="text-align:center; width:960px; margin:auto;">
        <form method="post" action="flushOldProfilePictures.php">
            <input type="text" name="confirmation" style="margin-top:15%; width: 500px; height: 30px;"
                placeholder="Type 'yes' if you are sure you want to delete the unused profile images.">
            <input type="submit" name="submit" value="DELETE" style="height:30px;"/>
        </form>
    </body>

</html>

<?php
    if(isset($_POST['confirmation']) && isset($_POST['submit'])) {

        if($_POST['confirmation'] == 'yes') {

            include ("serverConfig.php");
            $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
            if ($conn -> connect_error) {
                die("Connection failed:" .$conn -> connect_error);
            }

            $files = glob('profileImages/*.{jpg,png,gif,svg}', GLOB_BRACE);
            
            foreach($files as $file) {
                
                $tmpfile = substr($file, strpos($file, "/")+1, strlen($file) );
                
                $query = "SELECT * FROM users WHERE profileImage='$tmpfile'";

                $result = mysqli_query($conn,$query);
                $num_row = mysqli_num_rows($result);

                $row=mysqli_fetch_array($result);

                if( $num_row > 0 ) {
                    print "<h3>Keep file in use: " . $tmpfile . "</h3>" . '<br><hr>';          
                }
                else {
                    print "<h3>Deleting unused file: " . $tmpfile . "</h3>" . '<br><hr>';
                    unlink($file);
                }
            }
        }
    }
?>