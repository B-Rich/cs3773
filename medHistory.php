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
  
echo "Patient: $fname $minit $lname:";

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
      $surgeries  = $row[16];
      $allergies  = $row[17];
      $currentmeds = $row[18];
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
    echo $chickenpox ? "Yes<br>" : "No<br>";
    echo "Tetanus: ";
    echo $tetanus? "Yes<br>" : "No<br>";
    echo "MMR: ";
    echo $mmr? "Yes<br>" : "No<br>";
    echo "Alcohol use: ";
    echo ($alcohol == null) ? "Unknown" : $alcohol;
    echo "<br>";
    echo "Tobacco use: ";
    echo ($tobacco == null) ? "Unknown" : $tobacco;
    echo "<br>";
    echo "Exercise frequency: ";
    echo ($exercise == null) ? "Unknown" : $exercise;
    echo "<br>";
    echo "Surgeries:<br>$surgeries<br><br>";
    echo "Allergies:<br>$allergies<br><br>";
    echo "Current medications:<br>$currentmeds<br><br>";

/* print button to edit medical history */

echo
"<form action='editMedHistory.php?cid=$cid' method='POST'>
   <input type = 'submit' value = 'Edit Medical History' name = 'medhist'>
</form>";
}
?>

</body>
</html>
