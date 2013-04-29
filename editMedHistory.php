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

/* print medical history form */
// FIX - prefill form with existing values

  // Temperature: <input type = 'text' name = 'temp' value=\"$temp\" size = '20'><br>
echo "<form action='editMedHistory.php?cid=$cid' method='POST'>
   Enter Medical History information below:<br><br>
   Have you or a family member had cancer?
   <input type='checkbox' name ='cancer_s' value ='1'>Self   
   <input type='checkbox' name ='cancer_m' value ='1'>Mother's Side
   <input type='checkbox' name ='cancer_f' value ='1'>Father's Side<br>
   If yes, please describe:<br>
   <textarea name = 'cancerdesc' rows='4' cols='50'></textarea><br>
   Have you or a family member had heart disease?
   <input type='checkbox' name ='heart_s' value ='1'>Self   
   <input type='checkbox' name ='heart_m' value ='1'>Mother's Side
   <input type='checkbox' name ='heart_f' value ='1'>Father's Side<br>
   If yes, please describe:<br>
   <textarea name = 'heartdesc' rows='4' cols='50'></textarea><br>
   Have you had the following immunizations within the past 5 years:
   <input type='checkbox' name ='chickenp' value ='1'>Chickenpox<br>
   <input type='checkbox' name ='tetanus' value ='1'>Tetanus<br>
   <input type='checkbox' name ='mmr' value ='1'>MMR<br>
   How often do you consume alcoholic beverages?<br>
   <input type='radio' name='alcohol' value='n'>Never<br>
   <input type='radio' name='alcohol' value='_s'>Sometimes<br>
   <input type='radio' name='alcohol' value='o'>Often<br>
   How often do you smoke or use tobacco?<br>
   <input type='radio' name='tobacco' value='n'>Never<br>
   <input type='radio' name='tobacco' value='s'>Sometimes<br>
   <input type='radio' name='tobacco' value='o'>Often<br>
   How often do you exercise?<br>
   <input type='radio' name='exercise' value='1'>Never<br>
   <input type='radio' name='exercise' value='2'>Less than twice per week<br>
   <input type='radio' name='exercise' value='3'>2-4 times per week<br>
   <input type='radio' name='exercise' value='4'>More than 4 times per week<br>
   Blood Type: <input type = 'text' name = 'bloodtype' size = '20'><br>
   Surgeries:<br>
   <textarea name = 'surgeries' rows='4' cols='50'></textarea><br>
   Allergies:<br>
   <textarea name = 'allergies' rows='4' cols='50'></textarea><br>
   Current Medications:<br>
   <textarea name = 'currentmeds' rows='4' cols='50'></textarea><br>
   <input type = 'submit' value = 'Submit' name = 'submit'>
   </form>";


/* submit changes to database when user presses submit */
   if (isset($_POST['submit'])){
      $cancer_s= isset($_POST['cancer_s'])? $_POST['cancer_s'] : '0';
      $cancer_m= isset($_POST['cancer_m'])? $_POST['cancer_m'] : '0';
      $cancer_f= isset($_POST['cancer_f'])? $_POST['cancer_f'] : '0';
      $cancerdesc = sanitizeString($_POST['cancerdesc']);
      $heart_s= isset($_POST['heart_s'])? $_POST['heart_s'] : '0';
      $heart_m= isset($_POST['heart_m'])? $_POST['heart_m'] : '0';
      $heart_f= isset($_POST['heart_f'])? $_POST['heart_f'] : '0';
      $heartdesc = sanitizeString($_POST['heartdesc']);
      $chickenp = isset($_POST['chickenp'])? $_POST['chickenp'] : '0';
      $tetanus = isset($_POST['tetanus'])? $_POST['tetanus'] : '0';
      $mmr = isset($_POST['mmr'])? $_POST['mmr'] : '0';
      $alcohol = isset($_POST['alcohol'])? $_POST['alcohol'] : null;
      $tobacco = isset($_POST['tobacco'])? $_POST['tobacco'] : null;
      $exercise = isset($_POST['exercise'])? $_POST['exercise'] : null;
      $surgeries = sanitizeString($_POST['surgeries']);
      $allergies = sanitizeString($_POST['allergies']);
      $currentmeds = sanitizeString($_POST['currentmeds']);

$query = "insert into Medical_History
          values (?, current_timestamp,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'isssssssssssssssss', $cid, $cancer_s, 
   $cancer_m, $cancer_f, $cancerdesc, $heart_s, $heart_m, $heart_f, 
   $heartdesc, $chickenp, $tetanus, $mmr, $alcohol, $tobacco, $exercise,
   $surgeries, $allergies, $currentmeds);

if (!mysqli_stmt_execute($stmt)){
   echo "Error saving medical history information<br>".mysqli_stmt_error($stmt)."<br>";
}
else{
   //redirect
}
}
?>

</body>
</html>
