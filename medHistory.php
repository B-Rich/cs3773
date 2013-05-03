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

if ($type != 'doctor' && $type != 'nurse'){
   header("Location: members.php");
}

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
  
echo "Patient: $fname $minit $lname:<br><br>";

/* Get current medical history to display*/
$query = "select *
          from Medical_History
          where cid=$cid
          order by time desc";
$result = mysqli_query($conn, $query);
if (!$result){
   echo "Error: Unable to access database.<br>";
}
if (!($row = mysqli_fetch_array($result))){
   echo "No information has been entered for this patient.<br>";
   echo "
<form action='editMedHistory.php?cid=$cid' method='POST'>
    <input type = 'submit' value = 'Enter Medical History' name = 'medhist'>
    </form>";
}
else{ 

   /* display medical history information */
      $cancer_s = $row[2];
      $cancer_m = $row[3];
      $cancer_f = $row[4];
      $cancerdesc  = $row[5];
      $heart_s = $row[6];
      $heart_m = $row[7];
      $heart_f = $row[8];
      $heartdesc  = $row[9];
      $chickenp  = $row[10];
      $tetanus  = $row[11];
      $mmr  = $row[12];
      $alcohol  = $row[13];
      $tobacco  = $row[14];
      $exercise  = $row[15];
      $bloodtype = $row[16];
      $surgeries  = $row[17];
      $allergies  = $row[18];
      $currentmeds = $row[19];
    echo "Cancer: ";
    if ($cancer_s){
       echo "Self ";
    }
    if ($cancer_m){
       echo "Mother's side ";
    }
    if ($cancer_f){
       echo "Father's side ";
    }
    echo "<br>";
    echo "Cancer description:<br>";
    echo $cancerdesc."<br>";
    echo "Heart disease: ";
    if ($heart_s){
       echo "Self ";
    }
    if ($heart_m){
       echo "Mother's side ";
    }
    if ($heart_f){
       echo "Father's side";
    }
    echo "<br>";
    echo "Heart disease description:<br>";
    echo $heartdesc;
    echo "<br>";
    echo "Vaccines in past 5 years:<br>";
    echo "Chickenpox: ";
    echo $chickenp ? "Yes<br>" : "No<br>";
    echo "Tetanus: ";
    echo $tetanus? "Yes<br>" : "No<br>";
    echo "MMR: ";
    echo $mmr? "Yes<br>" : "No<br>";
    echo "Alcohol use: ";
    switch($alcohol){
       case 'n':
          echo "Never<br>";
          break;
       case 's':
          echo "Sometimes<br>";
          break;
       case 'o':
          echo "Often<br>";
          break;
       default:
          echo "Unknown<br>";
    }
    //echo ($alcohol == null) ? "Unknown" : $alcohol;
    //echo "<br>";
    echo "Tobacco use: ";
   // echo ($tobacco == null) ? "Unknown" : $tobacco;
    switch($tobacco){
       case 'n':
          echo "Never<br>";
          break;
       case 's':
          echo "Sometimes<br>";
          break;
       case 'o':
          echo "Often<br>";
          break;
       default:
          echo "Unknown<br>";
     }
    //echo "<br>";
    echo "Exercise frequency: ";
    switch($tobacco){
       case '1':
          echo "Never<br>";
          break;
       case '2':
          echo "Less than twice per week<br>";
          break;
       case '3':
          echo "2-4 times per week<br>";
          break;
       case '4':
          echo "More than 4 times per week<br>";
          break;
       default:
          echo "Unknown<br>";
     }

    //echo ($exercise == null) ? "Unknown" : $exercise;
    //echo "<br>";
    echo "Blood type: $bloodtype<br>";
    echo "Surgeries:<br>$surgeries<br><br>";
    echo "Allergies:<br>$allergies<br><br>";
    echo "Current medications:<br>$currentmeds<br><br>";

/* print button to edit medical history */

echo
"<form action='editMedHistory.php?cid=$cid' method='POST'>
   <input type = 'submit' value = 'Edit Medical History' name = 'medhist'>
</form>";

echo "<form action='medHistory.php?cid=$cid' method='POST'>";
echo "Add new comment:<br>";
echo "<textarea name = 'comment' rows='4' cols='50'></textarea><br>";
echo "<input type = 'submit' value='Submit' name='submit'>";
echo "</form>";
}

if (isset($_POST['submit'])){
   $comment = sanitizeString($_POST['comment']);
   $query = "insert into Chart_Comments
             values(?, current_timestamp, ?)";
   $stmt = mysqli_prepare($conn, $query);
   mysqli_stmt_bind_param($stmt, 'is', $cid, $comment); 
   mysqli_stmt_execute($stmt);
   if (mysqli_stmt_affected_rows($stmt) == 0){
      echo "Unable to add comment. ".mysqli_error($conn)."<br>";
   }
   else{
      /* reload page */
      header('Location: '.$_SERVER['REQUEST_URI']);
   }
}

/* get old comments */
$query = "select * from 
          Chart_Comments
          order by time desc";
$result = mysqli_query($conn, $query);
if (!$result){
   echo "Unable to load comments<br>";
}
else{
   while ($row = mysqli_fetch_array($result)){
      $time = $row[1];
      $content = $row[2];
      echo "Time: $time<br>";
      echo "$content<br><br><br>";
   }
}

?>

</body>
</html>
