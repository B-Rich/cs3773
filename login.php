<?php 
session_start();
//test
?>
<!DOCTYPE html>
    <html><head>
	  <script src='OSC.js'></script>
	  <title>Software Engineering CS 3773 Project: Scrum Team!</title>
	  <style>
	  	.head{
			font-family:Calibri;
		    font-size:42px;
			width:100%;
			height:60px;
			text-align:right;
			/*background:#4660a1;*/
			color:#fff;
		}
		
		body{
			background:#4660a1;
			font-family:calibri;
		}
		
		div.contents{
			position: relative; margin-top: 1em;
			padding-left:300px;
			padding-right:300px;
			padding-top:50px;
			display:table;
			background:#fff;
			height:550px;
			width:100%;
		}
		
		div.main, div.logo{
			float:left;
			padding-right:90px;
			padding-left:70px;
			height:350px;
		}
		
		div.main{
			padding-top:100px;
			color:#a3a3a3;
			font-size:25px;
		}
		
		h2.conn{
			font-family:calibri;
			color:#4660a1;
		}
		
		h2.credit{
			color:#fff
		}
		div.foot{
			width:900px;
			height:100px;
			font-family:calibri;
			font-size:10px;
			background:#4660a1;
			padding-left:100px;
			padding-right:100px;
		}
		
		h3{
			font-size:35px;
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
			-webkit-border-radius:5px
		}
	  </style>
	  </head>
	  <div class="head">
	  
	  </div>
	  <body>
	  <div class = "contents">	  
	  	<div class= "logo">
		  <img src="EHIS.png" id="pic"><br>
		  <h2 class="conn">Connecting People with Health Care</h2>
		</div>
<?
$userstr = ' (Guest)';

if (isset($_SESSION['user']))
{
    $user     = $_SESSION['user'];
    $loggedin = TRUE;
    $userstr  = " ($user)";
}

?>


<div class='main'>
	<h3>Login</h3>
<?
$error = $user = $pass = "";

if (isset($_POST['user']))
{
    $user = sanitizeString($_POST['user']);
    $pass = sanitizeString($_POST['pass']);
    
    if ($user == "" || $pass == "")
    {
        $error = "Not all fields were entered<br />";
    }
    else
    {
        $query = "SELECT user,pass FROM members
            WHERE user='$user' AND pass='$pass'";

        if (mysql_num_rows(queryMysql($query)) == 0)
        {
            $error = "<span class='error'>Username/Password
                      invalid</span><br /><br />";
        }
        else
        {
            $_SESSION['user'] = $user;
            $_SESSION['pass'] = $pass;
            die("You are now logged in. Please <a href='members.php?view=$user'>" .
                "click here</a> to continue.<br /><br />");
        }
    }
}

echo <<<_END
<form method='post' action='login.php'>$error
<span class='fieldname'>Username</span><br><input type='text'
    maxlength='16' name='user' value='$user' /><br />
<span class='fieldname'>Password</span><br><input type='password'
    maxlength='16' name='pass' value='$pass' />
_END;
?>

<br />
<span class='fieldname'>&nbsp;</span><br>
<input type='submit' class="button" value='Submit' />
</form><br />
	</div>
</div>
</body>
<br>
<center>
<div class="foot">
	<center><h2 class="credit">A Scrum Team Production</h2><span>Software Engineering Project: Version 1.0</span></center>
</div>	
</center>
</html>
