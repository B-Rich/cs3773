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

/* print prescription form */
echo
   "
   <form action='prescription.php?cid=$cid' method='POST'>
   Enter Prescription Information:<br>
   Medication: <input type='text' name='medication' size='20' required><br>
   Dosage: <input type='text' name='dosage' size='20' required><br>
   Refills: <input type='number' name='refills' size='20' required><br>
   <input type = 'submit' value = 'Submit' name = 'submit'>
</form>";

   if (isset($_POST['submit'])){
 
      $medication = sanitizeString($_POST['medication']);
      $dosage = sanitizeString($_POST['dosage']);
      $refills = $_POST['refills'];

      $query = "insert into Prescriptions(cid,date, medication, dosage, refills, doctor_username)
                values ($cid, current_timestamp, '$medication', '$dosage', $refills, '$user')";
      $result = mysqli_query($conn, $query);
      if (!$result){
         //handle error
         echo "Unable to save prescription <br>";
         echo mysqli_error($conn);
      }        
      else{
         echo "Prescription has been saved. <br>";
      } 

   }
?>
