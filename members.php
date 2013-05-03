<?php
session_start();
require_once('db.php');
// the no cache might be needed in case we run into
// problems with displaying cached pages
//include('no_cache.php'); 
//include_once 'header.php';
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
    //get patient's first and last by getting cid
    if($_SESSION['type']=="patient"){
        //if patient get their cid from their user name
        $res=mysqli_query($conn,"SELECT cid FROM `Member` WHERE username='".$_SESSION['user']."'");
        $row=mysqli_fetch_array($res);
        $cid=$row[0];//this will be used to fetch the patient's name
		//echo "<br>Patient CID:".$cid."<br>";
    }
 
    if(isset($_POST['setApt'])){

        $startDate = $_POST['start-date'];
        $startTime = $_POST['start-time'];
        $aType = $_POST['apt-type'];//appointment type
        $calendarId = $_POST['calendarId'];//which/who's calendar/which doctor
        $fname;
        $lname;
        $dateToCheck = $startDate." ".$startTime.":00:00";
        $queryCheckAppt = "SELECT start_ts
                          FROM `phpc_events` , `phpc_occurrences` , `Appointment`
                          WHERE phpc_events.eid = phpc_occurrences.eid
                          AND phpc_events.eid = Appointment.eid
                          AND phpc_events.cid =".$calendarId;
        $res = mysqli_query($conn,$queryCheckAppt);
        $row = mysqli_fetch_array($res);
        $dateFromDB = $row[0];
        if($dateToCheck==$dateFromDB)
            echo "Try again. This time slot with this doctor is taken sorry :/<br>";
        else{
			//echo "built up date time to check against DB<br>".$dateToCheck."<br>";
			//TO DO!
			//check to make sure that the block of time that they are try to schedual is 
			//actually free for that particular doctor
			//it'll be a receptionist they will get patient name from drop down menu
			if(isset($_POST['cid'])){
				$cid=$_POST['cid'];//patient's cid, this occurs when a receptionist makes an appt.
			}
			$res=mysqli_query($conn,"SELECT fname, lname FROM `Personal_Info` WHERE cid=".$cid);
			$row=mysqli_fetch_array($res);
			$fname=$row[0];
			$lname=$row[1];
			$patientName = $fname." ".$lname;
			
			//echo "<br>patient's name!<br>".$patientName."<br>";
			
			//$ownerQuery="SELECT uid FROM `phpc_users` WHERE username='".$_SESSION['user']."'";
			//$res=mysqli_query($conn,$ownerQuery);
			//$row=mysqli_fetch_array($res);
			//$owner=$row[0];
			if($aType=="P"){
				$eventQuery = "INSERT INTO `EHIS`.`phpc_events` (
							`eid` ,
							`cid` ,
							`owner` ,
							`subject` ,
							`description` ,
							`readonly` ,
							`catid`
							   )
				VALUES (NULL, '".$calendarId."' , '3' , '".$patientName."', 'Physical Exam', '0',NULL)";
					mysqli_query($conn, $eventQuery);
					$eidQuery="SELECT MAX(eid) FROM `phpc_events`";
					$res= mysqli_query($conn,$eidQuery);
					$row = mysqli_fetch_array($res);
					$eid = $row[0];
			
				$occurQuery = "INSERT INTO `EHIS`.`phpc_occurrences` (`oid`, `eid`, `start_date`, `end_date`, `start_ts`, `end_ts`, `time_type`) VALUES (NULL, '".$eid."', NULL, NULL, '".$startDate." ".$startTime.":00:00', '".$startDate." ".$startTime.":40:00', '0')";
			}

			if($aType=="F"){
				//this query creates an event
				$eventQuery = "INSERT INTO `EHIS`.`phpc_events` (
							`eid` ,
							`cid` ,
							`owner` ,
							`subject` ,
							`description` ,
							`readonly` ,
							`catid`
							   )
				VALUES (NULL, '".$calendarId."' , '3' , '".$patientName."', 'Follow-Up', '0',NULL)";
				mysqli_query($conn, $eventQuery);
				//occur query needs the eid of a particular event, the last event made will have the highest eid                
				$eidQuery="SELECT MAX(eid) FROM `phpc_events`";
					$res= mysqli_query($conn,$eidQuery);
					$row = mysqli_fetch_array($res);
					$eid = $row[0];
				//this query creates an occurance
				$occurQuery = "INSERT INTO `EHIS`.`phpc_occurrences` (`oid`, `eid`, `start_date`, `end_date`, `start_ts`, `end_ts`, `time_type`) VALUES (NULL, '".$eid."', NULL, NULL, '".$startDate." ".$startTime.":00:00', '".$startDate." ".$startTime.":30:00', '0')";
			}

			if($aType=="R"){
				$eventQuery = "INSERT INTO `EHIS`.`phpc_events` (
							`eid` ,
							`cid` ,
							`owner` ,
							`subject` ,
							`description` ,
							`readonly` ,
							`catid`
							   )
				VALUES (NULL, '".$calendarId."' , '3' , '".$patientName."', 'Regular', '0',NULL)";
					mysqli_query($conn, $eventQuery);
					$eidQuery="SELECT MAX(eid) FROM `phpc_events`";
					$res= mysqli_query($conn,$eidQuery);
					$row = mysqli_fetch_array($res);
					$eid = $row[0];
				
				$occurQuery = "INSERT INTO `EHIS`.`phpc_occurrences` (`oid`, `eid`, `start_date`, `end_date`, `start_ts`, `end_ts`, `time_type`) VALUES (NULL, '".$eid."', NULL, NULL, '".$startDate." ".$startTime.":00:00', '".$startDate." ".$startTime.":20:00', '0')";
			}
			//make a new appointment tuple
			mysqli_query($conn,"INSERT INTO `Appointment`(`eid`, `cid`, `checkout_time`, `arrival_time`, `appt_type`) VALUES ('".$eid."','".$cid."',NULL,NULL,'".$aType."')");
			//execute the query and return the results this query makes a row in the phpc occurances table
			if( mysqli_query($conn, $occurQuery))
				echo "<br>Successfully Scheduled an Appointment!<br>";
        }

    } 
    //print out user type
    //echo "I am a " . $_SESSION['type'];
          if($_SESSION['type']=="receptionist"){
        //this will log the receptionist into the calendar once they click the button
            echo "<form action=\"/cs3773/calendar/index.php\" method=\"post\">";
            echo "<input name=\"action\" value=\"login\" type=\"hidden\">";
            echo "<input name=\"submit\" value=\"CALENDAR!!!\" type=\"submit\">";
            echo "<input name=\"username\" value=\"receptionist\" type=\"hidden\">";
            echo "<input name=\"password\" value=\"password\" type=\"hidden\">";
            echo "</form>";
//receptionist needs to select a patient to schedual
//an appointment for
echo <<<_END
    If you need to reschedule an appointment then please use the calendar function! Thank You<br>
    If you need to cancel an appointment then please go to the appointment page below! <br>
    <form action="./members.php" method="POST">
    <th>Schedule Next Appointment?:</th>
    <br>Date (YYYY-MM-DD): <input type="text" class="form-date" name="start-date" id="start-date">
    <script type="text/javascript">$('#start-date').datepicker({dateFormat: "yy-mm-dd" });</script>
	<br>Patient: <select name="cid">
_END;
		$res=mysqli_query($conn,"SELECT Personal_Info.cid,fname, lname FROM `Personal_Info`, `Member` WHERE Personal_Info.cid=Member.cid AND Member.type='patient'");
		$n = $res->num_rows;
		for ($i = 1; $i <= $n; $i++){
			$row = mysqli_fetch_assoc($res);
			echo "<option value=\"".$row['cid']."\">".$row['fname']." ".$row['lname']."</option>";
			//echo "<br>eid:<br>";
		}
echo <<<_END
                </select>     
    <br>Doctor: <select name="calendarId">
                <option value="1">Dr.Taylor</option>
                <option value="2">Dr.Garza</option>
                </select> 
    <br>Time: <select name="start-time">
        <option value="13">1:00pm</option>
        <option value="14">2:00pm</option>
        <option value="15">3:00pm</option>
        </select>
    <br>Type:<select name="apt-type">
                <option value="P">Physical Exam</option>
                <option value="F">Follow-Up</option>
                <option value="R">Regular</option>
                </select>
    <input type="submit" name="setApt" value="Submit Appointment">
    </form>
_END;
    }    
    if($_SESSION['type']=="doctor"){
        //doctor's have their own user account for the calendar
        //this account mimics their ehis user account
        echo "<br>If you need to reschedule an appointment then please use the calendar function! Thank You<br>";
        echo "If you need to cancel an appointment then please go to the appointment page below! <br>";
        echo "<form action=\"/cs3773/calendar/index.php\" method=\"post\">";
        echo "<input name=\"action\" value=\"login\" type=\"hidden\">";
        echo "<input name=\"submit\" value=\"CALENDAR!!!\" type=\"submit\">";
        echo "<input name=\"username\" value=\"" . $_SESSION['user'] . "\" type=\"hidden\">";
        echo "<input name=\"password\" value=\"" . $_SESSION['pass'] . "\" type=\"hidden\">";
        echo "</form>";
    }
 
    if($_SESSION['type']=="patient"){
    //patients are able to sched. appointments but not see any calendars
echo <<<_END
        <form action="./members.php" method="POST">
        <th>Schedule Next Appointment?:</th>
        <br>Date (YYYY-MM-DD): <input type="text" class="form-date" name="start-date" id="start-date">
        <script type="text/javascript">$('#start-date').datepicker({dateFormat: "yy-mm-dd" });</script>
        <br>Doctor: <select name="calendarId">
                <option value="1">Dr.Taylor</option>
                <option value="2">Dr.Garza</option>
                </select>
        <br>Time: <select name="start-time">
                <option value="09">09:00am</option>
                <option value="10">10:00am</option>
                <option value="11">11:00am</option>
                <option value="12">12:00pm</option>
                <option value="13">1:00pm</option>
                <option value="14">2:00pm</option>
                <option value="15">3:00pm</option>
                <option value="16">4:00pm</option>
                <option value="17">5:00pm</option>
                </select>
        <br>Type:<select name="apt-type">
                <option value="P">Physical Exam</option>
                <option value="F">Follow-Up</option>
                <option value="R">Regular</option>
                </select>
        <br><input type="submit" name="setApt" value="Submit Appointment">
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


