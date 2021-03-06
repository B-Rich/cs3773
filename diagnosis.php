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
$cid = $_GET['cid'];

/* if user doesn't have permission to view this page, redirect to home */
if ($type != 'doctor'){
   header("Location: members.php");
}
/* make database connection */

$conn = connect_db();

/* Get current diagnosis */
$query = "select diagnosis, treatmentplan
          from Log
          where cid='$cid' and date=current_date";
$result = mysqli_query($conn, $query);
$diagnosis = null;
$tplan = null;
if ($result == false || mysqli_num_rows($result) == 0){
   /* make new chart entry for patient */
   $query = "insert into Log(cid, date)
             values($cid, current_timestamp)";
   $result = mysqli_query($conn, $query);
   if (!$result){
      //handle error
      echo "Unable to access chart for patient<br>";
   }
}
else{
   $row = mysqli_fetch_array($result);
   $diagnosis= formatFromDB($row[0]);
   $tplan = formatFromDB($row[1]);
}
/* print diagnosis form */
echo
   "
   <form action='diagnosis.php?cid=$cid' method='POST'>
   Enter Diagnosis:<br>
   <textarea name = 'diagnosis' rows='4' cols='50'>$diagnosis</textarea><br>
   <input type = 'submit' value = 'Submit' name = 'submit1'>
</form>";
 
   if (isset($_POST['submit1'])){
 
      $diagnosis = $_POST['diagnosis'];

      $query = "update Log
                set diagnosis=?
                where cid=$cid and date=current_date";
      $stmt = mysqli_prepare($conn, $query);
      mysqli_stmt_bind_param($stmt, 's', $diagnosis);
        
      /* execute prepared statement */
      mysqli_stmt_execute($stmt);
      if (mysqli_stmt_affected_rows($stmt) == 0){
         echo "Error entering diagnosis<br>".mysql_error()."<br>";
      }
      /* reload page */
      header('Location: '.$_SERVER['REQUEST_URI']);
   }

/* print treatmentplan form */
echo
   "
   <form action='diagnosis.php?cid=$cid' method='POST'>
   Enter Diagnosis:<br>
   <textarea name = 'treatmentplan' rows='4' cols='50'>$tplan</textarea><br>
   <input type = 'submit' value = 'Submit' name = 'submit2'>
</form>";
 
   if (isset($_POST['submit2'])){
 
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
      header('Location: '.$_SERVER['REQUEST_URI']);
   }
?>
