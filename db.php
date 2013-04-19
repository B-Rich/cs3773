<?php
$host="localhost";
$username='root';  
$password='sdhcs29i';  
$db='EHIS'; 


// Connects to MySql database on the "localhost" machine
// it checks for a connection error else returns the connection
function connect_db(){
   $conn = mysqli_connect( $GLOBALS['host'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['db']);
   if ( mysqli_connect_errno($conn) ) {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
   }
   return $conn;
}


// function checks if user exists
function userExists( $userName ){

   $exists = false;
   // open connection to the DB
   $conn = connect_db();
   $query = "SELECT username FROM User WHERE username = '$userName'";
   
   // find if $username is in the database
   $result = mysqli_query($conn, $query);
   if (mysqli_num_rows($result) == 1)
   {
      $exists = 1;
   }
       
   // execute the query and return the results
   mysqli_query($conn, $query);  
   //close connection to the database
   mysqli_close($conn);  
   
   return $exists;     
}
// function takes a connection and a string that represents a sql command
// this function is for general use, returns the executed sql command
function execSql($conn, $sql){
//   $stid = oci_parse($conn, $sql);
//   oci_execute($stid);
//
   return $stid;
}

?>

