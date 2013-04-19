<html>
<body>

<?php
   session_start();
   include_once('db.php');
   include_once('functions.php');
   // session not logged in so redirect to login page
   if(!isset($_SESSION['user'])){
      header("Location: login.php");
   } 
   // user does not have permission to view page
   if(!($_SESSION['type'] == 'doctor' || $_SESSION['type'] == 'nurse')){
      echo "Permission denied!";
      die;
   } 
   //connect to database
   $conn = connect_db();
?>

   <form action="searchchart.php" method="POST">
   <input type = "text" placeholder="Enter patient last name" name = "searchString" size = "20">
   <input type = "submit" value = "Search" name = "search">
</form>
<br>

<?php
   //search patients based on substring match in first or last name
   if (isset($_POST['search'])){
      $searchString = sanitizeString($_POST['searchString']);
      //$searchString = $_POST['searchString'];
      $query = "select fname, minit, lname, dob, username
                from User 
                where lname like '%$searchString%'
                and type='patient'";
      $result = mysqli_query($conn, $query);

      //display results - plain text for now, will need to link to chart
      if (mysqli_num_rows($result) == 0){
         echo "No patients found.";
      }
      else{
         while ($row = mysqli_fetch_array($result, MYSQL_NUM)){
         $patient = $row[4];
             echo "<a href=chart.php?patient=$patient>$row[0] $row[1] $row[2], DOB: $row[3]</a><br>";
         }
      }
   }
?>
</body>
</html>
