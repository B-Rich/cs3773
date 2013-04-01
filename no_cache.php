<?php 
session_cache_limiter( 'nocache' );
header("Pragma: public");
header("Cache-Control: no-cache, must-revalidate, post-check=3600, pre-check=3600"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
?>