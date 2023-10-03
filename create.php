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
    
    //exit;

    try{

    		$insert = $dbc->insertMessage($channel,$category,$subcategory,$labelQ,$language,$content, $agentID, $today, $publish);

	    	//$insert_log = $dbc->insertFeedback($ticketid, $status, $agentID, $datetime, $filename, $location, $unixtime_ticket);

		    if($insert == false) //|| $insert_log == false)
		    {
		    	echo '<script language="javascript"> alert("Sorry! Entry could not be saved, contact concern person.")</script>';
		    }else
		    {

		    	echo '<script language="javascript"> alert("New Message has been created successfully.")</script>';

		    	$_SESSION['status_message']['success'] = "New Message has been created successfully.";
		    	header('Location: create.php');
		    }
		

	}catch (RuntimeException $e) {
   		
   		echo $e->getMessage();

    	echo '<script language="javascript"> alert("Sorry! Entry could not be saved, contact with concern person.")</script>';

    	$_SESSION['status_message']['success'] = "Sorry! Entry could not be saved, contact concern with person.";

    	header('Location: create.php');
   		die;
	}
}
//exit;

if(isset($_POST['newCatSave']))
{
	//$dbc->var_dump($_POST);

	$newCat = addslashes(trim($_POST['newCat']));
	$agentID = $_SESSION['userId'];
	$today = date('Y-m-d');

	$result = $dbc->insertNewCategory($newCat, $agentID, $today);

	if($result)
	{
		echo '<script language="javascript"> alert("New Category has been added successfully.")</script>';

		$_SESSION['status_message']['success'] = "New Category has been added successfully.";
		
		header('Location: create.php');
	}

	//exit;
}

if(isset($_POST['newSubCatSave']))
{
	//$dbc->var_dump($_POST);

	$newSubCat = addslashes(trim($_POST['newSubCat']));
	$agentID = $_SESSION['userId'];
	$today = date('Y-m-d');

	$result = $dbc->insertNewSubCategory($newSubCat, $agentID, $today);

	if($result)
	{
		echo '<script language="javascript"> alert("New Sub-Category has been added successfully.")</script>';

		$_SESSION['status_message']['success'] = "New Sub-Category has been added successfully.";
		
		header('Location: create.php');
	}

	//exit;
}

if(isset($_POST['newSourceSave']))
{
	//$dbc->var_dump($_POST);

	$newSource = addslashes(trim($_POST['newSource']));
	$agentID = $_SESSION['userId'];
	$today = date('Y-m-d');

	$result = $dbc->insertNewSource($newSource, $agentID, $today);

	if($result)
	{
		echo '<script language="javascript"> alert("New Source has been added successfully.")</script>';

		$_SESSION['status_message']['success'] = "New Source has been added successfully.";
		
		header('Location: create.php');
	}
}

if(isset($_POST['updateCat']))
{

	$catId = $_POST['catnames'];
	$updateCat = addslashes(trim($_POST['editCat']));
	$agentID = $_SESSION['userId'];
	$today = date('Y-m-d');

	$result = $dbc->updateCategory($catId, $updateCat, $agentID, $today);

	if($result)
	{
		echo '<script language="javascript"> alert("Category has been updated successfully.")</script>';

		$_SESSION['status_message']['success'] = "Category has been updated successfully.";
		
		header('Location: create.php');
	}
}


if(isset($_POST['updateSubCat']))
{
	//$dbc->var_dump($_POST);
	//exit;

	$subCatId = $_POST['subcatnames'];
	$updateSubCat = addslashes(trim($_POST['editSubCat']));
	$agentID = $_SESSION['userId'];
	$today = date('Y-m-d');

	$result = $dbc->updateSubCategory($subCatId, $updateSubCat, $agentID, $today);

	if($result)
	{
		echo '<script language="javascript"> alert("Sub-Category has been updated successfully.")</script>';

		$_SESSION['status_message']['success'] = "Sub-Category has been updated successfully.";
		
		header('Location: create.php');
	}
}

$cat = json_decode($dbc->getCategory());
$subcat = json_decode($dbc->getSubCategory());
$source = json_decode($dbc->getSource());


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
		<!-- <script language="javascript" src="assets/js/jquery-ui-1.12.1.js"></script> -->
		<!-- <script language="javascript" src="assets/js/jquery.validate.min-1.16.0.js"></script> -->
		<!-- <link rel="stylesheet" type="text/css" href="assets/js/jquery-ui.css"> -->
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

		<!-- <link rel="stylesheet" type="text/css" href="assets/css/stylesheet.css"> -->
		
		<script>
		$(document).ready(function () {
			//$(function() {

				/*$("#ticketid").change(function(){
					//var ticketid = $(this).val();
					//alert(ticketid);

					$.ajax({
						type: "post",
						url: "checkDuplicate.php",
						cache: false,
						data: $('#ticketid').serialize(),
						success: function(json)
						{
							//alert(json);

							if(json==1)
							{
								alert('Sorry! This ticket is already created.');
								$('#ticketid').val('');
							}

						}

					});
				});

				$("#agentID").prop("readonly", true);*/


			    // $("#qry").autocomplete({
      	// 			source: q_arr,
      	// 			select: function(event, ui){
      	// 				$("#wc").val(ui.item.code);
      	// 				$("#cat").val(ui.item.cat);
      	// 			}
    			// });

    			// $("#wc,#agntID,#cat").prop("readonly",true);

    			// $("#qry").attr("placeholder","type to search query");
				

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

						if($('#pub').val() == "NA")
						{
							isValid = false;
							if(isValid==false)
							{
								$('#pub').css({
									"border" : "2px solid red",
									"background" : "#FFCECE"
								});
							}
						}else{
								$('#pub').css({
									"border" : "",
									"background" : ""
								});
						}

						/*if($('#flwp_second').val() == "NA")
						{
							isValid = false;
							if(isValid==false)
							{
								$('#flwp_second').css({
									"border" : "2px solid red",
									"background" : "#FFCECE"
								});
							}
						}else{
								$('#flwp_second').css({
									"border" : "",
									"background" : ""
								});
						}*/

						//alert(isValid);

						if(isValid == false)
						{
							e.preventDefault();
						}
					}
				);

				/*$("#frmAgentEntry").validate({
					 messages: {
				     outcome: {
				      required: "Please select an option from the list",
				     },
				    }
				});*/
			});
		//});
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

  
  <div class="row">
  	<div class="span4">
  		<form name="frmAgentEntry" id="frmAgentEntry" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<table class="tg">
		  <tr>
		    <th height="20" colspan="2">
				<div align="center">Create New Message</div>	
			</th>
		  </tr>
	  
	    <tr>
		    <td><div align="right">Source</div></td>
		    <td>      
		    	<div align="left">

		    		<?php foreach ($source as $so) {

		    		echo '<div class="checkbox-div">
				    	<input type="checkbox" name="channels[]" value="'.$so->value.'"/> '. $so->value.
				    "</div>";
		    		 }?>

					<div class="checkbox-div">
						<a id="anchor-source">Add New</a>
					</div>
				        
			  </div>
			  <br/>

		      <div id="div-source" style="display: none;">
		      	<input type="text" id="newSource" name="newSource" value="">
		      	<input type="submit" class="" name="newSourceSave" id="newSourceSave" value="Save">
		      </div>

			</td>
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
	        </select> &nbsp &nbsp <a id="anchor-cat">Add New</a>
			</div>
			<br/>
	      <div id="div-cat" style="display: none;">
	      	<input type="text" id="newCat" name="newCat" value="">
	      	<input type="submit" class="" name="newCatSave" id="newCatSave" value="Save">
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
	        	</select> &nbsp &nbsp <a id="anchor-subcat">Add New</a>
	      </div>
	      <br/>
	      <div id="div-Subcat" style="display: none;">
	      	<input type="text" id="newSubCat" name="newSubCat" value="">
	      	<input type="submit" class="" name="newSubCatSave" id="newSubCatSave" value="Save">
	      </div>
	  	</td>
	  </tr>

	  <tr> 
	    <td><div align="right">Label Questionaries</div></td>
	    <td>
	    	<div align="left">
	        <input type="text" id="labelQ" name="labelQ">
	      </div>
	    </td>
	   </tr>

	    <tr>
		    <td><div align="right">Language</div></td>
		    <td align="left">
		    	<div align="left">
	       		 <select name="lang" id="lang">
		        	<option value="NA">-Select-</option>
		        	<option value="English">English</option>
		        	<option value="Bangla">Bangla</option>
	        	</select>
	      </div>
		    </td>
	    </tr>

	    <tr>
	    <td><div align="right">Content</div></td>
	    <td align="left">
	      <div align="left">
	        <textarea rows="20" cols="50" name="content" id="content"></textarea>
	        <span class="man">*</span>
	      </div></td>
	    </tr>
	  <tr>

	  <tr>
		    <td><div align="right">Publish?</div></td>
		    <td align="left">
		    	<div align="left">
			       		 <select name="pub" id="pub">
				        	<option value="NA">-Select-</option>
				        	<option value="1">Yes</option>
				        	<option value="0">No</option>
			        	</select>
	      		</div>
		    </td>
	    </tr>

	    <tr>
		    <td colspan="2"> 
				<div align="center">

			   	  		<input type="reset"  class="btnSubmitAgentEntry" name="reset" value="Reset">

			   	  		<input type="submit" class="btnSubmitAgentEntry" name="btnSave" id="btnSave" value="Submit">
			   	  		<!--onClick="return chkFormSubmitValidity();clearDiv();"-->
			    </div>
			</td>
	    </tr>
	 </table>
	 </form>
	</div>
	<div class="span2" style="padding-left: 100px">
		<form name="formCategory" id="formCategory" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
			<table class="tg">
			  <tr>
			    <th height="20" colspan="2">
					<div align="center">Edit Category</div>	
				</th>
			  </tr>
			  <tr>
			  	<td>
			  		<select name="catnames" id="catnames" size="20" style="width:160px;">
			          <?php 
					  foreach($cat as $c){
					  ?>
			          <option value="<?php echo $c->id;?>"><?php echo $c->value;?></option>
			          <?php }?>
			        </select>
			  		<!-- <input type="button" id="addCat" value="ADD"> -->
			  	<br/>
			  	<br/>
			  		<input type="text" id="editCat" name="editCat"><br/><br/>
			  		<input type="submit" name="updateCat" id="updateCat" value="Update">
			  	</td>
			  </tr>
		    </table>
		</form>
	</div>

	<div class="span2">
		<form name="formSubCategory" id="formSubCategory" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
			<table class="tg">
			  <tr>
			    <th height="20" colspan="2">
					<div align="center">Edit Sub-Category</div>	
				</th>
			  </tr>
			  <tr>
			  	<td>
			  		<select name="subcatnames" id="subcatnames" size="20" style="width:200px;">
			          <?php 
					  foreach($subcat as $sc){
					  ?>
			          <option value="<?php echo $sc->id;?>"><?php echo $sc->value;?></option>
			          <?php }?>
			        </select>
			  		<!-- <input type="button" id="addCat" value="ADD"> -->
			  	<br/>
			  	<br/>

			  
			  	<input type="text" id="editSubCat" name="editSubCat" size="25"><br/><br/>
			  	<input type="submit" name="updateSubCat" id="updateSubCat" value="Update">
			  	</td>
			  </tr>
		    </table
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

<script type="text/javascript">

$(document).ready(function() 
{ 
   $('#catnames').click(function(){

   		$('#editCat').val($('#catnames option:selected').text());

		//$('#editCat').val($(this).val());   	

   });

   $('#subcatnames').click(function(){

   		$('#editSubCat').val($('#subcatnames option:selected').text());

		//$('#editCat').val($(this).val());   	

   });

});
</script>


</body>
</html>

