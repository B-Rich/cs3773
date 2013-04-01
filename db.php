<?php
$host="localhost";
$username='root';  
$password='jg1093xavi';  
$db='EHIS_db'; 


// Connects to MySql database on the "localhost" machine
// it checks for a connection error else returns the connection
function connect_db(){
   $conn = mysqli_connect( $GLOBALS['host'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['db']);
   if ( mysqli_connect_errno($conn) ) {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
   }
   return $conn;
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

