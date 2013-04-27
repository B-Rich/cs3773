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
<link rel="stylesheet" type="text/css" href="http://ehisproject.dyndns.org/cs3773/calendar/static/phpc.css">
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/base/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="http://ehisproject.dyndns.org/cs3773/calendar/static/jquery-ui-timepicker.css">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="http://ehisproject.dyndns.org/cs3773/calendar/static/phpc.js"></script>
<script type="text/javascript" src="http://ehisproject.dyndns.org/cs3773/calendar/static/jquery.ui.timepicker.js"></script>
<script type="text/javascript" src="http://ehisproject.dyndns.org/cs3773/calendar/static/jquery.hoverIntent.minified.js"></script>
</head>
<body>

<?php

      // connect to the DB
      $conn = connect_db(); 
      
      //print out user type
      echo "I am a " . $_SESSION['type'];
      if($_SESSION['type']!="patient" && $_SESSION['type']!="nurse"){
	echo "<form action=\"/cs3773/calendar/index.php\" method=\"post\">";
	echo "<input name=\"action\" value=\"login\" type=\"hidden\">";
	echo "<input name=\"submit\" value=\"CALENDAR!!!\" type=\"submit\">";
	echo "<input name=\"username\" value=\"" . $_SESSION['user'] . "\" type=\"hidden\">";
	echo "<input name=\"password\" value=\"" . $_SESSION['pass'] . "\" type=\"hidden\">";
	echo "</form>";
	} 
      if($_SESSION['type']=="patient"){
echo <<<_END
	<tr class="form-part form-group">
	<td><table><tr class="form-part form-question form-atomic-question form-date-time-question"><th>Schedual Next Appointment?:</th>
	<td>Date (MM/DD/YYYY): <input type="text" class="form-date" name="start-date" id="start-date">
	<script type="text/javascript">$('#start-date').datepicker({dateFormat: "mm/dd/yy" });</script>
	 Time: <input type="text" class="form-time" name="start-time" id="start-time">
	<script type="text/javascript">$('#start-time').timepicker({showPeriod: true, showLeadingZero: false });</script>
	</td>
	</tr>
_END;
	}
	
      //close connection to the database
      mysqli_close($conn);
?>
<p> <a href="appointments.php">APPOINTMENTS!</a> </p>
<p> <a href="searchchart.php">SEARCH CHART!</a> </p>
<p> <a href="messages.php">SEND MESSAGING!</a> </p>
<p> <a href="inbox.php">INBOX!</a> </p>
<p> <a href="javascript:gotoLogout()">Logout</a> </p>
</body>
</html>

<?php
?>
