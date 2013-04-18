<?php
$sendto = $_POST["sendto"];
$subject = $_POST["subject"];
$message = $_POST["message"];
$from = "ehis.system@cs3733.com";
$headers = "From:" . $from;
mail($sendto,$subject,$message,$headers);
echo "Mail Sent.";
?>
