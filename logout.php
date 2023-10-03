<?php
session_start();
session_destroy();
$redirectLoc="Location: index.php";
header($redirectLoc);

?>