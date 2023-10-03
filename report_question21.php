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
	  <h4>Q2.1 category wise report</h4>
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
	  </form>
	  </table>
	</div>
</div>

<div class="tables">
<?php 
if(isset($_POST['btnsubmit']))
{
	$startdate=$_POST['StartDate'];
	$enddate=$_POST['EndDate'];

	$sql_21 =	"select distinct(quest_two_ans) ans, count(quest_two_ans) cnt 
				from cust_survey 
				where survey_date between '$startdate' and '$enddate' 
				and quest_one_ans in('Not at all satisfied', 'Somewhat Satisfied')
				group by quest_two_ans
				";
		
	$sq_21 = mysql_query($sql_21) or die(mysql_error());
	$columns_21 = mysql_num_fields($sq_21);

	if(mysql_num_rows($sq_21) == 0)
	{
	  echo '<p style="color:red; font-size:14px;">Sorry! No records found for Question 2.1.</p>';
	}
	else
	{
		echo '<p style="font-size:14px;">Results for Question 2.1</p>';

		$arr_const_21 = array( //issue with answer options
						 	'Internet'=> 0,
						 	'Value added services'=>0,
						 	'Call rate & BL offers'=>0,
						 	'Others' =>0,
						 	'Network' =>0
						 );

			//echo 'VAL:'.$arr_const['Internet'];			

			/*echo '<pre>';
			print_r($arr_const);
			echo '</pre>';*/

			//exit;

			while ($row = mysql_fetch_array($sq_21)) 
			{
				$val_arr = explode(';', $row['ans']);

				/*echo '<pre>';
				print_r($val_arr);
				echo '</pre>';*/

				foreach($val_arr as $v)
				{
					if(array_key_exists($v,$arr_const_21))
					{
						$arr_const_21[$v] = $arr_const_21[$v] + $row['cnt'];
					}
				}
			}
			
				/*echo '<pre>';
				print_r($arr_const);
				echo '</pre>';
				exit;*/


			//echo 'Total records: '.$row_count;
			
			$out = '<TABLE class="tg" cellSpacing=0 cellPadding=0 width="100%"><THEAD><tr> ';

			foreach($arr_const_21 as $key => $value)
			{
				$out.= '<th class="tg-g492">'.$key.'</th>';
			}

			$out.="\n"; 
			$out.= '</tr>  </THEAD> <TBODY>';

	        // Add all values in the table	
			$out.= '<tr>';
	        foreach($arr_const_21 as $key => $value)
			{
				$out.='<td class="tg-214n">'.$value.'</td>'; 
			}

		$out.="\n";
		$out.= '</tr>';	
		$out = $out.'</TBODY></TABLE>';
		echo $out;
	}

	echo '<br/><br/>';
	/* Question 2.2 */

	$sql_22 =	"select distinct(quest_two_ans) ans, count(quest_two_ans) cnt 
				from cust_survey 
				where survey_date between '$startdate' and '$enddate' 
				and quest_one_ans in('Satisfied', 'Very Satisfied')
				group by quest_two_ans
				";
		
	$sq_22 = mysql_query($sql_22) or die(mysql_error());
	$columns_22 = mysql_num_fields($sq_22);

	if(mysql_num_rows($sq_22) == 0)
	{
	  echo '<p style="color:red; font-size:14px;">Sorry! No records found for Question 2.2.</p>';
	}
	else
	{
		echo '<p style="font-size:14px;">Results for Question 2.2</p>';

		$arr_const_22 = array( //issue with answer options
						 	'Internet'=> 0,
						 	'Value added services'=>0,
						 	'Call rate & BL offers'=>0,
						 	'Others' =>0,
						 	'Network' =>0
						 );

			//echo 'VAL:'.$arr_const['Internet'];			

			/*echo '<pre>';
			print_r($arr_const);
			echo '</pre>';*/

			while ($row22 = mysql_fetch_array($sq_22)) 
			{
				$val_arr = explode(';', $row22['ans']);

				/*echo '<pre>';
				print_r($val_arr);
				echo '</pre>';*/

				foreach($val_arr as $v)
				{
					if(array_key_exists($v,$arr_const_22))
					{
						$arr_const_22[$v] = $arr_const_22[$v] + $row22['cnt'];
					}
				}
			}
			
				/*echo '<pre>';
				print_r($arr_const);
				echo '</pre>';
				exit;*/


			//echo 'Total records: '.$row_count;
			
			$out = '<TABLE class="tg" cellSpacing=0 cellPadding=0 width="100%"><THEAD><tr> ';

			foreach($arr_const_22 as $key => $value)
			{
				$out.= '<th class="tg-g492">'.$key.'</th>';
			}

			$out.="\n"; 
			$out.= '</tr>  </THEAD> <TBODY>';

	        // Add all values in the table	
			$out.= '<tr>';
	        foreach($arr_const_22 as $key => $value)
			{
				$out.='<td class="tg-214n">'.$value.'</td>'; 
			}

		$out.="\n";
		$out.= '</tr>';	
		$out = $out.'</TBODY></TABLE>';
		echo $out;
	}

}


function in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}

?>
</div>
  
<br/>

<?php include 'footer.php'; ?>
<!-- /container --> 

<!-- Le javascript ================================================== --> 
<!-- Placed at the end of the document so the pages load faster -->
<?php include 'js_file.php'; ?>
</body>
</html>