<?php
require_once('db.php');
function display_appointments($doctor){
   $conn = connect_db();

   //get current date for query
   $date_format = "Y-m-d";
   $curr_date = date($date_format);

   //get time, last name, first name from database for all appointments
   //for the current day, list in order of time (ascending) 
   $query = "SELECT time, fname, lname
             FROM User, Appointment
             WHERE username=patient AND date='$curr_date' AND doctor='$doctor'
             ORDER BY time";
   
   //execute query and get result
   $result = mysqli_query($conn, $query);
   $n = $result->num_rows;
  
   //print table with time in first colum, lastname and first name in second column
   echo "<table>";
   for ($i = 1; $i <= $n; $i++){
      $row = mysqli_fetch_assoc($result);
      $time = strtotime($row['time']);
      $time = date("g:i A", $time);
      echo "<tr><td>" .$time. "</td><td>".$row['lname'].", ". 
            $row['fname']."</td></tr>"; 
   }
   echo "</table>";

   //close connection
   mysqli_close($conn);
   }
?>
