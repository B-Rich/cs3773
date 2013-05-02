<?php
session_start();
require_once('db.php');
 
if(!isset($_SESSION['user'])){
   // session not logged in so redirect to login page
   //header("Status: 200"); //might be needed
   header("Location: login.php");
}
?>
<html>
<head> 
<title> Appointment Page</title>
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
<p> <a href="./members.php">BACK~!</a> </p>

<?php
//function display_appointments($doctor){
   $conn = connect_db();
   $res=mysqli_query($conn,"SELECT cid FROM `Member` WHERE username='".$_SESSION['user']."'");
   $row=mysqli_fetch_array($res);
   $cid=$row[0];

   if(isset($_POST['cancelApt'])&&$_SESSION['type']!="doctor"){
		//gets current date time.
		$now=date("Y-m-d H:i:s");
		$eid=$_POST['eid'];
		$cancelAppt = "UPDATE `Appointment`
				  Set`cancel`='".$now."'
				  WHERE eid=".$eid;
		$cancelEvent = "DELETE FROM `EHIS`.`phpc_events` WHERE `phpc_events`.`eid` = ".$eid;
		$cancelOccur = "DELETE FROM `EHIS`.`phpc_occurrences` WHERE `phpc_occurrences`.`eid` = ".$eid;
		if(mysqli_query($conn,$cancelAppt)&&mysqli_query($conn,$cancelEvent)&&mysqli_query($conn,$cancelOccur))
			echo "<br>Appointment Cancelled!<br>";
	}else if(isset($_POST['cancelApt'])&&$_SESSION['type']=="doctor"){
		//gets current date time.
		$now=date("Y-m-d H:i:s");
		$eid=$_POST['eid'];
		$res = mysqli_query($conn,"SELECT cid FROM `Appointment` WHERE eid=".$eid);
		$row=mysqli_fetch_array($res);
		$toCid = $row[0];
		$res=mysqli_query($conn,"SELECT username FROM `Member` WHERE cid=".$toCid);
		$row=mysqli_fetch_array($res);
		$toUsername = $row[0];
		$sendMsg = "INSERT INTO `Message`(`mid`, `to_username`, `from_username`, `time`, `subject`, `text`, `read`) 
					VALUES (NULL,'".$toUsername."','".$_SESSION['user']."','".$now."','CANCELLED','CANCELLED!',0)";
		$cancelAppt = "UPDATE `Appointment`
				  Set`cancel`='".$now."'
				  WHERE eid=".$eid;
		$cancelEvent = "DELETE FROM `EHIS`.`phpc_events` WHERE `phpc_events`.`eid` = ".$eid;
		$cancelOccur = "DELETE FROM `EHIS`.`phpc_occurrences` WHERE `phpc_occurrences`.`eid` = ".$eid;
		if(mysqli_query($conn,$cancelAppt)&&mysqli_query($conn,$cancelEvent)&&mysqli_query($conn,$cancelOccur)&&mysqli_query($conn,$sendMsg));
			echo "<br>Message Sent and Appointment Cancelled!<br>";
	}
   if(isset($_POST['resetApt'])){
		$eid=$_POST['eid'];
		$date=$_POST['start-date'];
		$time=$_POST['time'];
		$query="SELECT appt_type FROM `Appointment` WHERE eid=".$eid;
		$res=mysqli_query($conn,$query);
        $row=mysqli_fetch_array($res);
		$type=$row[0];
		//echo "type:".$type;
		//echo "eid:".$eid;
		//echo "time:".$time;
		//echo "date:".$date;
		if($type == "P"){
		$query = "UPDATE `phpc_occurrences` 
				  SET `start_ts`='".$date." ".$time.":00:00',`end_ts`='".$date." ".$time.":40:00' 
				  WHERE eid=".$eid;
		}
		if($type == "F"){
		$query = "UPDATE `phpc_occurrences` 
				  SET `start_ts`='".$date." ".$time.":00:00',`end_ts`='".$date." ".$time.":30:00' 
				  WHERE eid=".$eid;
		}
		if($type == "R"){
		$query = "UPDATE `phpc_occurrences` 
				  SET `start_ts`='".$date." ".$time.":00:00',`end_ts`='".$date." ".$time.":20:00' 
				  WHERE eid=".$eid;
		}
		if(mysqli_query($conn,$query))
			echo "<br>Appointment Moved!<br>";
	}
   //get time, last name, first name from database for all appointments
   //for the current day, list in order of time (ascending) 
   if($_SESSION['type']=="patient"){
		//$res=mysqli_query($conn,"SELECT cid FROM `Member` WHERE username='".$_SESSION['user']."'");
		//$row=mysqli_fetch_array($res);
		//$cid=$row[0];
		$query = "SELECT phpc_events.eid, cancel, description, start_ts
			FROM `phpc_events` , `phpc_occurrences` , `Appointment`
			WHERE phpc_events.eid = phpc_occurrences.eid
			AND phpc_events.eid = Appointment.eid
			AND Appointment.cid =".$cid;
   
		//execute query and get result
		$result = mysqli_query($conn, $query);
		$n = $result->num_rows;
		//print table with apt # in first colum, lastname and first name in second column
		echo "<table border=\"8\">";
		echo "<tr><td>Apt. NUMBER</td><td>DESCRIPTION</td><td>DATE/TIME</td></tr>";
		for ($i = 1; $i <= $n; $i++){
			$row = mysqli_fetch_assoc($result);
			$time = $row['start_ts'];
			if($row['cancel'] == NULL)
				echo "<tr><td>".$row['eid']. "</td><td>".$row['description']."</td><td>".$time."</td></tr>"; 
		}
		echo "</table>";		
echo <<<block
			<br>To reschedule enter the appointment number and the new date and time
			<form action="./appointments.php" method="POST">
			<input type="text" name="eid" id="eid">
			Date (YYYY-MM-DD): <input type="text" class="form-date" name="start-date" id="start-date">
			<script type="text/javascript">$('#start-date').datepicker({dateFormat: "yy-mm-dd" });</script>
			Time: <select name="time">
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
			<input type="submit" name="resetApt" value="Submit">
			</form>
			<br>To cancel an appointment enter the appointment number and click submit
			<form action="./appointments.php" method="POST">
			<input type="text" name="eid" id="eid">
			<input type="submit" name="cancelApt" value="Cancel">
			</form>
block;
	}
	  if($_SESSION['type']=="doctor"){
		//$res=mysqli_query($conn,"SELECT cid FROM `Member` WHERE username='".$_SESSION['user']."'");
        //$row=mysqli_fetch_array($res);
		//$cid=$row[0];
		$query = "SELECT phpc_events.eid, cancel, subject, description, start_ts
			FROM `phpc_events` , `phpc_occurrences` , `Appointment`
			WHERE phpc_events.eid = phpc_occurrences.eid
			AND phpc_events.eid = Appointment.eid
			AND phpc_events.cid =".$cid;
   
		//execute query and get result
		$result = mysqli_query($conn, $query);
		$n = $result->num_rows;
		//print table with apt # in first colum, lastname and first name in second column
		echo "<table border=\"8\">";
		echo "<tr><td>Apt. NUMBER</td><td>PATIENT NAME</td><td>DESCRIPTION</td><td>DATE/TIME</td></tr>";
		for ($i = 1; $i <= $n; $i++){
			$row = mysqli_fetch_assoc($result);
			$time = $row['start_ts'];
			$name = $row['subject'];
			if($row['cancel'] == NULL)
				echo "<tr><td>".$row['eid']. "</td><td>".$name."</td><td>".$row['description']."</td><td>".$time."</td></tr>"; 
		}
		echo "</table>";
echo <<<block
			</form>
			<br>To cancel an appointment enter the appointment number and click submit
			<form action="./appointments.php" method="POST">
			<input type="text" name="eid" id="eid">
			<input type="submit" name="cancelApt" value="Cancel">
			</form>
block;
	}
	if($_SESSION['type']=="receptionist"){
		$query = "SELECT phpc_events.eid, cancel, subject, description, start_ts
		FROM `phpc_events` , `phpc_occurrences` , `Appointment`
		WHERE phpc_events.eid = phpc_occurrences.eid";
		//execute query and get result
		$result = mysqli_query($conn, $query);
		$n = $result->num_rows;
		//print table with apt # in first colum, lastname and first name in second column
		echo "<table border=\"8\">";
		echo "<tr><td>Apt. NUMBER</td><td>PATIENT NAME</td><td>DESCRIPTION</td><td>DATE/TIME</td></tr>";
		for ($i = 1; $i <= $n; $i++){
			$row = mysqli_fetch_assoc($result);
			$time = $row['start_ts'];
			$name = $row['subject'];
			if($row['cancel'] == NULL)
				echo "<tr><td>".$row['eid']. "</td><td>".$name."</td><td>".$row['description']."</td><td>".$time."</td></tr>"; 
		}
		echo "</table>";
echo <<<block
			</form>
			<br>To cancel an appointment enter the appointment number and click submit
			<form action="./appointments.php" method="POST">
			<input type="text" name="eid" id="eid">
			<input type="submit" name="cancelApt" value="Cancel">
			</form>
block;
	}
   //close connection
   mysqli_close($conn);
  // }
?>
</body>
</html>
