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

.tg  {border-collapse:collapse;border-spacing:0;border-color:#aaa;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#aaa;color:#333;background-color:#fff;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#aaa;color:#fff;background-color:#f38630;}
.tg .tg-j2zy{background-color:#FCFBE3;vertical-align:top}
.tg .tg-baqh{text-align:center;vertical-align:top}
.tg .tg-zczf{background-color:#FCFBE3;text-align:right;vertical-align:top}
.tg .tg-lqy6{text-align:right;vertical-align:top}
.tg .tg-yw4l{vertical-align:top}


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
  <form name="dumpreport" method="post" action="<?php echo $_SERVER['PHP_SELF'] ;?>"> <!--dumpreportgenerate.php export_csv.php-->
    <div style="width:100%; height:auto;float:left; padding:0px 0px 10px 0px;">
    	<div class="row">
        	<table class="table">
            	<tr>
    <td><strong>Start Date:</strong></td>
    <td>
      <input name="StartDate" type="text" id="StartDate" value="<?php if($_POST['StartDate']) echo $_POST['StartDate']; else echo date('Y-m-d'); ?>" >
  
     <img src="assets/img/calendar.png" onClick="displayCalendar(document.forms[0].StartDate,'yyyy-mm-dd',this)" /></td>
    <td width="17%"><strong>End Date</strong></td>
    <td width="30%">
      <input name="EndDate" type="text" id="EndDate" value="<?php if($_POST['EndDate']) echo $_POST['EndDate']; else echo date('Y-m-d'); ?>" >
  
     <img src="assets/img/calendar.png" onClick="displayCalendar(document.forms[0].EndDate,'yyyy-mm-dd',this)" /></td>
     <td>
      <input name="btnsubmit" type="submit" class="SubmitButton" id="btnsubmit" value="Show Results" />
    </td>
  </tr>
      </table>
        </div>
    </div>
  </form>
<?php 
if(isset($_POST['btnsubmit']))
{
	$startdate=$_POST['StartDate'];
	$enddate=$_POST['EndDate'];

	$sql =	"SELECT employee_name ,a.msisdn_txt ,con_type ,quest_one_ans ,quest_two_ans,quest_two_others ,VOCdetailsStoryline,txtstory,survey_date,survey_time ,callback, status FROM cust_survey a 
	    WHERE survey_date BETWEEN '$startdate' AND '$enddate' AND callback='Yes' AND in_use = 0"; 

	//echo $sql =	"SELECT employee_name ,a.msisdn_txt ,con_type ,quest_one_ans ,quest_two_ans,quest_two_others ,VOCdetailsStoryline,txtstory,survey_date,survey_time ,callback, status, b.f_date, b.f_time, b.f_by FROM cust_survey a left join feedback b on a.msisdn_txt = b.msisdn_txt WHERE survey_date BETWEEN '$startdate' AND '$enddate' AND in_use = 0"; 
		
	$sq = mysql_query($sql) or die(mysql_error());

	if(mysql_num_rows($sq) == 0)
	{
	  echo '<p style="color:red; font-size:14px;">Sorry! No records found.</p>';
	}

?>
  <div class="tables">
  <table cellpadding="10" cellspacing="0" class="tg">
  <tr>
  	<thead>
    	<th class="tg-baqh">Sl</th>
    	<th class="tg-baqh">Last Status</th>
    	<th class="tg-baqh">Employee Name</th>
		<th class="tg-baqh">Customer MSISDN</th>
		<th class="tg-baqh">Connection Type</th>
		<th class="tg-baqh">Question One</th>
		<th class="tg-baqh">Question Two</th>
		<th class="tg-baqh">Others</th>
		<th class="tg-baqh">VOCdetailsStoryline</th>
		<th class="tg-baqh">VOC Story</th>
		<th class="tg-baqh">Survey Date</th>
		<th class="tg-baqh">Survey Time</th>
		<th class="tg-baqh">Callback Needed?</th>
    </thead>
  </tr>
  <tbody>
  <?php 
  	$i = 1;
  	while($t = mysql_fetch_assoc($sq))
	{
  ?>
  
  	<tr>
    	<td class="tg-yw4l"> <?php echo $i; ?></td>
    	<td class="tg-yw4l"> <?php echo $t['status'];//.' | '.$t['f_date'].' '.$t['f_time'].' | '.$t['f_by']; ?></td>
    	<td class="tg-yw4l"> <?php echo $t['employee_name'];?></td>
    	<td class="tg-yw4l"> <a onclick="chkStatus(<?php echo $t['msisdn_txt'];?>);" class ="chkStatus" href="#"><?php echo $t['msisdn_txt'];?></a></td>
    	<!--target="_blank" href="processTicket.php?m=--><?php //echo $t['msisdn_txt'];?>
    	<td class="tg-yw4l"> <?php echo $t['con_type'];?></td>
    	<td class="tg-yw4l"> <?php echo $t['quest_one_ans'];?></td>
    	<td class="tg-yw4l"> <?php echo $t['quest_two_ans'];?></td>
    	<td class="tg-yw4l"> <?php echo $t['quest_two_others'];?></td>
    	<td class="tg-yw4l"> <?php echo $t['VOCdetailsStoryline'];?></td>
    	<td class="tg-yw4l"> <?php echo $t['txtstory'];?></td>
    	<td class="tg-yw4l"> <?php echo $t['survey_date'];?></td>
    	<td class="tg-yw4l"> <?php echo $t['survey_time'];?></td>
    	<td class="tg-yw4l"> <?php echo $t['callback'];?></td>
    </tr>
    <?php

	$i++;

	}
}

	?>
  </tbody>
  </table>
  <br/>
  </div>
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