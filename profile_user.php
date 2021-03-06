

<html>
    <head>
        <title>Loop : Home</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/profile_user.css?v=<?php echo time(); ?>">
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
            <input type="text" name="name" placeholder="Search for User">
            <input type="submit" value="Search">

        </form>
        <div class = "profile-container" >
            <div class = "profileImage" >
                <img src = "images/ellipse.png" alt = "there should be an image here" height="20%" weight="20%" >
            </div>
        </div>
        <div class = "description-container">
            <div class = "description-heading">
            <H1 style = "text-align: center;"> description</H1>
            </div>
            <div class = "bio-description">
            <h3>Bio:</h3>
            <p1>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ut turpis ornare, blandit sem egestas, 
                placerat mi. Aliquam lacus libero, auctor id orci ut, porttitor faucibus sapien. Morbi tincidunt massa libero, 
                a commodo justo dapibus vitae. Cras pulvinar porta purus id gravida. Sed in suscipit nisl. Aenean ac orci dictum, 
                vehicula lacus vitae, pulvinar urna. Suspendisse id aliquam velit. Nullam ac velit vestibulum, ultrices sapien molestie, 
                bibendum ante. Donec libero odio, sagittis sed mauris at, condimentum aliquet erat.
                Etiam molestie libero lectus, at consectetur sem dignissim sed. Proin congue fermentum erat, 
                sit amet mattis tellus dictum nec. Cras efficitur ac libero ac suscipit. Sed elit augue, gravida ac mauris ac, 
                ultrices iaculis libero. Sed porttitor laoreet dui at porttitor. Nam vitae nulla ut ipsum posuere pharetra. 
                Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Sed luctus nibh ac 
                semper venenatis. Quisque dictum sit amet tortor a imperdiet. Proin a lectus sit amet justo rhoncus fermentum ac 
                in nisl. Vivamus consectetur non augue vitae faucibus. Phasellus suscipit odio eu aliquet condimentum.
            </p1>
            </div>
            <div class = "skills-description">
            <h3>Skills:</h3>
            <p1>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ut turpis ornare, blandit sem egestas, 
                placerat mi. Aliquam lacus libero, auctor id orci ut, porttitor faucibus sapien. Morbi tincidunt massa libero, 
                a commodo justo dapibus vitae. Cras pulvinar porta purus id gravida. Sed in suscipit nisl. Aenean ac orci dictum, 
                vehicula lacus vitae, pulvinar urna. Suspendisse id aliquam velit. Nullam ac velit vestibulum, ultrices sapien molestie, 
                bibendum ante. Donec libero odio, sagittis sed mauris at, condimentum aliquet erat.
                Etiam molestie libero lectus, at consectetur sem dignissim sed. Proin congue fermentum erat, 
                sit amet mattis tellus dictum nec. Cras efficitur ac libero ac suscipit. Sed elit augue, gravida ac mauris ac, 
                ultrices iaculis libero. Sed porttitor laoreet dui at porttitor. Nam vitae nulla ut ipsum posuere pharetra. 
                Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Sed luctus nibh ac 
                semper venenatis. Quisque dictum sit amet tortor a imperdiet. Proin a lectus sit amet justo rhoncus fermentum ac 
                in nisl. Vivamus consectetur non augue vitae faucibus. Phasellus suscipit odio eu aliquet condimentum.
            </p1>
            </div>
            
            <div class = "Qualifications-description">
            <h3>Qualifications:</h3>
            <p1>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ut turpis ornare, blandit sem egestas, 
                placerat mi. Aliquam lacus libero, auctor id orci ut, porttitor faucibus sapien. Morbi tincidunt massa libero, 
                a commodo justo dapibus vitae. Cras pulvinar porta purus id gravida. Sed in suscipit nisl. Aenean ac orci dictum, 
                vehicula lacus vitae, pulvinar urna. Suspendisse id aliquam velit. Nullam ac velit vestibulum, ultrices sapien molestie, 
                bibendum ante. Donec libero odio, sagittis sed mauris at, condimentum aliquet erat.
                Etiam molestie libero lectus, at consectetur sem dignissim sed. Proin congue fermentum erat, 
                sit amet mattis tellus dictum nec. Cras efficitur ac libero ac suscipit. Sed elit augue, gravida ac mauris ac, 
                ultrices iaculis libero. Sed porttitor laoreet dui at porttitor. Nam vitae nulla ut ipsum posuere pharetra. 
                Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Sed luctus nibh ac 
                semper venenatis. Quisque dictum sit amet tortor a imperdiet. Proin a lectus sit amet justo rhoncus fermentum ac 
                in nisl. Vivamus consectetur non augue vitae faucibus. Phasellus suscipit odio eu aliquet condimentum.
            </p1>
            </div>

            <div class = "Certs-description">
            <h3>Licenses and Certificates:</h3>
            <p1>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ut turpis ornare, blandit sem egestas, 
                placerat mi. Aliquam lacus libero, auctor id orci ut, porttitor faucibus sapien. Morbi tincidunt massa libero, 
                a commodo justo dapibus vitae. Cras pulvinar porta purus id gravida. Sed in suscipit nisl. Aenean ac orci dictum, 
                vehicula lacus vitae, pulvinar urna. Suspendisse id aliquam velit. Nullam ac velit vestibulum, ultrices sapien molestie, 
                bibendum ante. Donec libero odio, sagittis sed mauris at, condimentum aliquet erat.
                Etiam molestie libero lectus, at consectetur sem dignissim sed. Proin congue fermentum erat, 
                sit amet mattis tellus dictum nec. Cras efficitur ac libero ac suscipit. Sed elit augue, gravida ac mauris ac, 
                ultrices iaculis libero. Sed porttitor laoreet dui at porttitor. Nam vitae nulla ut ipsum posuere pharetra. 
                Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Sed luctus nibh ac 
                semper venenatis. Quisque dictum sit amet tortor a imperdiet. Proin a lectus sit amet justo rhoncus fermentum ac 
                in nisl. Vivamus consectetur non augue vitae faucibus. Phasellus suscipit odio eu aliquet condimentum.
            </p1>
            </div>

            <div class = "Qualifications-description">
            <h3>Qualifications:</h3>
            <p1>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ut turpis ornare, blandit sem egestas, 
                placerat mi. Aliquam lacus libero, auctor id orci ut, porttitor faucibus sapien. Morbi tincidunt massa libero, 
                a commodo justo dapibus vitae. Cras pulvinar porta purus id gravida. Sed in suscipit nisl. Aenean ac orci dictum, 
                vehicula lacus vitae, pulvinar urna. Suspendisse id aliquam velit. Nullam ac velit vestibulum, ultrices sapien molestie, 
                bibendum ante. Donec libero odio, sagittis sed mauris at, condimentum aliquet erat.
                Etiam molestie libero lectus, at consectetur sem dignissim sed. Proin congue fermentum erat, 
                sit amet mattis tellus dictum nec. Cras efficitur ac libero ac suscipit. Sed elit augue, gravida ac mauris ac, 
                ultrices iaculis libero. Sed porttitor laoreet dui at porttitor. Nam vitae nulla ut ipsum posuere pharetra. 
                Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Sed luctus nibh ac 
                semper venenatis. Quisque dictum sit amet tortor a imperdiet. Proin a lectus sit amet justo rhoncus fermentum ac 
                in nisl. Vivamus consectetur non augue vitae faucibus. Phasellus suscipit odio eu aliquet condimentum.
            </p1>
            </div>
           
            </div>

       <!--<h1>Welcome <?php //echo $_SESSION['username']; ?> </h1>-->
    </body>
</html>