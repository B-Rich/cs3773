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

/* reset credentials */
if ($type != 'doctor' && $type != 'nurse'){
   header("Location: members.php");
}
$conn = connect_db();

/* get current personal information */
$query = "select u.fname, u.lname, u.minit, u.dob, c.bloodtype, c.familyhistory, c.cid
          from User u, Chart c
          where u.username = c.username and u.username='$patient'";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) == 0){
   $error = "<span class='error'>Unable to find 
   patient information</span><br><br>";
}

else{
   $row = mysqli_fetch_array($result);
   echo "Patient name: $row[0] $row[1] $row[2]<br>
   Date of Birth: $row[3]<br>
   Blood Type: $row[4]<br>";
   $cid = $row[6];
}

?>

<script type="text/javascript">
var fhString = <?=json_encode($row[5])?>;
</script>
   
<input type='button' onclick=displayFH(fhString) value='Display Family History'>

<br><br><br>

<?php
//Find current chart information for today, if exists
$query = "select * 
          from Log
          where cid=$cid and date=current_date";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) == 0){
   echo "Creating chart <br>";
   //start new log entry for today -- ***NEED TO FIX DATABASE
   $query1 = "insert into Log(cid, date)
             values ($cid, current_date)";
   $result1 = mysqli_query($conn, $query1);
   if ($result1 == 0){
      echo "Unable to open patient chart<br>";
   }
   $diagnosis = "[Not entered]";
   $currentcond=  "[Not entered]";
   $treatplan=  "[Not entered]";
   $bp=  "[Not entered]";
   $temp= "[Not entered]";
   $pulse = "[Not entered]";
   $weight= "[Not entered]";
   $height= "[Not entered]";
}
else{
   $row = mysqli_fetch_array($result);
   $diagnosis = $row[2]; 
   $currentcond= $row[3]; 
   $treatplan= $row[4];
   $bp= $row[5];
   $temp= $row[6];
   $pulse = $row[7];
   $weight= $row[8];
   $height= $row[9];
}

//Display all current information
echo
"Vitals:<br>
<ul>
   <li>Blood Pressure: $bp</li>
   <li>Temperature: $temp</li>
   <li>Pulse: $pulse</li>
   <li>Weight: $weight</li>
   <li>Height: $height</li>
   <li>Description of Current Condition: $currentcond</li>
</ul>";

//print out button for current medical condition
echo
"<form action='vitalsform.php?patient=$patient&cid=$cid' method='POST'>
   <input type = 'submit' value = 'Enter Patient Vitals' name = 'vitals'>
</form>";

echo
"
<ul>
   <li>Diagnosis: $diagnosis</li>
   <li>Treatment Plan: $treatplan</li>
</ul>";

//print out buttons for doctor functions
if ($type == 'doctor'){
echo
"<form action='diagnosis.php?patient=$patient&cid=$cid' method='POST'>
   <input type = 'submit' value = 'Enter Diagnosis' name = 'diagnosis'>
</form>";
echo
"<form action='treatmentplan.php?patient=$patient&cid=$cid' method='POST'>
   <input type = 'submit' value = 'Enter Treatment Plan' name = 'treatplan'>
</form>";
}
?>

</body>
<
