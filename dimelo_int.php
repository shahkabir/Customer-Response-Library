<?php 
session_start(); 
//print_r($_SESSION);

if(!isset($_SESSION['userId'])){
	$userId=$_SERVER['REMOTE_USER'];
	$userId=explode('\\',$userId);
	$userId=$userId[1];
	$_SESSION['userId']=$userId;
}

include 'header.php'; 
require_once 'DBConnection.php';
$dbc = new DBConnection();


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
	  <h4>Dimelo Historical Data</h4>
	  <br/>
	  <form name="dumpreport" method="post" action="<?php echo $_SERVER['PHP_SELF'] ;?>"> <!--dumpreportgenerate.php export_csv.php-->

	    <div style="width:100%; height:auto;float:left; padding:0px 0px 10px 0px;">
	    	<div class="row">
	        	<table class="table">
	            <tr>
				    <td><strong>Start Date:</strong></td>
				    <td>
				      <input name="StartDate" type="text" id="StartDate" value="<?php if($_POST['StartDate']) echo $_POST['StartDate']; else echo date('m-d-Y'); ?>" >
				  
				     <img src="assets/img/calendar.png" onClick="displayCalendar(document.forms[0].StartDate,'mm-dd-yyyy',this)" /></td>
				    <td width="17%"><strong>End Date</strong></td>
				    <td width="30%">
				      <input name="EndDate" type="text" id="EndDate" value="<?php if($_POST['EndDate']) echo $_POST['EndDate']; else echo date('m-d-Y'); ?>" >
				  
				     <img src="assets/img/calendar.png" onClick="displayCalendar(document.forms[0].EndDate,'mm-dd-yyyy',this)" /></td>
				     <td>
				      <input name="btnsubmit" type="submit" class="SubmitButton" id="btnsubmit" value="Show Results" />
				    </td>
	  			</tr>
	  			<tr>
	  				<td><strong>Source</strong></td>
	  				<td>
	  					<select name='source'>
	  						<option value=''>--select--</option>
			  				<option value='Messenger'>Messenger</option>
			  				<option value='Engage Chat'>Engage Chat</option>
			  				<!--<option value='Email'>Email</option>-->
			  				<option value='Facebook Page'>Facebook Page</option>
			  				<option value='Instagram'>Instagram</option>
			  				<option value='Twitter'>Twitter</option>
			  				<option value='Youtube'>Youtube</option>
		  			</select>
	  				</td>
	  			</tr>

	  			<tr>
	  				<td><strong>Customer Name</strong></td>
	  				<td>
	  					<input type='text' name="authName" id='authName' size=25/>
	  				</td>
	  			</tr>

	  			<!--<tr>
	  				<td><strong>Category</strong></td>
	  				<td>
	  					<input type='text' name="cat" id='cat' size=25/>
	  				</td>
	  			</tr>

	  			<tr>
	  				<td><strong>Text</strong></td>
	  				<td>
	  					<input type='text' name="text" id='text' size=25/>
	  				</td>
	  			</tr>-->
	  </form>
	  </table>
	</div>
</div>

<div class="tables">
<?php 

//echo 'I am here';
//exit;

if(isset($_POST['btnsubmit']))
{

	$startdate=$_POST['StartDate'];
	$enddate=$_POST['EndDate'];

	$source = $_POST['source'];
	$cat = "";//$_POST['cat'];
	$txt = "";//$_POST['text'];
	$authName = $_POST['authName'];


	$result = $dbc->fetchDimeloData($startdate,$enddate,$source,$cat,$txt,$authName);

	//$sq = mysql_query($sql) or die(mysql_error());
	$columns = mysql_num_fields($result);

	if(mysql_num_rows($result) == 0)
	{
	  echo '<p style="color:red; font-size:14px;">Sorry! No records found.</p>';
	}
	
	/*echo 'row_count:'.$row_count;
	echo 'fbdetails:'.$rfbdetails;*/
	/*if($fbdetails == 5)
	{
		echo '<p class="error" style="text-align:center"><b>Sorry! No data found.</b></p>';
	}*/

	else
	{
			//echo 'Total records: '.$row_count;
			
			$out = '<TABLE class="tg" cellSpacing=0 cellPadding=0 width="100%"><THEAD><tr> ';
			for ($i = 0; $i < $columns; $i++) 
			{	
				$col = mysql_field_name($result, $i);
				$out.= '<th class="tg-g492">'.$col.'</th>';
			}	

			$out.="\n"; 
			$out.= '</tr>  </THEAD> <TBODY>';	
	        // Add all values in the table	
			while ($row = mysql_fetch_array($result)) 
			{	
				$out.= '<tr>';
				for ($i = 0; $i < $columns; $i++) 
					{					
						if(($row["$i"])!='')
							$out.='<td class="tg-214n">'.$row["$i"].'</td>'; 
						else 
							$out.='<td class="tg-214n"></td>';
						
					}	
					$out.="\n";
					$out.= '</tr>';	
			}
		$out = $out.'</TBODY></TABLE>';
		echo $out;
	}
}
?>
</div>
  
<br/>

<?php 

exit;

include 'footer.php'; ?>
<!-- /container --> 

<!-- Le javascript ================================================== --> 
<!-- Placed at the end of the document so the pages load faster -->
<?php //include 'js_file.php'; ?>
</body>
</html>