<html>
<body>

<?php
session_start();
require_once('db.php');
require_once('functions.php');

/* if user not logged in, redirect to home page */
if(!isset($_SESSION['user'])){
   header("Location: login.php");
}

$user = $_SESSION['user'];
$type = $_SESSION['type'];
$cid= $_GET['cid'];
$conn = connect_db();

if ($type != 'doctor' && $type != 'nurse'){
   header("Location: members.php");
}

/* Get and display treatments given */
$query = "select * 
          from Treatments_Given 
          where cid=$cid and date=current_date
          order by t_code";
$result = mysqli_query($conn, $query);
if (!$result){ 
   echo "Error: Unable to find patient information<br>";  
}
/* put treatments given into array indexed by t_code */
else{
   $ts_given = array();
      while($row = mysqli_fetch_array($result)){
         $t_code = $row[2];
         $ts_given[intval($t_code)] = true;
      }
}

/* get treatment names */
$query = "select *
          from Treatment_Cost
          order by tid";
$result = mysqli_query($conn, $query);
if (!$result){
   echo "Unable to find treatment information <br>";
}
else{
   $tnames = array();
   while ($row = mysqli_fetch_array($result)){
      $tnames[intval($row[0])] = $row[1];
   } 
}

/* print treatments given form */
echo "<form action='treatmentsgiven.php?cid=$cid' method='POST'>";
for ($i = 1; $i <= sizeof($tnames); $i++){
   if (!array_key_exists($i, $ts_given)){  //treatment not given
   echo "<input type='checkbox' name='$i' value='1'>".$tnames[$i]."<br>";
   }
   else{ //treatment already given
   echo $tnames[$i]."<br>";
   }
}
echo "<input type ='submit' value ='Submit' name ='submit'>";
echo "</form>";
/* submit changes to database */
   if (isset($_POST['submit'])){
      /* iterate through treatments, add those given to database */
      for ($i = 1; $i < sizeof($tnames); $i++){
         if (isset($_POST["$i"])){
            //insert i into t-given
            $query = "insert into Treatments_Given
                      values($cid, current_date, '$i')"; 
            $result = mysqli_query($conn, $query);
            if (!$result){
               echo "Error adding treatment to patient chart <br>";
            }
            else{
               header('Location: '.$_SERVER['REQUEST_URI']);
            }
         }
      }
   }
?>

</body>
</html>
