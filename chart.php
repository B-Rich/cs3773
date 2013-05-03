<html>
<body>

<?php
require_once('db.php');
require_once('functions.php');
session_start();

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
if ($type != 'patient'){
   echo "<form action='chart.php?cid=$cid' method='POST'>
         <input type = 'submit' value = 'Check In' name = 'checkIn'>
         </form>";
}
if (($type == 'doctor') || ($type == 'nurse')){
   echo "<a href='vitalsform.php?cid=$cid'>Enter Current Medical Information</a><br>";
   echo "<a href='pastlog.php?cid=$cid'>Previous Appointments</a><br>";
   echo "<a href='medHistory.php?cid=$cid'>Medical History</a><br>";
   echo "<a href='viewprescriptions.php?cid=$cid'>View Prescriptions</a><br>";
   echo "<a href='viewtestorders.php?cid=$cid'>View Test Orders</a><br>";
   echo "<a href='treatmentsgiven.php?cid=$cid'>Enter Treatments Given</a><br>";
   echo "<a href='addDocument.php?cid=$cid'>Add Document to Patient Chart</a><br>";
   echo "<a href='viewDocuments.php?cid=$cid'>View Patient's Documents</a><br>";
}
if ($type == 'doctor'){
   echo "<a href='diagnosis.php?cid=$cid'>Enter Diagnosis / Treatment Plan</a><br>";
   echo "<a href='prescription.php?cid=$cid'>Write Prescription</a><br>";
   echo "<a href='testorder.php?cid=$cid'>Create Test Order</a><br>";
}

if (isset($_POST['checkIn'])){
   $query =  "select a.eid, a.arrival_time, o.start_ts
             from Appointment a, phpc_occurrences o
             where a.cid=$cid and a.eid = o.eid and CAST(o.start_ts as DATE) = CURRENT_DATE";
   $result = mysqli_query($conn, $query);
   if (!$result || (mysqli_num_rows($result) == 0)){
      echo "Unable to find appointment<br>";
   }
   else{
      $row = mysqli_fetch_array($result);
      $eid = $row[0];
      $arrival = $row[1];
      $start = $row[2];
      if (isset($arrival)){
         echo "Patient already checked in<br>";
         echo "Arrival time: $arrival<br>";
      }
      else{
         $query = "update Appointment
                   set arrival_time=current_timestamp
                   where eid=$eid";
         $result = mysqli_query($conn, $query);
         if (!$result){
            echo "Unable to check patient in<br>";
         }
         else{
            echo "Patient is checked in.<br>";
         }
         $query = "select timestampdiff(MINUTE, o.start_ts, timestamp(curdate(), a.arrival_time))
                   from Appointment a, phpc_occurrences o
                   where a.eid = o.eid and o.eid=$eid";

         $result = mysqli_query($conn, $query);
         if (!$result || mysqli_num_rows($result) == 0){
            echo "Unable to find appointment data <br>";
            die;
         }
         $row = mysqli_fetch_array($result);
         if ($row[0] > 30){
             /* generate late fee */
             echo "Check in is ". $row[0]." minutes late<br>";
             echo "Generating late fee <br>";
             $query = "insert into Bill
                       values ($eid, $cid, current_date, 'late fee', 30.00)";
             $result = mysqli_query($conn, $query);
             if (!$result){
                echo "Error charging late fee<br>"; 
                die; 
             }
         }  
      }
   }
}
}
?>

</body>
</html>
