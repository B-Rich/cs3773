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

/* print test order form */
echo
   "
   <form action='testorder.php?cid=$cid' method='POST'>
   Enter Test Order Information:<br>
   Test Type: <input type='text' name='testtype' size='20' required><br>
   Name of Lab: <input type='text' name='lab' size='20' required><br>
   Comments/Instructions: <br>
   <textarea name = 'comments' rows='4' cols='50'></textarea><br>
   <input type = 'submit' value = 'Submit' name = 'submit'>
</form>";

   if (isset($_POST['submit'])){
 
      $testtype = sanitizeString($_POST['testtype']);
      $lab = sanitizeString($_POST['lab']);
      $comments = sanitizeString($_POST['comments']);

      $query = "insert into Test_Orders(cid,date, type, labName, comments, doctor_username)
                values ($cid, current_timestamp, '$testtype', '$lab', '$comments', '$user')";
      $result = mysqli_query($conn, $query);
      if (!$result){
         echo "Unable to save test order<br>";
         echo mysqli_error($conn);
      }        
      else{
         echo "Test order has been saved. <br>";
      } 
   }
?>
