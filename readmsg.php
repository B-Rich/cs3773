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
}else{
   //session logged 
   //display the contents of members.php
   //notice that the closing brace is not until the bottom of the page		
?>

<html>
<head> 
<title> Read Message</title>
<script src="linknav.js"></script>
</head>
<body>

<?php
if(isset($_GET['msgid'])){


      // connect to the DB
       $conn = connect_db(); 
      // write the query to be executed
      $query = "SELECT * FROM Message 
            WHERE mid = '".  $_GET['msgid'] ."' ";
      // execute the query and return the results
      $result = mysqli_query($conn, $query);
      // fetch the results into an associative array
      $row = mysqli_fetch_assoc($result);
      
      echo "<table>";
      echo "<tr>";
      echo "<td align='left' width = 15> To: </td>";
      echo "<td>" . $row['to_username'] . "</td>";
      echo "</tr>";
      echo "<tr>";
      echo "<td align='left' width = 15> From: </td>";
      echo "<td>" . $row['from_username'] . "</td>";
      echo "</tr>";
      echo "<tr>";
      echo "<td align='left' width = 15> Subject: </td>";
      echo "<td>" . $row['subject'] . "</td>";
      echo "</tr>";
      echo "<tr>";
      //echo "<td> Subject: </td>";
      echo "<td colspan ='2'><textarea disabled style='color:black; resize:none; background:white' name='name' cols='60' rows='5'>" . $row['text'] . "</textarea></td>";
      echo "</tr>";
      echo "</table>";
      //echo $row['body'];
      
      
      //this is like free in C 
      mysqli_free_result($result);
      //close connection to the database
      mysqli_close($conn);

}



?>

</body>
</html>
<?php
} //end else
?>
