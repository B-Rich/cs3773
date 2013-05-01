<?php
session_start();
require_once('db.php');
// the no cache might be needed in case we run into
// problems with displaying cached pages
//include('no_cache.php'); 
if(!isset($_SESSION['user'])){
   // session not logged in so redirect to login page
   //header("Status: 200"); //might be needed
   header("Location: login.php");
}
   //session logged 
   //display the contents of members.php
   //notice that the closing brace is not until the bottom of the page		
?>
<html>
<head> 
<title> Member's Page</title>
<script src="linknav.js"></script>
<link rel="stylesheet" type="text/css" href="./calendar/static/phpc.css">
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/base/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="./calendar/static/jquery-ui-timepicker.css">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="./calendar/static/phpc.js"></script>
<script type="text/javascript" src="./calendar/static/jquery.ui.timepicker.js"></script>
<script type="text/javascript" src="./calendar/static/jquery.hoverIntent.minified.js"></script>
</head>
<body>

<?php

      // connect to the DB:
      $conn = connect_db(); 
if(isset($_POST['setApt'])){

   $startDate = $_POST['start-date'];
   $startTime = $_POST['start-time'];
   $aType = $_POST['apt-type'];
   $cid = $_POST['cid'];
   $fname;
   $lname;

   mysqli_query($conn,"SELECT fname lname    ");
   $patient = $fname." ".$lname;
//echo "this is teh type you entered:".$aType."<br>";
//echo "this is teh cid you entered:".$cid."<br>";
//echo "this is teh patient you entered:".$patient."<br>";
//echo "this is teh date you entered:".$startDate."<br>";
//echo "this is the time you entered:".$startTime."<br>";
//mysqli_query($conn,"INSERT INTO `Appointment`(`aid`, `patient`, `checkout_time`, `arrival_time`, `appt_type`) VALUES ('1','".$patient."',NULL,NULL,'".$aType."')");
$ownerQuery="SELECT uid FROM `phpc_users` WHERE username='".$_SESSION['user']."'";
$res=mysqli_query($conn,$ownerQuery);
$row=mysqli_fetch_array($res);
$owner=$row[0];
     $eventQuery = "INSERT INTO `EHIS`.`phpc_events` (
                `eid` ,
                `cid` ,
                `owner` ,
                `subject` ,
                `description` ,
                `readonly` ,
                `catid`
               )
               VALUES (NULL, '".$cid."' , '3' , 'Appointment with ".$patient."', 'This user is schedualing an appointment', '0',NULL)";
    mysqli_query($conn,"INSERT INTO `Appointment`(`aid`, `patient`, `checkout_time`, `arrival_time`, `appt_type`) VALUES ('1','".$patient."',NULL,NULL,'".$aType."')");
    mysqli_query($conn, $eventQuery);
    $eidQuery="SELECT MAX(eid) FROM `phpc_events`";
    $res= mysqli_query($conn,$eidQuery);
    $row = mysqli_fetch_array($res);
    $eid = $row[0];
//echo"eid:".$eid."<br>";
if($aType=="P"){
	$occurQuery = "INSERT INTO `EHIS`.`phpc_occurrences` (`oid`, `eid`, `start_date`, `end_date`, `start_ts`, `end_ts`, `time_type`) VALUES (NULL, '".$eid."', NULL, NULL, '".$startDate." ".$startTime.":00:00', '".$startDate." ".$startTime.":40:00', '0')";
}
if($aType=="F"){
	$occurQuery = "INSERT INTO `EHIS`.`phpc_occurrences` (`oid`, `eid`, `start_date`, `end_date`, `start_ts`, `end_ts`, `time_type`) VALUES (NULL, '".$eid."', NULL, NULL, '".$startDate." ".$startTime.":00:00', '".$startDate." ".$startTime.":30:00', '0')";
}
if($aType=="R"){
	$occurQuery = "INSERT INTO `EHIS`.`phpc_occurrences` (`oid`, `eid`, `start_date`, `end_date`, `start_ts`, `end_ts`, `time_type`) VALUES (NULL, '".$eid."', NULL, NULL, '".$startDate." ".$startTime.":00:00', '".$startDate." ".$startTime.":20:00', '0')";
}
//echo"occurQuery:".$occurQuery."<br>";  
    // execute the query and return the results
     if( mysqli_query($conn, $occurQuery))
	echo "<br> You have schedualed an appointment! <br>";

      //close connection to the database
     mysqli_close($conn);
   } 
      //print out user type
      echo "I am a " . $_SESSION['type'];
   if($_SESSION['type']!="patient" && $_SESSION['type']!="nurse"){
      if($_SESSION['type']=="receptionist"){
         echo "<form action=\"/cs3773/calendar/index.php\" method=\"post\">";
         echo "<input name=\"action\" value=\"login\" type=\"hidden\">";
         echo "<input name=\"submit\" value=\"CALENDAR!!!\" type=\"submit\">";
         echo "<input name=\"username\" value=\"receptionist\" type=\"hidden\">";
         echo "<input name=\"password\" value=\"password\" type=\"hidden\">";
         echo "</form>";
      }else{
         echo "<form action=\"/cs3773/calendar/index.php\" method=\"post\">";
	 echo "<input name=\"action\" value=\"login\" type=\"hidden\">";
	 echo "<input name=\"submit\" value=\"CALENDAR!!!\" type=\"submit\">";
	 echo "<input name=\"username\" value=\"" . $_SESSION['user'] . "\" type=\"hidden\">";
	 echo "<input name=\"password\" value=\"" . $_SESSION['pass'] . "\" type=\"hidden\">";
	 echo "</form>";
      }
   } 
      if($_SESSION['type']=="patient"||$_SESSION['type']=="receptionist"){
	if($_SESSION['type']=="patient"){
echo <<<_END
        <form action="/cs3773/members.php" method="POST">
        <th>Schedual Next Appointment?:</th>
         Date (YYYY/MM/DD): <input type="text" class="form-date" name="start-date" id="start-date">
        <script type="text/javascript">$('#start-date').datepicker({dateFormat: "yy-mm-dd" });</script>
        Doctor: <select name="cid">
                <option value="1">Dr.Garza</option>
                <option value="2">Dr.Taylor</option>
                </select>
        Time: <select name="start-time">
                <option value="13">1:00pm</option>
                <option value="14">2:00pm</option>
                <option value="15">3:00pm</option>
                </select>
        Type:<select name="apt-type">
                <option value="P">Physical Exam</option>
                <option value="F">Follow-Up</option>
                <option value="R">Regular</option>
                </select>

        <input type="submit" name="setApt" value="Submit Appointment">
        </form>
_END;


	}
//receptionist needs to select a patient to schedual
//an appoint for	
echo <<<_END
	<form action="/cs3773/members.php" method="POST">
	<th>Schedual Next Appointment?:</th>
	 Date (YYYY/MM/DD): <input type="text" class="form-date" name="start-date" id="start-date">
	<script type="text/javascript">$('#start-date').datepicker({dateFormat: "yy-mm-dd" });</script>
	Doctor: <select name="cid">
                <option value="1">Dr.Garza</option>
                <option value="2">Dr.Taylor</option>
                </select> 
        Time: <select name="start-time">
		<option value="13">1:00pm</option>
		<option value="14">2:00pm</option>
		<option value="15">3:00pm</option>
		</select>
	Type:<select name="apt-type">
                <option value="P">Physical Exam</option>
                <option value="F">Follow-Up</option>
                <option value="R">Regular</option>
                </select>

	<input type="submit" name="setApt" value="Submit Appointment">
	</form>
_END;
	}
	
      //close connection to the database
      mysqli_close($conn);
?>
<p> <a href="./appointments.php">APPOINTMENTS!</a> </p>
<p> <a href="./searchchart.php">SEARCH CHART!</a> </p>
<p> <a href="./messages.php">SEND MESSAGING!</a> </p>
<p> <a href="./inbox.php">INBOX!</a> </p>
<p> <a href="./changepassword.php">CHANGE PASSWORD</a> </p>
<p> <a href="javascript:gotoLogout()">Logout</a> </p>
</body>
</html>

<?php
?>
