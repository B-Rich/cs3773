
<?php
require_once('db.php');

echo "<!DOCTYPE HMTL>
<head>

<style>
div.mainbody {
        font-family: Arial, Helvetica, sans-serif ;
        font-size: 1.1em ;
        width: 730px ;
        margin-left: auto ;
        margin-right: auto ;
        padding: 10px ;
        background-color: white ;
        color: black ;
}
.button{
        -webkit-box-shadow:rgba(0,0,0,0.0.1) 0 1px 0 0;
        -moz-box-shadow:rgba(0,0,0,0.0.1) 0 1px 0 0;
        box-shadow:rgba(0,0,0,0.0.1) 0 1px 0 0;
        background-color:#a3a3a3;
        border:1px solid #a3a3a3;
        font-family:calibri;
        font-size:20px;
        font-weight:700;
        padding:2px 6px;
        height:38px;
        width:90px;
        color:#fff;
        border-radius:5px;
        -moz-border-radius:5px;
        -webkit-border-radius:5px;
}
</style>
</head>
<body>
	<div class =mainbody>";



function generatePassword($length = 5) {
    $upperCase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $number = '0123456789';
    $lowerCase = 'abcdefghijklmnopqrstuvwxyz';
    $specialCase = '~!@#$%^&*()';

    $lowerCount = mb_strlen($lowerCase);
    $numberCount = mb_strlen($number);
    $upperCount = mb_strlen($upperCase);
    $specialCount = mb_strlen($specialCase);

    for ($i = 0, $pass = ''; $i < $length; $i++) {
        
	$randNum = rand(0,3);
	switch($randNum){
		case "0":
			$index = rand(0,$lowerCount - 1);
		        $pass .= mb_substr($lowerCase, $index, 1);
			break;
		case 1:
			$index = rand(0,$numberCount-1);
                        $pass .= mb_substr($number, $index, 1);
                        break;
		case 2;
			$index = rand(0, $upperCount-1);
                        $pass .= mb_substr($upperCase, $index, 1);
                        break;
		case 3:
			$index = rand(0,$specialCount-1);
                        $pass .= mb_substr($specialCase, $index, 1);
                        break;
	}
    }
	$index = rand(0,$upperCount - 1);
	$pass .= mb_substr($upperCase, $index, 1);
	
	$index = rand(0,$numberCount - 1);
	$pass .= mb_substr($number,$index, 1);
	
	$index = rand(0,$specialCount - 1);
	$pass .= mb_substr($specialCase,$index, 1);
    return $pass;
}


$tempPassword = generatePassword(); 
$conn = connect_db();
$fname = $_POST['firstname'];
$lname = $_POST['lastname'];
$ssn = $_POST['ssn'];
$dob = $_POST['bday'];


$email = mysqli_query($conn,"SELECT email FROM `Personal_Info` WHERE fname='$fname' AND lname = '$lname' AND ssn = '$ssn' AND dob = '$dob'");
$usernamequery = mysqli_query($conn,"SELECT username FROM `Member` WHERE cid = (SELECT cid FROM `Personal_Info` WHERE fname='$fname' AND lname = '$lname' AND ssn = '$ssn' AND dob = '$dob')");
$usernameRow = mysqli_fetch_assoc($usernamequery);
$alreadySent = mysqli_query($conn,"SELECT temppassword FROM `Member` WHERE username = '$usernameRow[username]'");
$row2 = mysqli_fetch_assoc($alreadySent);



if(mysqli_num_rows($usernamequery) != 0){
	$row = mysqli_fetch_assoc($email);
	if($row2['temppassword'] == "Yes"){
		echo "We have already sent you a temporary password.  Please check your spam inbox.<br/><br/>
		     Click here to return to the login page: ";
		echo "<a href=http://ehisproject.dyndns.org/cs3773/login.php>EHIS project</a>";
		
	}
	else{
	mysqli_query($conn,"UPDATE Member SET `temppassword` = 'Yes' WHERE username = '$usernameRow[username]'");
    mysqli_query($conn,"UPDATE Member SET `password` = '$tempPassword' WHERE username = '$usernameRow[username]'");	
	

		$sendto = $row['email'];
		$subject = "Temporary Password";
		$username = $usernameRow['username'];
		$message = "Hi $fname,

		Your username is: $username

		Please use this password to sign into your account: $tempPassword

		You will have the ability to change your password once you have logged in by

		clicking on your account settings (has not been implemented yet).

		Please login here: http://ehisproject.dyndns.org/cs3773/login.php

		Sincerely,

		EHIS project team";
		$from = "ehis.system";
		$headers = "From: ehis.system";
		mail($sendto,$subject,$message,$headers);
		echo "Check Your Email. (don't forget to check your spam folder).
		 Make sure to white list ehis.system@gmail.com in your email filters for future messages from EHIS.
		 Click here to return to the login page: ";
		echo "<a href=http://ehisproject.dyndns.org/cs3773/login.php> EHIS project </a>";
	}
}
else{
	echo "Sorry, but there is no record of you in our system.  Please create an account first.";
}
echo "</div>";
mysqli_close($conn);
?>
