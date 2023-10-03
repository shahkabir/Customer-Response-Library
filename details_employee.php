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

	$sql	=	"SELECT * FROM cust_survey WHERE employee_name='".$_SESSION['userId']."'";
	
	$exec	=	mysql_query($sql) or die(mysql_error());
			
	
	$sqlc	=	"SELECT count(msisdn_txt) AS TOTC FROM cust_survey where employee_name='".$_SESSION['userId']."'";
	
	$execc	=	mysql_query($sqlc) or die(mysql_error());	
	$rowc	=	mysql_fetch_array($execc);
	
	$totc	=	$rowc['TOTC'];
	
	//echo $totc;

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
    <div class="row_container" style="width:100%">
      <?php 
	if($totc>0)
	{
	?>
      <h3>Details</h3>
      <table class="table table-sm">
        <thead>
        	<th scope="col">MSISDN</th>
        	<th scope="col">Connection Type</th>
        	<th scope="col"> Date</th>
        	<th scope="col">Time</th>
        	<th scope="col">Answer 1</th>
        	<th scope="col">Answer 2</th>
        	<th scope="col">Answer 2 Others</th>
			<th scope="col">VOCdetailsStoryline</th>
			<th scope="col">Story</th>
			<th scope="col">Call Back Required?</th>        	
        </thead>

        <tbody>
          <?php
          while($rows	=	mysql_fetch_array($exec))
	  {
		  ?>
          <tr>
            <td><?php echo $rows['msisdn_txt'];?></td>
            <td><?php echo $rows['con_type'];?></td>
            <td><?php echo $rows['survey_date'];?></td>
            <td><?php echo $rows['survey_time'];?></td>
            <td><?php echo $rows['quest_one_ans'];?></td>
            <td><?php echo $rows['quest_two_ans'];?></td>
            <td><?php echo $rows['quest_two_others'];?></td>
            <td><?php echo $rows['VOCdetailsStoryline'];?></td>
            <td><?php echo $rows['txtstory'];?></td>
            <td><?php echo $rows['callback'];?></td>
          </tr>
          <?php
	  }?>
        </tbody>
      </table>
      <?php
	}
	else
	{
		echo '<p> No Records Found!!</p>';
	}
	?>
    </div>
  </form>
</div>
<div class="foot_div">
  <p><strong>&copy; Business Intelligence Analysis &amp; Reporting || CCD </strong></p>
</div>
<?php //include 'footer.php'; ?>
<!-- /container --> 

<!-- Le javascript ================================================== --> 
<!-- Placed at the end of the document so the pages load faster -->
<?php include 'js_file.php'; ?>
</body>
</html>