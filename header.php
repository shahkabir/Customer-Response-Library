<?php 
session_start(); 
//print_r($_SESSION);

if(!isset($_SESSION['userId'])){
  $userId=$_SERVER['REMOTE_USER'];
  $userId=explode('\\',$userId);
  $userId=$userId[1];
  $_SESSION['userId']=$userId;


require_once 'DBConnection.php';

$dbc = new DBConnection();

$arr = $dbc->fetchAll();

//print_r($arr);
//exit;

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Customer Response Library</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<style type="text/css">
body {
	padding-bottom: 40px;
}

a{
	font-weight: bold;
}

.subscript
{
	text-align: right;
}
</style>
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="assets/css/stylesheet.css" rel="stylesheet">

<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="assets/js/html5shiv.js"></script>
    <![endif]-->

<!-- Fav and touch icons -->
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
<!--<link rel="shortcut icon" href="assets/ico/favicon.png">-->

<link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen">
<script type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>

</head>


<body>
<?php 
$br = $_SERVER['HTTP_USER_AGENT'];
$ie = strpos($br,'.NET');
if($ie>0)
{
?>
	<div style="border:1px solid #f5d000; text-align:center; font: bold 20px Arial, serif;"><p>Please use Firefox or Chrome browser for best performance. Thank you.</p>
    <?php $full_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];?>
	 <p><a target="_blank" href="<?php echo $full_url; ?>"><?php echo $full_url;?></a></p></div>
<?php
exit;
}
?>
<?php

  /*$sql  = "SELECT COUNT(*) AS TOTCNT FROM cust_survey WHERE employee_name='".$_SESSION['userId']."'";
  $exec = mysql_query($sql) or die(mysql_error());
  $rows = mysql_fetch_array($exec);
  $ton  = $rows['TOTCNT'];
  */
  
  /*$sqlT = "SELECT employee_name,COUNT(msisdn_txt) AS TOTCNTM FROM cust_survey
        GROUP BY employee_name
        ORDER BY TOTCNTM DESC LIMIT 1";
        
  $execT  = mysql_query($sqlT) or die(mysql_error());     
  $rost = mysql_fetch_array($execT);*/

?>
<div class="container">
  <div class="head_div">
    <div class="head_logo"></div>
    <div class="head_title">
      <h3 class="muted" style="font-size: 30px; color:#fff;">CUSTOMER RESPONSE LIBRARY</h3>
     <!--  <h6 class="subscript">Dear <?php echo $_SESSION['userId'];?>, so far you talked with <?php echo $ton;?> customers. Keep it up..</h6> -->
    </div>
  </div>
</div>
<?php include 'nav.php'; ?>