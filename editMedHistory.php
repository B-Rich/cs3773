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
  
echo "Patient: $fname $minit $lname:";

/* Get current medical history form */
$query = "select *
          from Medical_History
          where cid=$cid
          order by time desc";
$result = mysqli_query($conn, $query);
if (!$result){
   echo "Error: Unable to access medical history<br>";
}

else{
/* get current medical history information */

   $cancer_s = null;
   $cancer_m = null;
   $cancer_f = null;
   $cancerdesc  = null;
   $heart_s = null;
   $heart_m = null;
   $heart_f = null;
   $heartdesc  = null;
   $chickenp  = null;
   $tetanus  = null;
   $mmr  = null;
   $alcohol  = null;
   $tobacco  = null;
   $exercise  = null;
   $surgeries  = null;
   $allergies  = null;
   $currentmeds = null;

   if ($row = mysqli_fetch_array($result)){
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
      $currentmeds  = $row[19];
   }
}


/* print medical history form */

  // Temperature: <input type = 'text' name = 'temp' value=\"$temp\" size = '20'><br>
echo "<form action='editMedHistory.php?cid=$cid' method='POST'>
   Enter Medical History information below:<br><br>
   Have you or a family member had cancer?
   <input type='checkbox' name ='cancer_s' value ='1'"; 
   if ($cancer_s == '1'){
      echo "checked";
   }
   echo ">Self   
   <input type='checkbox' name ='cancer_m' value ='1'";
   if ($cancer_m == '1'){
      echo "checked";
   }
   echo">Mother's Side
   <input type='checkbox' name ='cancer_f' value ='1'";
   if ($cancer_f == '1'){
      echo "checked";
   }
   echo">Father's Side<br>";
   echo "If yes, please describe:<br>
   <textarea name = 'cancerdesc' rows='4' cols='50'>$cancerdesc</textarea><br>";
   echo "Have you or a family member had heart disease?
   <input type='checkbox' name ='heart_s' value ='1'";
   if ($heart_s == '1'){
      echo "checked";
   }
   echo ">Self   
   <input type='checkbox' name ='heart_m' value ='1'";
    if ($heart_m == '1'){
      echo "checked";
   }
   echo ">Mother's Side
   <input type='checkbox' name ='heart_f' value ='1'";
   if ($heart_m == '1'){
      echo "checked";
   }
   echo ">Father's Side<br>";
   echo "If yes, please describe:<br>
   <textarea name = 'heartdesc' rows='4' cols='50'>$heartdesc</textarea><br>";
   echo "Have you had the following immunizations within the past 5 years:
   <input type='checkbox' name ='chickenp' value ='1'";
   if ($chickenp == '1'){
      echo "checked";
   }
   echo ">Chickenpox<br>
   <input type='checkbox' name ='tetanus' value ='1'";
   if ($tetanus == '1'){
      echo "checked";
   }
   echo ">Tetanus<br>
   <input type='checkbox' name ='mmr' value ='1'";
   if ($mmr == '1'){
      echo "checked";
   }
   echo ">MMR<br>";
   echo "How often do you consume alcoholic beverages?<br>";
   echo "<input type='radio' name='alcohol' value='n'";
   if ($alcohol == 'n'){
      echo "checked";
   }
   echo ">Never<br>
   <input type='radio' name='alcohol' value='s'";
   if ($alcohol == 's'){
      echo "checked";
   }
   echo ">Sometimes<br>
   <input type='radio' name='alcohol' value='o'";
   if ($alcohol == 'o'){
      echo "checked";
   }
   echo ">Often<br>";
   echo "How often do you smoke or use tobacco?<br>
   <input type='radio' name='tobacco' value='n'";
   if ($tobacco == 'n'){
      echo "checked";
   }
   echo ">Never<br>
   <input type='radio' name='tobacco' value='s'";
   if ($tobacco == 's'){
      echo "checked";
   }
   echo ">Sometimes<br>
   <input type='radio' name='tobacco' value='o'";
   if ($tobacco == 'o'){
      echo "checked";
   }
   echo ">Often<br>";
   echo "How often do you exercise?<br>
   <input type='radio' name='exercise' value='1'";
   if ($exercise == '1'){
      echo "checked";
   }
   echo ">Never<br>
   <input type='radio' name='exercise' value='2'";
   if ($exercise == '2'){
      echo "checked";
   }
   echo ">Less than twice per week<br>
   <input type='radio' name='exercise' value='3'";
   if ($exercise == '3'){
      echo "checked";
   }
   echo">2-4 times per week<br>
   <input type='radio' name='exercise' value='4'";
   if ($exercise == '4'){
      echo "checked";
   }
   echo ">More than 4 times per week<br>";
   echo "Blood Type: <input type = 'text' name = 'bloodtype' size = '20' value = $bloodtype><br>";
   echo "Surgeries:<br>
   <textarea name = 'surgeries' rows='4' cols='50'>$surgeries</textarea><br>";
   echo "Allergies:<br>
   <textarea name = 'allergies' rows='4' cols='50'>$allergies</textarea><br>";
   echo "Current Medications:<br>
   <textarea name = 'currentmeds' rows='4' cols='50'>$currentmeds</textarea><br>";
   echo "<input type = 'submit' value = 'Submit' name = 'submit'>
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
      $bloodtype = isset($_POST['bloodtype']) ? $_POST['bloodtype'] : null;
      $exercise = isset($_POST['exercise'])? $_POST['exercise'] : null;
      $surgeries = sanitizeString($_POST['surgeries']);
      $allergies = sanitizeString($_POST['allergies']);
      $currentmeds = sanitizeString($_POST['currentmeds']);

$query = "insert into Medical_History
          values (?, current_timestamp,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'issssssssssssssssss', $cid, $cancer_s, 
   $cancer_m, $cancer_f, $cancerdesc, $heart_s, $heart_m, $heart_f, 
   $heartdesc, $chickenp, $tetanus, $mmr, $alcohol, $tobacco, $exercise,
   $bloodtype, $surgeries, $allergies, $currentmeds);

if (!mysqli_stmt_execute($stmt)){
   echo "Error saving medical history information<br>".mysqli_stmt_error($stmt)."<br>";
}
else{
   //redirect
      header('Location: '.$_SERVER['REQUEST_URI']);
}
}
?>

</body>
</html>
