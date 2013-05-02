<?php
session_start();
require_once('db.php');

if(!isset($_SESSION['user'])){
	header("Location: login.php");
}
?>

<!DOCTYPE HTML>
<html>
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
<div class="mainbody">






<?php

function validatePassword($password){
	$upper = 0;
	$number = 0;
	$special = 0;
	if(strlen($password) < 8){
		return 0;
	}
	$passwordArray = str_split($password);
	
	foreach($passwordArray as $char){
		if(isUpper($char))
			$upper = 1;
		if(isNumber($char))
			$number = 1;
		if(isSpecial($char))
			$special = 1;
	}
	if($upper == 1 && $number == 1 && $special == 1)
		return 1;
	else
		return 0;
}
function isNumber($char){
	$number = '0123456789';
	for($i = 0; $i < 10; $i++){
		if($char == mb_substr($number, $i, 1)){
			return 1;
		}
	}
return 0;
}
function isUpper($char){
	$upperCase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	for($i = 0; $i < 26 ; $i++){
                if($char == mb_substr($upperCase, $i, 1)){
                        return 1;
                }
        }
return 0;
}
function isSpecial($char){
	$specialCase = '~!@#$%^&*()';
        for($i = 0; $i < 11 ; $i++){
                if($char == mb_substr($specialCase, $i, 1)){
                        return 1;
                }
        }
return 0;
}

$conn = connect_db();
$user = $_SESSION['user'];
$oldpassword = $_POST['currentpassword'];
$newpassword = $_POST['newpassword'];
$newpassword2 = $_POST['newpassword2'];
$result = mysqli_query($conn, "SELECT `password` FROM `Member` WHERE username='$user'");
$row = mysqli_fetch_assoc($result);
$currPass = $row['password'];


if($newpassword == $newpassword2){
	if($currPass == $oldpassword){
	  if(validatePassword($newpassword) == 0){
		echo "The password you entered does not meet the minimum password requirements.<br/><br/>";
		echo "The password must be at least 8 characters and have a mimimum of 1 uppercase letter, 1 number and 1 special character.";
	  }
	  else{
		mysqli_query($conn,"UPDATE `Member` SET password='$newpassword' WHERE username='$user'");
		mysqli_query($conn,"UPDATE `Member` SET temppassword='No' WHERE username='$user'");	
		echo "<span style=color:green>Password changed successfully.</span> <br/><br/>";
		echo "<a href=http://ehisproject.dyndns.org/cs3773/members.php><< back to main menu </a>";
	  }
	}
	else{
		echo "<span style=color:red>Incorrect current password.  Try again.</style> <br/><br/>";
		echo "<a href=http://ehisproject.dyndns.org/cs3773/changepassword.php><< go back </a>";}
}
else{
	echo "New Passwords do not match.";
        echo "<a href=changepassword.php><< go back</a>";
}
?>






