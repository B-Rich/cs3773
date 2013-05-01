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
	background-color: white	;
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
<div class="mainbody">

<?php

echo <<<_END
<form method="post" action="emailpassword.php">
Enter the e-mail address associated with your EHIS account,
 then click Send password. You will be sent a temporary password that you can use to login.
 You will be prompted to create a new password after you login. <br/><br/>
 First Name: <input type="text" name="firstname" autofocus required><br/>
 Last Name: <input type="text" name="lastname" required><br/>
 Date of Birth: <input type="date" name="bday" required><br/>
 SSN: <input type="text" name="ssn" required><br/><br/>
 <input type="submit" class="button"  value="Send"><br/>
 </form>
</div>
_END;
?>

</body>
<html>

