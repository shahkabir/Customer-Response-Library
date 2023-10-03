<?php
session_start();
set_time_limit(-30); 
error_reporting(-1);


//echo $_SESSION['kpi_userId'].'-'.$_SESSION['kpi_access'].'-'.$_SESSION['kpi_type'];
/*if(!isset($_SESSION['kpi_userId']) && !isset($_SESSION['kpi_access']) && !isset($_SESSION['kpi_type']))
{die('<p><b>Session Time out. Please Click <a href="http://blccdmis01/kpi_gen/index.php" target="_top">here</a> for new Session</b></p>');}
set_time_limit(-30);
$loginname=$_SESSION['kpi_userId'];
$access=$_SESSION['kpi_access'];
$type=$_SESSION['kpi_type'];*/
//$_SESSION['status_message'] = '';

if(!isset($_SESSION['userId'])){
	$userId=$_SERVER['REMOTE_USER'];
	$userId=explode('\\',$userId);
	$userId=$userId[1];
	$_SESSION['userId']=$userId;
}


include 'header.php'; 

require_once 'DBConnection.php';

$dbc = new DBConnection();

//$arr = $dbc->fetchAll();

/*echo '<pre>';
print_r($arr);
echo '</pre>';
exit;*/
//echo $categoryArr[0][workCode];


if(isset($_POST['btnSave']))
{
	/*
	echo '<pre>';
	print_r($_POST);
	echo '</pre>';
	*/

	//exit;

	$channels = $_POST['channels']	;

	$channel = '';
	foreach ($channels as $key => $value) {
		//echo '$value:'.$value;
		$channel = $channel.$value.'/';
	}
	$channel = substr($channel,0,-1);
	//echo '$channel:'. $channel;

    $category = addslashes(trim($_POST['category']));
    $subcategory = addslashes(trim($_POST['subcategory']));
    $labelQ = addslashes(trim($_POST['labelQ']));
    $language = addslashes(trim($_POST['lang']));
    $content = addslashes(trim($_POST['content']));
    $agentID = $_SESSION['userId'];
    $today = date('Y-m-d');
    $publish = addslashes(trim($_POST['pub']));
    $id = $_POST['id'];
    
    //exit;

    try{

    		$updateLogId = $dbc->updateLog($id, $agentID, $today);

    		$insert = $dbc->updateMessage($id,$channel,$category,$subcategory,$labelQ,$language,$content, $agentID, $today, $publish, $updateLogId);

		    if($insert == false || $updateLogId == 0)
		    {
		    	echo '<script language="javascript"> alert("Sorry! Entry could not be update, contact concern person.")</script>';
		    }else
		    {

		    	echo '<script language="javascript"> alert("Message has been updated successfully.")</script>';

		    	//$_SESSION['status_message']['success'] = "New Message has been updated successfully.";
		    	header('Location: edit.php');
		    }
		
	}catch (RuntimeException $e) {
   		
   		echo $e->getMessage();

    	echo '<script language="javascript"> alert("Sorry! Entry could not be updated, contact with concern person.")</script>';

    	$_SESSION['status_message']['success'] = "Sorry! Entry could not be updated, contact concern with person.";

    	header('Location: edit.php');
   		die;
	}
}
?>

<!DOCTYPE HTML>
<html lang="en">
  <head>
		<title>New Message</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		
		<style>
		   .hid { display: none }
		   .loads {
		      position:absolute;
		      /*background-color:#FF9900!important;*/
		   }
		   .loading {
			position:absolute;
			background-color:#FF9900!important;
			left: 504px;
			top: 52px;
		   }
		   .loads h2 { color: #000; }

		   .man
		   {
		   	color: red;
		   }

		   
		</style>

		<!--<script language="javascript" src="../javascripts/jquery-1.9.1.js"></script>-->
		
		<script language="javascript" src="assets/js/jquery-3.5.1.js"></script>
		<!-- <script language="javascript" src="javascripts/jquery-ui-1.12.1.js"></script> -->
		<!-- <script language="javascript" src="javascripts/jquery.validate.min-1.16.0.js"></script> -->
		<!-- <link rel="stylesheet" type="text/css" href="javascripts/jquery-ui.css"> -->
		<!-- <script type="text/javascript" src="popcalendar.js"></script> -->
		<!--<script language="javascript" src="../javascripts/entryPage.js"></script>
		<script language="JavaScript" src="../javascripts/tree.js"></script>
		
		<script language="JavaScript" src="../javascripts/tree_tpl.js"></script>-->
		
		<!-- <link href="../default.css" rel="stylesheet" type="text/css" /> -->
		<!--<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>-->

		<link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen">
		<script type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>

		<link rel="stylesheet" type="text/css" href="stylesheet.css">
		
		<script language="javascript">
		$(document).ready(function () {
				
				$("#btnSave").click(function(e) {

						var isValid = true;

						$('#content, #labelQ').each(function(){

							if($.trim($(this).val()) == '')
							{
								isValid = false;

								$(this).css({
									"border" : "2px solid red",
									"background" : "#FFCECE"
								});
							}else
							{
								$(this).css({
									"border" : "",
									"background" : ""
								});
							}
						});

						/* Check select tag*/
						if($('#category').val() == "NA")
						{
							isValid = false;
							if(isValid==false)
							{
								$('#category').css({
									"border" : "2px solid red",
									"background" : "#FFCECE"
								});
							}
						}else{
								$('#category').css({
									"border" : "",
									"background" : ""
								});
						}

						if($('#subcategory').val() == "NA")
						{
							isValid = false;
							if(isValid==false)
							{
								$('#subcategory').css({
									"border" : "2px solid red",
									"background" : "#FFCECE"
								});
							}
						}else{
								$('#subcategory').css({
									"border" : "",
									"background" : ""
								});
						}

						if($('#lang').val() == "NA")
						{
							isValid = false;
							if(isValid==false)
							{
								$('#lang').css({
									"border" : "2px solid red",
									"background" : "#FFCECE"
								});
							}
						}else{
								$('#lang').css({
									"border" : "",
									"background" : ""
								});
						}

						if(isValid == false)
						{
							e.preventDefault();
						}
					}
				);
			});
		</script>

		<script language="javascript">
		  		function chkNumeric(obj)
		  		{ 
		  			if(obj.value!=""&&(!(parseInt(obj.value)>0) || parseInt(obj.value)!=obj.value))
		  				{ 
		  					window.alert('Non Numeric Character Entry'); 
		  					obj.value=""; 
		  					obj.focus();
		  				}
		  		}
		</script>
  </head>

  <body>
  	<div class="row_container">
		<?php 
		    //session_start();
			
		    //echo 'SessionSTatusMSG'.$_SESSION['status_message'];

			/*
			echo '<pre>';
			print_r($_SESSION);
			echo '</pre>';
			*/

		if($_SESSION['status_message']) { ?>
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
    </div>

<?php
if((isset($_GET['id'])) && (!is_null($_GET['id'])))
{
	$id = $_GET['id'];
	$msg = json_decode($dbc->getMessageById($id));
	$cat = json_decode($dbc->getCategory());
	$subcat = json_decode($dbc->getSubCategory());

	/*echo '<pre>';
	print_r($cat);
	echo '</pre>';*/

	//echo $cat[0]->value;
	//echo $msg[0]->category;

	//exit;
}

?>
  <form name="frmAgentEntry" id="frmAgentEntry" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>">
  <div class="container">
	<table class="tg">
	  <tr>
	    <th height="20" colspan="2">
			<div align="center">Edit Message</div>	
		</th>
	  </tr>
	  <input type="hidden" name="id" value="<?php echo $msg[0]->Id; ?>">
	  <tr>
	    <td><div align="right">Source</div></td>
	    <td>      
	    	<div align="left">
	    		<?php
	    		$channels = $msg[0]->source;
	    		$channels_arr = explode('/', $channels);
	    		/*echo '<pre>';
				print_r($channels_arr);
				echo '</pre>';*/

	    		?>
			    <div class="checkbox-div">
			    	<input <?php if (in_array('Facebook', $channels_arr)) echo 'checked';?> type="checkbox" name="channels[]" value="Facebook" onclick="return ValidatePetSelection();"/> Facebook
			    </div>
			    <div class="checkbox-div">
			    	<input <?php if (in_array('Email', $channels_arr)) echo 'checked';?> type="checkbox" name="channels[]" value="Email" onclick="return ValidatePetSelection();"> Email
				</div>
				<div class="checkbox-div">
			    	<input <?php if (in_array('Playstore', $channels_arr)) echo 'checked';?> type="checkbox" name="channels[]" value="Playstore" onclick="return ValidatePetSelection();"> Playstore
				</div>
				<div class="checkbox-div">
			    	<input <?php if (in_array('Youtube', $channels_arr)) echo 'checked';?> type="checkbox" name="channels[]" value="Youtube" onclick="return ValidatePetSelection();"> Youtube
				</div>
				<div class="checkbox-div">
			    	<input <?php if (in_array('Instagram', $channels_arr)) echo 'checked';?> type="checkbox" name="channels[]" value="Instagram" onclick="return ValidatePetSelection();"> Instagram
				</div>
				<div class="checkbox-div">
			    	<input <?php if (in_array('Twitter', $channels_arr)) echo 'checked';?> type="checkbox" name="channels[]" value="Twitter" onclick="return ValidatePetSelection();"> Twitter
				</div>
			        
		  </div></td>
	    </tr>
	  <tr>
	    <td><div align="right">Category</div></td>
	    <td>
			<div align="left">
		      <select name="category" id="category">
	        	<option value="NA">-Select-</option>

	        	<?php foreach ($cat as $c){
	        		/*echo $c->value;
	        		echo $msg[0]->category;*/
	        		$sel = '';
	        		if(($c->value) == ($msg[0]->category)){
	        			$sel = 'selected';
	        		}
	        		echo "<option ".$sel." value='$c->value'>$c->value</option>";
	        	}?>
	        </select> 
			</div>
		</td>
	    </tr>

	  <tr>
	    <td><div align="right">Sub Category</div></td>
	    <td align="left">
	      <div align="left">
	       		 <select name="subcategory" id="subcategory">
		        	<option value="NA">-Select-</option>

		        	<?php foreach ($subcat as $sc){
		        		$sel2='';
		        		if(($sc->value) == ($msg[0]->subcategory)){
		        			$sel2 = 'selected';
		        		}

	        			echo "<option ".$sel2." value='$sc->value'>$sc->value</option>";
	        		}?>
	        	</select>  
	      </div>
	  	</td>
	  </tr>

	  <tr> 
	    <td><div align="right">Label Questionaries</div></td>
	    <td>
	    	<div align="left">
	        <input type="text" id="labelQ" name="labelQ" value="<?php echo $msg[0]->label?>">
	      </div>
	    </td>
	   </tr>

	    <tr>
		    <td><div align="right">Language</div></td>
		    <td align="left">
		    	<div align="left">
	       		 <select name="lang" id="lang">
		        	<option value="NA">-Select-</option>
		        	<option <?php if ($msg[0]->language == 'English') echo 'selected';?> value="English">English</option>
		        	<option <?php if ($msg[0]->language == 'Bangla') echo 'selected';?> value="Bangla">Bangla</option>
	        	</select>
	      </div>
		    </td>
	    </tr>

	    <tr>
	    <td><div align="right">Content</div></td>
	    <td align="left">
	      <div align="left">
	        <textarea rows="20" cols="50" name="content" id="content"><?php echo $msg[0]->content?></textarea>
	        <span class="man">*</span>
	      </div></td>
	    </tr>
	  <tr>

	  	<tr>
		    <td><div align="right">Published?</div></td>
		    <td align="left">
		    	<div align="left">
	       		 <select name="pub" id="pub">
		        	<option value="NA">-Select-</option>
		        	<option <?php if ($msg[0]->publish == '1') echo 'selected';?> value="1">Yes</option>
		        	<option <?php if ($msg[0]->publish == '0') echo 'selected';?> value="0">No</option>
	        	</select>
	     		</div>
		    </td>
	    </tr>

	    <td colspan="2"> 
			<div align="center">

		   	  		<input type="reset"  class="btnSubmitAgentEntry" name="reset" value="Reset">

		   	  		<input type="submit" class="btnSubmitAgentEntry" name="btnSave" id="btnSave" value="Submit">
		   	  		<!--onClick="return chkFormSubmitValidity();clearDiv();"-->
		    </div>
		</td>
	    </tr>
	    </tr>
	 </table>
	</div>
	</form>

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

