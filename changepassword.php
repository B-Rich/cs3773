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


<body>

<div class="mainbody">

<?php
$user = $_SESSION['user'];

echo $user;
?>

<?php

echo <<<_END

<form method = "post" action="passwordChanged.php">
Current Password: <input type="password" name="currentpassword" autofocus required><br/>
New Password: <input type="password" name="newpassword" required><br/>
Verify New Password: <input type="password" name="newpassword2" required> <br/><br/>
<input type="submit" class="button" value="Change">
</form>
_END;
?>


</div>



</body>

</html>
