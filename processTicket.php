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
<script type="text/javascript">

	function warning_two_min()
	{
	  alert("You have 2 minutes to finish this call.");
	}

	function cancelAndRedirect()
	{
		//var c = confirm('You will be redirected to main page');
		alert('You will be redirected to main page');	
		window.location='http://blccdmis01/voc/allTickets.php';
	}

	setTimeout(warning_two_min, 480000);//8 minutes
	setTimeout(cancelAndRedirect, 600000); //10 minutes

</script>

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
	/*background-color: #fffceb;*/
	padding-left: 10px;
	padding-bottom: 10px;
	/*border: 1px solid #f5d000;*/
	float: left;
	width: 55%;
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
.tg .tg-baqh{font-weight:bold}
.tg .tg-zczf{background-color:#FCFBE3;text-align:right;vertical-align:top}
.tg .tg-lqy6{text-align:right;vertical-align:top}
.tg .tg-yw4l{vertical-align:top}

.error {
	display: none;
	margin-left: 10px;
}
.error_show {
	color: red;
	margin-top: 10px;
	margin-bottom: 10px;
}

</style>

<?php
  	if (isset($_POST['submit']))
  	{
  		/*
  		echo '<pre>';
  		print_r($_POST);
  		echo '</pre>';
  		*/

  		$initiator = $_POST['initiator'];
  		$msisdn_txt = $_POST['msisdn_txt'];
  		$f_remarks = addslashes(trim($_POST['f_remarks']));
  		$f_status = $_POST['f_status'];
  		$f_by = $_POST['f_by'];

  		//First check if status is updated by another user or not?
  		//$sql_chk = "select status, processStatus from cust_survey where msisdn_txt = '$msisdn_txt' and processStatus <> 0";
  		//$sql_chk_r = mysql_query($sql_chk) or die();

  		//Insert feedback in feedback table
  		$sql = "INSERT INTO `feedback` (`msisdn_txt`,`f_status`,`f_remarks`,`f_date`,`f_time`,`f_by`) 
  				VALUES ('$msisdn_txt','$f_status','$f_remarks',curdate(), curtime(),'$f_by')";

  		$sql_r = mysql_query($sql) or die(mysql_error());

  		//Set locked_time_limit to now() as a new status is provided
  		$sql_up = "update cust_survey set `status`='$f_status', locked_time_limit = now() where msisdn_txt = '$msisdn_txt'";//, in_use = 0
  		$sql_up_r = mysql_query($sql_up) or die(mysql_error());


  		//Now send sms to initiator for Closed status
  		if($f_status=='Closed')
  		{
  			//Now send sms
			  $sql_emp = "select msisdn from emp_base where email='$initiator' limit 0,1";
			  $sql_emp_r = mysql_query($sql_emp) or die(mysql_die());
			  $e_arr = mysql_fetch_assoc($sql_emp_r);

			  if(mysql_num_rows($sql_emp_r) == 0) 
    		    $noUser = 'But SMS could not be sent to your mobile.';

			  $msisdn = '880'.$e_arr['msisdn'];
			  
			  $url = 'http://10.10.31.115/kannel/ivr_sms/service.php';

			  $fields = array(
			      'msisdn'       => "$msisdn",
			      'appname'      => "VOC",
			      'raddr'        => "CE Team",
			      'message'      => "Dear Colleague,\nCustomer (".$msisdn_txt.") issue has been addressed.Thanks for notifying us.\nRegards,\nCustomer Experience"
			      );

			  //open connection
			  $ch = curl_init();

			  //set the url, number of POST vars, POST data
			  curl_setopt($ch, CURLOPT_URL, $url);
			  curl_setopt($ch, CURLOPT_POST, count($fields));
			  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
			  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

			  //execute post
			  curl_exec($ch);

			  //close connection
			  curl_close($ch);
  		}


  		$_SESSION["status_message"]['success'] = "Thank you! Ticket status has been updated successfully.";

  		//header('Location: processTicket.php?m=$msisdn_txt');
  	}
  ?>

<?php 
	/*echo '<pre>';
	print_r($_SESSION);
	echo '</pre>';*/
?>
  
<div class="container" style="padding-top:10px;">
  <?php if($_SESSION['status_message']) { ?>
	  <div class="status_message_contaier">
	    <div class="status_message_contaier_inner">
	      <?php if($_SESSION['status_message']['success']) { ?>
	      <div class="status_message success" style="padding: 4px; text-align: center;">
	      	<?php echo $_SESSION['status_message']['success']; ?>
	      </div>
	      <?php } ?>
	      <?php unset($_SESSION['status_message']); ?>
	    </div>
	  </div>
  <?php } ?>

  

  <?php 
  if(isset($_GET['m']))
  {
  	$m = trim($_GET['m']);

   	//First check if it is locked by another user or not?
  	/*$sql_chk = "select status, in_use from cust_survey where msisdn_txt = '$m' and in_use <> 0 limit 0,1";
  	$sql_chk_r = mysql_query($sql_chk) or die(mysql_error());
  	if(mysql_num_rows($sql_chk_r) != 0)
  	{
  		$process_flag = 1;
  	}*/
  	 
  	/* Update locked time limit by adding 15 minutes, so it will be locked for 15 minutes */
  	$sql_st = "update cust_survey set locked_time_limit=now() + INTERVAL 10 MINUTE where msisdn_txt = '$m'";
  	$sql_r = mysql_query($sql_st) or die(mysql_error());


  	$sql = "select * from cust_survey where msisdn_txt = '$m' limit 0,1";
  	$sql_r = mysql_query($sql) or die(mysql_error());

	if(mysql_num_rows($sql_r) == 0)
	{
	  echo '<p style="color:red; font-size:14px;">Sorry! No records found.</p>';
	}
	$t = mysql_fetch_assoc($sql_r);

	//Now fetch Ticket history
	$sql_his = "select * from feedback where msisdn_txt = '$m' order by f_date,f_time ASC";
	$sql_his_r = mysql_query($sql_his) or die(mysql_error());


  ?>
  
  <form name="feedbackForm" id="feedbackForm" method="post" action="<?php echo $_SERVER['PHP_SELF'] ;?>"> 
    <div style="width:100%; height:auto;float:left; padding:0px 0px 10px 0px;">
    	<h3>Second Level Feedback</h3>
    	<div class="row_container">
	    	<?php 
	    	/* if($process_flag == 1)
	    	 {*/
	    	 ?>
	    	 	<p style="color:red">This ticket is now locked for 10 minutes.</p>
	    	 <?php
	    	 //}
	    	?>
        	<table cellpadding="10" cellspacing="0" class="tg" style="max-width:50%">
            	<tr>
            		<td class="tg-baqh">Status</td>
    				<td class="tg-yw4l"> <?php $status = $t['status'];
    				echo $t['status']; ?></td>
  				</tr>
  				<tr>
            		<td class="tg-baqh">Employee Name</td>
    				<td class="tg-yw4l"> <?php echo $t['employee_name']; ?></td>
    				<input name="initiator" type="hidden" id="initiator" value="<?php echo $t['employee_name'];?>"/>
    				<input name="f_by" type="hidden" id="f_by" value="<?php echo $_SESSION['userId'];?>"/>
  				</tr>
				<tr>
            		<td class="tg-baqh">Survey Date/Time</td>
    				<td class="tg-yw4l"> <?php echo $t['survey_date'].' '.$t['survey_time']; ?></td>
  				</tr>
  				
  				<tr>
            		<td class="tg-baqh">Customer MSISDN</td>
    				<td class="tg-yw4l"> <?php echo $t['msisdn_txt']; ?></td>
    				<input type="hidden" name="msisdn_txt" value="<?php echo $t['msisdn_txt']; ?>" />
  				</tr>
  				<tr>
            		<td class="tg-baqh">Connection Type</td>
    				<td class="tg-yw4l"> <?php echo $t['con_type']; ?></td>
  				</tr>
  				<tr>
            		<td class="tg-baqh">Question One</td>
    				<td class="tg-yw4l"> <?php echo $t['quest_one_ans']; ?></td>
  				</tr>
  				<tr>
            		<td class="tg-baqh">Question Two</td>
    				<td class="tg-yw4l"> <?php echo $t['quest_two_ans']; ?></td>
  				</tr>
  				<tr>
            		<td class="tg-baqh">Others</td>
    				<td class="tg-yw4l"> <?php echo $t['quest_one_ans']; ?></td>
  				</tr>
  				<tr>
            		<td class="tg-baqh">VOC details Storyline</td>
    				<td class="tg-yw4l"> <?php echo $t['VOCdetailsStoryline']; ?></td>
  				</tr>
  				<tr>
            		<td class="tg-baqh">VOC Story</td>
    				<td class="tg-yw4l"> <?php echo $t['txtstory']; ?></td>
  				</tr>
  				<tr>
            		<td class="tg-baqh">Remarks</td>
  					<td>
  					<textarea id="txtstory" name="f_remarks" cols="60" rows="8" maxlength="1000"></textarea>
  						<div id="textarea_feedback" style="font-size: 12px;font-style: italic;"></div>
  					</td>
  				</tr>
  				<tr>
            		<td class="tg-baqh">Feedback status</td>
    				<td class="tg-yw4l">
    					<select name="f_status" size="1" id="f_status" class="">
    						<option selected value="0">--Select--</option>
    						<option value="Open">Open</option>
    						<option value="Under Investigation">Under Investigation</option>
    						<option value="Not Reached">Not Reached</option>
    						<option value="Closed">Close</option>
    					</select>
    					<span id="st_id" class="error">Status is required</span>
    				</td>
  				</tr>
      		</table>
        </div>
    <!--</div>-->
			<div style="float:right; height:auto; width:40%">
    			<h5>Ticket History</h5>

			    <div class="tables">
				  <table cellpadding="10" cellspacing="0" class="tg">
				  <tr>
				  	<thead>
				    	
				    	<th class="tg-baqh">Status</th>
				    	<th class="tg-baqh">Remarks</th>
						<th class="tg-baqh">Date</th>
						<th class="tg-baqh">Time</th>
						<th class="tg-baqh">Handled By</th>
			    </thead>
			  </tr>
			  <tbody>
			  <?php
				 if(mysql_num_rows($sql_his_r) == 0)
				 {
		  			echo '<p style="color:red; font-size:14px;">No history found.</p>';
				 }
			  	while($t = mysql_fetch_assoc($sql_his_r))
				{
			  ?>
			  	<tr>
			    	<td class="tg-yw4l"> <?php echo $t['f_status'];?></td>
			    	<td class="tg-yw4l"> <?php echo $t['f_remarks'];?></td>
			    	<td class="tg-yw4l"> <?php echo $t['f_date'];?></td>
			    	<td class="tg-yw4l"> <?php echo $t['f_time'];?></td>
			    	<td class="tg-yw4l"> <?php echo $t['f_by'];?></td>
			    </tr>
			    <?php
				}
				?>
			  </tbody>
			  </table>
			  <br/>
			  </div>
    		</div>
    
    		<?php //echo 'Status: '.$status;?>
	    <div class="row_right" style="width:45%; <?php if ($process_flag == 1 || $status == 'Closed') echo 'display:none'; ?>">
	      <input type="submit" name="submit" value="Save" class="btn btn-primary">
	      <input type="reset" name="close" id="close" value="Close Window" class="btn">
	    </div>
	  </form>
  </div>
	<?php
	}
	else
	{
	 //echo '<p style="color:red; font-size:14px;">Sorry! Invalid request.</p>';
	}
	?>
  </tbody>
  </table>
  <br/>
  </div>

  	<div class="foot_div">
  		<p><strong>&copy; Digital Services & Solutions | DSS-AppDev</strong></p>
	</div>
</div>

<?php //include 'footer.php'; ?>
<!-- /container --> 

<!-- Le javascript ================================================== --> 
<!-- Placed at the end of the document so the pages load faster -->
<?php include 'js_file.php'; ?>
</body>
</html>