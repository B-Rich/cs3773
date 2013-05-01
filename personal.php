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

/* reset credentials */
if ($type != 'doctor' && $type != 'nurse'){
   header("Location: members.php");
}
$conn = connect_db();

/* get current personal information */
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
   echo "Patient name: ".formatFromDB($row[2])." ".formatFromDB($row[3])." 
          ".formatFromDB($row[4])."<br>
   Gender: ".formatFromDB($row[7])."<br>
   Date of Birth: ".formatFromdB($row[6])."<br>
   Social Security Number: ".formatFromDB($row[5])."<br>
   Email Address: ".formatFromDB($row[8])."<br> 
   Phone Number: ".formatFromDB($row[9])."<br>
   Address: ".formatFromDB($row[10])."<br>
   Emergency Contact: ".formatFromDB($row[11])."<br>
   Emergency Contact Phone Number: ".formatFromDB($row[12])."<br>
   Insurance Company: ".formatFromDB($row[13])."<br>
   Group Number: ".formatFromDB($row[14])."<br>
   Policy Number: ".formatFromDB($row[15])."<br><br>";
}

/* print out button for editing info */
echo
"<form action='editPersonal.php?cid=$cid' method='POST'>
   <input type = 'submit' value = 'Edit Personal Information' name = 'edit'>
</form>";
?>

</body>
</html>
