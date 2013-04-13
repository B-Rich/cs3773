<?php
session_start();
require_once('db.php');
// the no cache might be needed in case we run into
// problems with displaying cached pages
//include('no_cache.php'); 
if(!isset($_SESSION['user'])){
   // session not logged in so redirect to login page
   //header("Status: 200"); //might be needed
   header("Location: login.php");
}
   //session logged 
   //display the contents of members.php
   //notice that the closing brace is not until the bottom of the page		
?>
<html>
<head> 
<title> Member's Page</title>
<script src="linknav.js"></script>
</head>
<body>

<?php

      // connect to the DB
      $conn = connect_db(); 
      
      //print out user type
      echo "I am a " . $_SESSION['type'];
      

      //close connection to the database
      mysqli_close($conn);
?>

<p> <a href="javascript:gotoLogout()">Logout</a> </p>
</body>
</html>

<?php
?>
