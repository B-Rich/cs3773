<html>
<body>

<?php
session_start();
require_once('db.php');
require_once('functions.php');

/* if user not logged in, redirect to home page */
if(!isset($_SESSION['user'])){
   header("Location: login.php");
}

$user = $_SESSION['user'];
$type = $_SESSION['type'];
$cid= $_GET['cid'];
$conn = connect_db();

/* get and display basic patient information */
$query = "select fname, minit, lname
          from Personal_Info
          where cid=$cid";
$result = mysqli_query($conn, $query);
if (!$result || mysqli_num_rows($result) == 0){
   echo "Error: Unable to find patient information<br>";  
}
else{
   $row = mysqli_fetch_array($result);
   $fname = $row[0];
   $minit = $row[1];
   $lname = $row[2];
}
  
echo "Past appointment information for patient $fname $minit $lname:";
echo "<br><br><br>";

/* Get log information for patient */
$query = "select *
          from Log
          where cid=$cid
          order by date desc";
$result = mysqli_query($conn, $query);
if ($result == false || mysqli_num_rows($result) == 0){
   echo "Error: Unable to find patient chart<br>";  
}
else{
   while ($row = mysqli_fetch_array($result)){
      /* get appointment info */
      $date = $row[1];
      $diagnosis = formatFromDB($row[2]);
      $currentcond = formatFromDB($row[3]);
      $tplan = formatFromDB($row[4]);
      $bp = formatFromDB($row[5]);
      $temp = formatFromDB($row[6]);
      $pulse = formatFromDB($row[7]);
      $weight = formatFromDB($row[8]);
      $height = formatFromDB($row[9]);
      /* display appointment info */ 
      echo "Date: $date<br>";
      echo "Condition $currentcond<br>";
      echo "Vitals:<br>";
      echo "Blood pressure: $bp<br>";
      echo "Temperature: $temp<br>";
      echo "Pulse: $pulse<br>";
      echo "Weight: $weight<br>";
      echo "Height: $height<br>";
      echo "Diagnosis: $diagnosis<br>";
      echo "Treatment Plan: $tplan<br>";
      echo "<br><br><br>";
   }
}
?>

</body>
</html>
