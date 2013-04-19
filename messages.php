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

$toField = "";
$fromField = "";
$msgField = "";

if(isset($_POST['sent'])){

   $inputError = false;
   $msgField = $_POST['text'];
   
   if($_POST['to'] == "" ){
      $errorToField = "* Field was left empty";   
      $inputError = true;
   }
   else{
      // if input, validate input
      if( !userExists($_POST['to']) ){
         $errorToField = "* User ". $_POST['to'] ." does not exist";
         $inputError = true;
      } else {
         // if user is valid display again
         $toField = $_POST['to'];
      }
   }
   if($_POST['from'] == ""){ 
      $errorFromField = "* field was left empty"; 
      $inputError = true;
   }
   else{ 
      // if input, validate input
      if( !userExists($_POST['from']) ){
         $errorFromField = "* User " . $_POST['from'] ." does not exist";
         $inputError = true;
      } else {
         // if user is valid display again
         $fromField = $_POST['from'];
      }
   }
   
   if(!$inputError) {
      // connect to the DB
      $conn = connect_db(); 

      
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
      echo "<p>message sent</p>";
  } //end send correct msg
}

echo <<<_END
Type here to leave a message:<br />
<table border = '0' >
<tr>
  <td> <form method='post' action='messages.php'> </td>
</tr>
<tr>
  <td>
    <table border = '0'>
    <tr>
     <td align='right'>To: </td>
     <td> <input type='text' name='to' value='$toField'  /><br /> </td>
     <td style='color:red'>
_END;
//if there is an error display this
echo $errorToField;
echo <<<_END
     </td>
    </tr>
    <tr>
     <td align='right'> From: </td>
     <td><input type='text' name='from' value='$fromField' /><br /></td>
     <td style='color:red'> 
_END;
//if there is an error display this
echo $errorFromField;
echo <<<_END
     </td>
    </tr>
   </table >
  </td>
</tr>
<tr>
  <td><textarea style='color:black; resize:none; background:white' name='text' cols='40' rows='5'>$msgField</textarea><br /></td>
</tr>
<tr colspan='2'>
  <td><input type='submit' name='sent' value='Post Message' /></td>
</tr>
</form>
</table>
_END;
?>

</body>
<html>
<?php
//} //end else
?>
