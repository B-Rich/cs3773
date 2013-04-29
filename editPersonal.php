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
  
echo "Enter New Personal Information:";

/* Get current personal info */

$query = "select *
          from Personal_Info p
          where p.cid = $cid
          order by time desc";

$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) == 0){
   $error = "<span class='error'>Unable to find 
   patient information</span><br><br>";
}

else{
   $row = mysqli_fetch_array($result);
   $fname = formatFromDB($row[2]);
   $minit = formatFromDB($row[3]);
   $lname = formatFromDB($row[4]);
   $gender = formatFromDB($row[7]);
   $dob = formatFromDB($row[6]);
   $ssn = formatFromDB($row[5]);
   $email = formatFromDB($row[8]);
   $phone = formatFromDB($row[9]);
   $addr = formatFromDB($row[10]);
   $eContact = formatFromDB($row[11]);
   $eContactNum = formatFromDB($row[12]);
   $insurance = formatFromDB($row[13]);
   $groupno = formatFromDB($row[14]);
   $policyno = formatFromDB($row[15]);


/* print personal information form */

echo "<form action='editPersonal.php?cid=$cid' method='POST'>
   First name: <input type = 'text' name = 'fname' value=\"$fname\" size = '20'>
   Middle initial: <input type = 'text' name = 'minit' value=\"$minit\" size = '20'>
   Last name: <input type = 'text' name = 'lname' value=\"$lname\" size = '20'><br>

   Gender: <input type = 'text' name = 'gender' value=\"$gender\" size = '20'><br>
   Date of birth: <input type = 'text' name = 'dob' value=\"$dob\" size = '20'><br>
   Social Security number: <input type = 'text' name = 'ssn' value=\"$ssn\"size = '20'><br>
   Email address: <input type = 'text' name = 'email' value=\"$email\" size = '20'>
   Phone number: <input type = 'text' name = 'phone' value=\"$phone\" size = '20'><br>
   Address: <input type = 'text' name = 'addr' value=\"$addr\" size = '20'><br>
   Emergency contact: <input type = 'text' name = 'eContact' value=\"$eContact\" size = '20'><br>
   Emergency contact phone number: <input type = 'text' name = 'eContactNum' value=\"$eContactNum\" size = '20'><br>
   Insurance company: <input type = 'text' name = 'insurance' value=\"$insurance\" size = '20'><br>
   Insurance group number: <input type = 'text' name = 'groupno' value=\"$groupno\" size = '20'><br>
   Insurance policy number: <input type = 'text' name = 'policyno' value=\"$policyno\" size = '20'><br>
   <input type = 'submit' value = 'Submit' name = 'submit'>
   </form>";

/* submit changes to database when user presses submit */
   if (isset($_POST['submit'])){
      $fname = $_POST['fname'];
      $minit = $_POST['minit'];
      $lname = $_POST['lname'];
      $gender = $_POST['gender'];
      $dob = $_POST['dob'];
      $ssn = $_POST['ssn'];
      $email = $_POST['email'];
      $phone = $_POST['phone'];
      $addr = $_POST['addr'];
      $eContact = $_POST['eContact'];
      $eContactNum = $_POST['eContactNum'];
      $insurance = $_POST['insurance'];
      $groupno = $_POST['groupno'];
      $policyno = $_POST['policyno'];
 
      //$query = "insert into Personal_Info(time, fname, minit, lname, ssn, dob, gender, email, phone, address, emergency_contact_name, emergency_contact_phone, insurance_company, group_no, policy_no)
      $query = "insert into Personal_Info
               values (?,current_timestamp,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"; 
      $stmt = mysqli_prepare($conn, $query);
      mysqli_stmt_bind_param($stmt, 'isssisssissisii', $cid, $fname, $minit, 
        $lname, $ssn, $dob, $gender, $email, $phone, $addr, $eContact,
        $eContactNum, $insurance, $groupno, $policyno);
  
      /* execute prepared statement */
      if(!mysqli_stmt_execute($stmt)){
      //if (mysqli_stmt_affected_rows($stmt) == 0){
         echo "Error saving personal information<br>".mysqli_stmt_error($stmt)."<br>";
      }
      else{
         header('Location: '.$_SERVER['REQUEST_URI']);
      }
   }
}
?>

</body>
</html>
