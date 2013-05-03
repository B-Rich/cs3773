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
if ($type != 'doctor' && $type != 'nurse'){
   header("Location: members.php");
}
/* make database connection */

$conn = connect_db();

/* Get prescriptions */ 
$query = "select * 
          from Prescriptions 
          where cid=$cid
          order by date desc";
$result = mysqli_query($conn, $query);
if (!$result){
   echo "Unable to find prescriptions in patient's chart <br>";
}
else{
   while ($row = mysqli_fetch_array($result)){
      $date = $row[2];
      $medication = $row[3];
      $dosage = $row[4];
      $refills = $row[5];
      $prescribed_by = $row[6];
      echo "Medication: $medication<br>";
      echo "Dosage: $dosage<br>";
      echo "Refills: $refills<br>";
      echo "Prescribed by: $prescribed_by<br>";
      echo "Date: $date<br>";
      echo "<br><br>";
   }
}
?>
