<?php
session_start();
require_once('db.php');
require_once('functions.php');

//if user not logged in, redirect to home page
if(!isset($_SESSION['user'])){
   header("Location: login.php");
}

$user = $_SESSION['user'];
$type = $_SESSION['type'];
$patient = $_GET['patient'];
$cid = $_GET['cid'];

/* if user doesn't have permission to view this page, redirect to home */
if ($type != 'doctor'){
   header("Location: members.php");
}
/* make database connection */

$conn = connect_db();

function formatFromDB($var){
   $var = sanitizeString($var);
   return ($var == null) ? "[Not entered]" : $var;
}

/* Get current treatmentplan */
$query = "select treatmentplan
          from Log
          where cid='$cid' and date=current_date";
$result = mysqli_query($conn, $query);
if ($result == false || mysqli_num_rows($result) == 0){
   echo "Error: Unable to find patient chart<br>";
}
else{

   $row = mysqli_fetch_array($result);
   $treatmentplan= formatFromDB($row[0]);

/* print treatmentplan form */
echo
   "
   <form action='treatmentplan.php?patient=$patient&cid=$cid' method='POST'>
   Enter Diagnosis:<br>
   <textarea name = 'treatmentplan' rows='4' cols='50'>$treatmentplan</textarea><br>
   <input type = 'submit' value = 'Submit' name = 'submit'>
</form>";
 
   if (isset($_POST['submit'])){
 
      $treatmentplan = $_POST['treatmentplan'];

      $query = "update Log
                set treatmentplan=?
                where cid=$cid and date=current_date";
      $stmt = mysqli_prepare($conn, $query);
      mysqli_stmt_bind_param($stmt, 's', $treatmentplan);
        
      /* execute prepared statement */
      mysqli_stmt_execute($stmt);
      if (mysqli_stmt_affected_rows($stmt) == 0){
         echo "Error entering treatmentplan<br>".mysql_error()."<br>";
      }
      
      /* reload page */
      header("Location: log.php?patient=$patient");

   }
}
?>
