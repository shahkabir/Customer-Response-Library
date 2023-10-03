<?php 
session_start(); 
//print_r($_SESSION);

if(!isset($_SESSION['userId'])){
	$userId=$_SERVER['REMOTE_USER'];
	$userId=explode('\\',$userId);
	$userId=$userId[1];
	$_SESSION['userId']=$userId;
}
?>
<?php 
include 'header.php'; 
include 'DBConnection.php';
?>
<?php

	$sql	=	"SELECT COUNT(*) AS TOTCNT FROM cust_survey WHERE employee_name='".$_SESSION['userId']."'";
	
	$exec	=	mysql_query($sql) or die(mysql_error());
	
	$rows	=	mysql_fetch_array($exec);
	$ton	=	$rows['TOTCNT'];
	

	
	$sqlT	=	"SELECT employee_name,COUNT(msisdn_txt) AS TOTCNTM FROM cust_survey
				GROUP BY employee_name
				ORDER BY TOTCNTM DESC LIMIT 1";
				
	$execT	=	mysql_query($sqlT) or die(mysql_error());			
	$rost	=	mysql_fetch_array($execT);

?>
<style type="text/css">
body {
	padding-top: 40px;
	padding-bottom: 40px;
}
.status_message {
	font-size: 11px;
	font-weight: bold;
	padding: 8px 12px;
	margin: 0 0 5px 0;
}
.status_message.error {
	/*background: url(../images/common/status_message/error.png) no-repeat center center transparent;*/
	background-color: #FFF9D7;
	color: #FF0000;
	border: 1px solid #E2C822;
}
.status_message.warning {
	/*background: url(../images/common/status_message/warning.png) no-repeat center center transparent;*/
	background-color: #eef8db;
	color: #003366;
	border: 1px solid #a0ae86;
}
.status_message.success {
	/*background: url(../images/common/status_message/success.png) no-repeat center center transparent;*/
	background-color: #E2F9E3;
	color: #000000;
	border: 1px solid #99CC99;
}
.status_message.notification {
	/*background: url(../images/common/status_message/notification.png) no-repeat center center transparent;*/
	background-color: #eef8db;
	color: #003366;
	border: 1px solid #a0ae86;
}
.status_message.information {
	/*background: url(../images/common/status_message/information.png) no-repeat center center transparent;*/
	background-color: #E2F9E3;
	color: #000000;
	border: 1px solid #99CC99;
}
.row_container {
	height: auto;
	background-color: #fffceb;
	padding-left: 10px;
	padding-bottom: 10px;
	border: 1px solid #f5d000;
	float: left;
	width: 75%;
}
.row_left {
	font-weight: bold;
	font-size: 14px;
	width: 50%;
	height: auto;
	padding: 10px 10px 10px 5px;
	float: left;
}
.row_right {
	font-weight: bold;
	font-size: 14px;
	width: 40%;
	height: auto;
	padding: 5px 10px 10px 5px;
	float: left;
}
.row {
	float: left;
	width: 100%;
	height: auto;
	padding: 0px;
	margin: 0px 0px 0px 0px;
	font-size: 16px;
}
</style>

<div class="container" style="padding-top:20px;">
  <?php if($_SESSION['status_message']) { ?>
  <div class="status_message_contaier">
    <div class="status_message_contaier_inner">
      <?php if($_SESSION['status_message']['success']) { ?>
      <div class="status_message success" style="padding: 4px; text-align: center;"><?php echo $_SESSION['status_message']['success']; ?></div>
      <?php } ?>
      <?php unset($_SESSION['status_message']); ?>
    </div>
  </div>
  <?php } ?>
  <form name="customersurvey" method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
    <div style="width:100%; height:auto;float:left; padding:0px 0px 10px 0px;">
    <div class="row_container">
      <!--<div class="row_left">Employee Name: &nbsp; <?php echo $_SESSION['userId'];?></div>-->
      <div class="row_right">You have interacted with <?php echo $ton;?> customers.<br/><br/>For more details, click <a href="details_employee.php">here</a>.</div>
    </div>
    <!--<div style="width:20%; height:auto; background-color:#eef8db; color:#003366; border:1px solid #a0ae86; float:right; padding:5px 5px 0px 10px;">
      <p><b>Top Surveyer</b></p>
      <p><b>Employee Email Alias:</b> <?php echo $rost['employee_name'];?> <br>
        <b>Total Count:</b> <?php echo $rost['TOTCNTM'];?></p>
    </div>-->
  </form>
</div>
<div class="foot_div">
  <p><strong>&copy; Digital Services & Solutions | DSS-AppDev</strong></p>
</div>
<?php //include 'footer.php'; ?>
<!-- /container --> 

<!-- Le javascript ================================================== --> 
<!-- Placed at the end of the document so the pages load faster -->
<?php include 'js_file.php'; ?>
</body>
</html>