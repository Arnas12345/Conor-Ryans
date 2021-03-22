<?php

   $DB_SERVER = 'localhost';

   $DB_USERNAME = 'root';

   $DB_PASSWORD = '';

   $DB_DATABASE ='loop';


   function connectToDB() {
      $conn = new mysqli('localhost', 'root', '', 'loop');
      if ($conn -> connect_error) {
         die("Connection failed:" .$conn -> connect_error);
      }
   }
?>