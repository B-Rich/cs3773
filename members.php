<?php
session_start();
require_once('db.php');
include('no_cache.php');
if(!isset($_SESSION['user'])){
		// session not logged in so redirect to login page
		//header("Status: 200"); //might be needed
		header("Location: login.php");
}else{
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
      // write the query to be executed
      $query = "SELECT type FROM User 
            WHERE username= '". $_SESSION['user'] ."' ";
      // execute the query and return the results
      $result = mysqli_query($conn, $query);
      // fetch the results into an associative array
      $row = mysqli_fetch_assoc($result);
      
      if($row['type'] == 'doctor'){
         echo "<p>I am a doctor</p>";
      }else if($row['type'] == 'nurse'){
         echo "<p>I am a Nurse</p>";
      }else if($row['type'] == 'receptionist'){
         echo "<p>I am a Staff</p>";
      }else if($row['type'] == 'patient'){
         echo "<p>I am a Patient</p>";
      }else{
         echo "<p>don't know what I am</p>";
      }
      
      //this is like free in C 
      mysqli_free_result($result);
      //close connection to the database
      mysqli_close($conn);
?>

<p> <a href="javascript:gotoLogout()">Logout</a> </p>
</body>
</html>

<?php
} //end else
?>
