<?php
session_start();
require_once('db.php');
// the no cache might be needed in case we run into
// problems with displaying cached pages
// include('no_cache.php'); 
//if(!isset($_SESSION['user'])){
   // session not logged in so redirect to login page
   // header("Status: 200"); //might be needed
   //header("Location: login.php");
//}else{
   //session logged 
   //display the contents of members.php
   //notice that the closing brace is not until the bottom of the page		
?>

<html>

<head> 
<title> Messages</title>
<script src="linknav.js"></script>
</head>

</body>

<?php

if(isset($_POST['sent'])){

   // connect to the DB
   $conn = connect_db(); 

   echo "<p>message sent</p>";
   $query = "INSERT INTO `EHIS`.`Message` (
             `mid` ,
             `to_username` ,
             `from_username` ,
             `time` ,
             `subject` ,
             `text` ,
             `read`
            )
            VALUES ( NULL, '" .$_POST['to'] ."', '" . $_POST['from'] ."',
                NULL, 'subject', '" .$_POST['text'] ."', '0'
            ) ";
   // execute the query and return the results
   mysqli_query($conn, $query);  
   
   //close connection to the database
   mysqli_close($conn);          
   //echo $query;
   //echo $_POST['to'] . "<br\>";
   //echo $_POST['from'] . "<br\>";
}

echo <<<_END
<form method='post' action='messages.php?view=$view'>
Type here to leave a message:<br />
To<input type='text' name='to' value=''  /><br />
From<input type='text' name='from' value='' /><br />
<textarea name='text' cols='40' rows='3'></textarea><br />

<input type='submit' name='sent' value='Post Message' />
</form><br />
_END;
?>



</body>
<html>
<?php
//} //end else
?>
