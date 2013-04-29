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
$cid= $_GET['cid'];
$conn = connect_db();

/* Get and display basic patient information */
$query = "select fname, minit, lname
          from Personal_Info
          where cid=$cid";
$result = mysqli_query($conn, $query);
if (!$result || mysqli_num_rows($result) == 0){
   echo "Error: Unable to find patient information<br>";  
}
else{
   $row = mysqli_fetch_array($result);
   $fname = $row[0];
   $minit = $row[1];
   $lname = $row[2];
}
  
echo "Enter Vitals For Patient $fname $minit $lname:";
//FIX - need to create log file if doesn't exist yet

/* Get current vitals to display in form */
$query = "select *
          from Log
          where cid=$cid and date=current_date";
$result = mysqli_query($conn, $query);
if ($result == false || mysqli_num_rows($result) == 0){
   echo "Error: Unable to find patient chart<br>";  
}
else{

   $row = mysqli_fetch_array($result);
   $currentcond = formatFromDB($row[3]);
   $bp = formatFromDB($row[5]);
   $temp = formatFromDB($row[6]);
   $pulse = formatFromDB($row[7]);
   $weight = formatFromDB($row[8]);
   $height = formatFromDB($row[9]);

/* print vitals form */
echo "<form action='vitalsform.php?cid=$cid' method='POST'>
   Description of Current Condition:<br>
   <textarea name = 'currentcond' rows='4' cols='50'>$currentcond</textarea><br>
   Blood Pressure: <input type = 'text' name = 'bp' value=\"$bp\" size = '20'><br>
   Temperature: <input type = 'text' name = 'temp' value=\"$temp\" size = '20'><br>
   Pulse: <input type = 'text' name = 'pulse' value=\"$pulse\" size = '20'><br>
   Weight: <input type = 'text' name = 'weight' value=\"$weight\"size = '20'><br>
   Height: <input type = 'text' name = 'height' value=\"$height\" size = '20'><br>
   <input type = 'submit' value = 'Submit' name = 'submit'>
   </form>";

/* submit changes to database when user presses submit */
   if (isset($_POST['submit'])){
      $currentcond = $_POST['currentcond'];
      $bp = $_POST['bp'];
      $temp = $_POST['temp'];
      $pulse = $_POST['pulse'];
      $weight = $_POST['weight'];
      $height = $_POST['height'];
   
   
      $query = "update Log
                set currentmedcondition=?, bp=?, temp=?, pulse=?, weight=?, height=?
                where cid=$cid and date=current_date";
      $stmt = mysqli_prepare($conn, $query);
      mysqli_stmt_bind_param($stmt, 'ssdidd', $currentcond, $bp, $temp, $pulse, 
                             $weight, $height);
        
      /* execute prepared statement */
      mysqli_stmt_execute($stmt);
      if (mysqli_stmt_affected_rows($stmt) == 0){
         echo "Error saving vitals<br>".mysql_error()."<br>";
      }
      
      /* reload page */
      header('Location: '.$_SERVER['REQUEST_URI']);
   }
}
?>

</body>
</html>
