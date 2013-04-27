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
$patient = $_GET['patient'];
$conn = connect_db();

echo "<a href='log.php?patient=$patient'>Current Appointment</a><br>";
/* not implemented yet */
echo "<a href='pastlog.php?patient=$patient'>Past Appointments</a><br>";
echo "<a href='personal.php?patient=$patient'>Personal Information</a><br>";

?>

</body>
</html>
