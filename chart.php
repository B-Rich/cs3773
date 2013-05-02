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
$cid = $_GET['cid'];
$conn = connect_db();

/* get and display patient's basic personal information */
$query = "select *
          from Personal_Info p
          where p.cid = $cid
          order by time desc";

$result = mysqli_query($conn, $query);
if (!$result || (mysqli_num_rows($result) == 0)){
   $error = "<span class='error'>Unable to find 
   patient information</span><br><br>";
}

else{
   $row = mysqli_fetch_array($result);
   
   $fname = $row[2];
   $minit = $row[3];
   $lname = $row[4];
   $dob = $row[6];
   $gender = formatFromDB($row[7]);
   
   echo "
   Patient: $fname $minit $lname <br>
   Date of Birth: $dob <br>
   Gender: $gender <br><br>";
   
   /* print links to patient's chart */
   
   echo "<a href='personal.php?cid=$cid'>Personal Information</a><br>";
  // echo "<a href='log.php?cid=$cid'>Current Appointment</a><br>";
   echo "<a href='vitalsform.php?cid=$cid'>Enter Current Medical Information</a><br>";
   echo "<a href='pastlog.php?cid=$cid'>Previous Appointments</a><br>";
   echo "<a href='medHistory.php?cid=$cid'>Medical History</a><br>";
   echo "<a href='diagnosis.php?cid=$cid'>Enter Diagnosis / Treatment Plan</a><br>";
   echo "<a href='prescription.php?cid=$cid'>Write Prescription</a><br>";
   echo "<a href='viewprescriptions.php?cid=$cid'>View Prescriptions</a><br>";
   echo "<a href='testorder.php?cid=$cid'>Create Test Order</a><br>";
   echo "<a href='viewtestorders.php?cid=$cid'>View Test Orders</a><br>";
   echo "<a href='treatmentsgiven.php?cid=$cid'>Enter Treatments Given</a><br>";
}
?>

</body>
</html>
