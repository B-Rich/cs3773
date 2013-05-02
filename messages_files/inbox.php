<?php
session_start();
require_once('db.php');
// the no cache might be needed in case we run into
// problems with displaying cached pages
// include('no_cache.php'); 
if(!isset($_SESSION['user'])){
   // session not logged in so redirect to login page
   // header("Status: 200"); //might be needed
  header("Location: login.php");
}else{
   //session logged 
   //display the contents of members.php
   //notice that the closing brace is not until the bottom of the page		
?>

<html>

<head> 
<title> Messages</title>
<script src="linknav.js"></script>
</head>

</body>
<?php
//if (isset($_SESSION['user']))
//{
      // connect to the DB
      $conn = connect_db(); 
      // write the query to be executed
      $query = "SELECT * FROM `Message` WHERE `to_username` = '" . $_SESSION['user'] ."'";
      // execute the query and return the results
      $result = mysqli_query($conn, $query);
      // fetch the results into an associative array
      
      echo "<table  border=''>";
     
      While($row = mysqli_fetch_assoc($result)){
       echo "<tr>";
      //echo "<td>I am a cell</td>"; 
      if ($row['read'] == 0){
         $isRead1 = "<b>";
         $isRead2 = "</b>";
      }
      else{
         $isRead1 = "";
         $isRead2 = "";
      }
      
       echo " <td><a href='readmsg.php?msgid=" . $row['mid'] . "'>". $isRead1 . $row['from_username'] . $isRead2. " </a></td>";
       echo "<td><a href='readmsg.php?msgid=" . $row['mid'] . "'>". $isRead1 . $row['subject'] . $isRead2 ."</a></td>";
       echo "</tr>";
      
      }
      
      echo "</table>";
      
      //this is like free in C 
      mysqli_free_result($result);
      //close connection to the database
      mysqli_close($conn);
//}
?>
</body>
</html>
<?php
} //end else
?>
