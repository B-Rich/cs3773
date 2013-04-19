<?php
session_start();
require_once('db.php');

//if user not logged in, redirect to home page
if(!isset($_SESSION['user'])){
   header("Location: login.php");
}

$user = $_SESSION['user'];
$type = $_SESSION['type'];
$patient = $_GET['patient'];
$cid = $_GET['cid'];

//if user doesn't have permission to view this page, redirect to home
if ($type != 'doctor'){
   header("Location: members.php");
}
//make database connection
$conn = connect_db();

//Get current treatment plan 
$query = "select treatmentplan
          from Log
          where cid='$cid' and date=current_date";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) == 0){
   echo "Error: Unable to find patient chart<br>";
}

   $row = mysqli_fetch_array($result);
   $treatmentplan = ($row[0] == null)? "[Not entered]" : $row[0];

//print treatment plan form
echo
   "
   <form action='treatmentplan.php?patient=$patient&cid=$cid' method='POST'>
   Enter Treatment Plan:<br>
   <textarea name = 'treatmentplan' rows='4' cols='50'>$treatmentplan</textarea><br>
   <input type = 'submit' value = 'Submit' name = 'submit'>
</form>";

if (isset($_POST['submit'])){
   $query = "insert into Log(treatmentplan)
             values('$treatmentplan')";
   $result = mysqli_query($conn, $query);
   header("Location: chart.php?patient=$patient");
}

?>
