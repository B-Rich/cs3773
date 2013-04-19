<html>
<body>

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
$cid= $_GET['cid'];
$conn = connect_db();
   
echo "Enter Vitals For Patient $patient:";

//Get current vitals
$query = "select *
          from Log
          where cid='$cid' and date=current_date";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) == 0){
   echo "Error: Unable to find patient chart<br>";  
}

   $row = mysqli_fetch_array($result);
   $currentcond = ($row[3] == null)? "[Not entered]" : $row[3];
   $bp= ($row[5] == null)? "[Not entered]" : $row[5];
   $temp = ($row[6] == null)? "[Not entered]" : $row[6];
   $pulse = ($row[7] == null)? "[Not entered]" : $row[7];
   $weight = ($row[8] == null)? "[Not entered]" : $row[8];
   $height = ($row[9] == null)? "[Not entered]" : $row[9];

//print vitals form
echo
   "
   <form action='vitalsform.php?patient=$patient&cid=$cid' method='POST'>
   Description of Current Condition:<br>
   <textarea name = 'currentcond' rows='4' cols='50'>$currentcond</textarea><br>
   Blood Pressure: <input type = 'text' name = 'bp' value=\"$bp\" size = '20'><br>
   Temperature: <input type = 'text' name = 'temp' value=\"$temp\" size = '20'><br>
   Pulse: <input type = 'text' name = 'pulse' value=\"$pulse\" size = '20'><br>
   Weight: <input type = 'text' name = 'weight' value=\"$weight\"size = '20'><br>
   Height: <input type = 'text' name = 'height' value=\"$height\" size = '20'><br>
   <input type = 'submit' value = 'Submit' name = 'submit'>
</form>";

if (isset($_POST['submit'])){
   $query = "insert into Log(currentmedcondition, bp, temp, pulse, weight, height)
             values('$currentcond', '$bp', '$temp', '$pulse', '$weight', '$height')";
   $result = mysqli_query($conn, $query);
   header("Location: chart.php?patient=$patient");
}

?>

</body>
</html>
