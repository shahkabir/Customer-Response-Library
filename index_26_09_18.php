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
include 'DBConnection.php';


if(isset($_POST['submit']))
{
	 
    /*echo '<pre>';
    print_r($_POST);
    echo '</pre>';
    //exit;
    */

	 	$employee_name		=	$_POST['employee_name'];	   
		//$employee_phone		=	$_POST['employee_phone'];
		$msisdn_txt			=	$_POST['msisdn_txt'];
    $con_type       = $_POST['con_type'];
    $con_edit       = $_POST['con_edit'];

		$q1					=	$_POST['q1'];
    $q2         = $_POST['q2'];
    $q3         = $_POST['q3'];

    $q2_ans     = $q2_others = '';

    if($q1 == 'Satisfied' || $q1 == 'Very Satisfied')
    {
      $q2_ans = implode(";", $q2);
      $q2_others = $_POST['q21_o'];
    }

    if($q1 == 'Not at all satisfied' || $q1 == 'Somewhat Satisfied')
    {
      $q2_ans = implode(";", $q3);
      $q2_others = $_POST['q22_o'];
    }

    $VOCdetailsStoryline = addslashes($_POST['story']);
    $txtstory = addslashes($_POST['txtstory']);

    $q2_others = addslashes($q2_others);
    
		$callback			=	$_POST['call_back'];
    if(!(isset($_POST['call_back'])))
    {
      $callback = 'No';
    }
		
		$stime	=	date("h:i:s");
		$sdate	=	date("Y-m-d");
		$status = 'Open';

		$sql	=	"INSERT INTO cust_survey";
		$sql	.=	"(employee_name,";
		$sql	.=	"msisdn_txt,con_type,";
		$sql	.=	"quest_one_ans,quest_two_ans,quest_two_others,"; 
		$sql	.=	"VOCdetailsStoryline,txtstory,survey_date,survey_time,callback,status)";	
		$sql	.=	"VALUES('$employee_name','$msisdn_txt','$con_type','$q1','$q2_ans','$q2_others',";   
	  $sql	.=	"'$VOCdetailsStoryline','$txtstory','$sdate','$stime','$callback','$status')";
	
	  //echo '<br/>'.$sql; 
    //exit;
  	$sql_r	=	mysql_query($sql) or die(mysql_error());

    //Now insert in feedback table
    $sql_feed = "INSERT INTO `feedback` (`msisdn_txt`,`f_status`,`f_remarks`,`f_date`,`f_time`,`f_by`) 
                 VALUES ('$msisdn_txt','$status','$txtstory',curdate(), curtime(),'$employee_name')";
  	
    $sql_feed_r = mysql_query($sql_feed) or die(mysql_error());

    //echo '<br/>msisdn_txt: '.$msisdn_txt;
  	
  	$sqlu	=	"UPDATE msisdnlist SET statusd='1' WHERE msisdn='$msisdn_txt'";
    //exit;
  	$exec_up	=	mysql_query($sqlu) or die(mysql_error());
  	
    //Now send sms
    $sql_emp = "select msisdn from emp_base where email='$employee_name' limit 0,1";
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
        'message'      => "Dear Colleague,\nThanks for getting in touch with the customer. \nRegards,\nCustomer Experience"
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

    //var_dump($result);
    $_SESSION["status_message"]['success'] = "Thank you! Information has been stored successfully. $noUser";
}

?>
	
<style type="text/css">

body {
	padding-top: 10px;
	padding-bottom: 35px;
}

.status_message {
  font-size: 12px;
  font-weight: bold;
  padding: 8px 12px;
  margin: 0 0 5px 0;
}
.status_message.error {
  /*background: url(../images/common/status_message/error.png) no-repeat center center transparent;*/
  background-color:#FFF9D7; color:#FF0000; border:1px solid #E2C822;
}
.status_message.warning {
  /*background: url(../images/common/status_message/warning.png) no-repeat center center transparent;*/
  background-color:#eef8db; color:#003366; border:1px solid #a0ae86;
}
.status_message.success {
  /*background: url(../images/common/status_message/success.png) no-repeat center center transparent;*/
  color: #000; font-size: 15px;
}
.status_message.notification {
  /*background: url(../images/common/status_message/notification.png) no-repeat center center transparent;*/
  background-color:#eef8db; color:#003366; border:1px solid #a0ae86;
}
.status_message.information {
  /*background: url(../images/common/status_message/information.png) no-repeat center center transparent;*/
  background-color:#E2F9E3; color:#000000; border:1px solid #99CC99;
}

.row_container{
	height: auto;
	background-color:#fffceb;
	padding-left:10px;
	padding-bottom:10px;
	border:1px solid #f5d000;
	float:left;
	width:75%;
}

.row_left{
    font-family: "HCo Gotham", "HCo Gotham SSm", Arial, sans-serif;
		font-weight:bold;
		font-size:14px;
		width:50%;
		height:auto;
		padding:10px 10px 10px 5px;
		float:left;
}

.row_right{
		font-weight:bold;
		font-size:14px;
		width:40%;
		height:auto;
		padding:5px 10px 10px 5px;
		float:left;
}

.row{
	float:left;
	width:100%;
	height:auto;
	padding:0px;
	margin:0px 0px 0px 0px;
	font-size:16px;
}

.error {
	display: none;
	margin-left: 10px;
}
.error_show {
	color: red;
	margin-top: 10px;
	margin-bottom: 10px;
}

textarea {
    font-family: "Arial", Helvetica, sans-serif;
    font-size: 14px;
}

input[disabled], input[disabled]:hover 
{ 
  color:#444; font-weight:bold;
}

.disabledInput {
    color: black;
}

 /* Tooltip container */
.tooltip {
   /* position: relative;*/
    display: inline-block;
    border-bottom: 1px dotted black; /* If you want dots under the hoverable text */
}

/* Tooltip text */
.tooltip .tooltiptext {
    visibility: hidden;
    width: 120px;
    background-color: #555;
    color: #fff;
    text-align: center;
    padding: 5px 0;
    border-radius: 6px;

    /* Position the tooltip text */
    position: absolute;
    z-index: 1;
    bottom: 125%;
    left: 50%;
    margin-left: -60px;

    /* Fade in tooltip */
    opacity: 0;
    transition: opacity 0.3s;
}

/* Tooltip arrow */
.tooltip .tooltiptext::after {
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #555 transparent transparent transparent;
}

/* Show the tooltip text when you mouse over the tooltip container */
.tooltip:hover .tooltiptext {
    visibility: visible;
    opacity: 1;
} 
</style>

<div class="container"> <!--style="padding-top:10px;"-->
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
     <form name="customersurvey" id="customersurvey" method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
      <div style="width:100%; height:auto;float:left; padding:0px 0px 10px 0px;">
	       <div class="row_container">
    	     <div class="row_left">Email Alias:</div>
              <div class="row_right">
              <input name="employee_name" type="text" id="employee_name" size="15" value="<?php echo $_SESSION['userId'];?>" readonly="readonly" onfocus="this.blur();" tabindex="-1" class="disabledInput"/>
              </div>

        <div class="row_left">Date:</div>
            <div class="row_right"><input name="curdate" type="text" id="curdate" size="15" value="<?php echo date('Y-m-d');?>" readonly="readonly" onfocus="this.blur();" tabindex="-1" class="disabledInput" />
        </div>
        
        <div class="row_left">Customer MSISDN:</div>
        <div class="row_right">
        <input name="msisdn_txt" type="text" id="msisdn_txt" size="15" readonly="readonly" class="disabledInput" maxlength="10" />
          <a id="getMSISDN" style="cursor:pointer;text-decoration:underline;">Get MSISDN</a> / 
          <a id="editMSISDN" style="cursor:pointer;text-decoration:underline;">Edit MSISDN</a><sup style="font-size=9px;">?</sup>
          <br><span id="msisdn_txt" class="error">Customer MSISDN is required</span>
        </div>

        <div class="row_left">Connection Type:</div>
        <div class="row_right">
          <div id="show_inp_con">
            <input name="con_type" type="text" id="con_type" size="15" readonly="readonly" tabindex="-1" class="disabledInput" />
          </div>

          <div id="show_st" style="display:none">
            <select name="con_edit" size="" id="con_edit">
              <option selected value="0">--Select--</option>
              <option value="Prepaid">Prepaid</option>
              <option value="Postpaid">Postpaid</option>
              <option value="CnC">CnC</option>
            </select>
           </div>

            <br><span id="con_type_r" class="error">Connection Type is required</span>
        </div>
          
           <?php
		        $gr	=	addslashes('<b>Opening  greetings:</b> Sir/Madam, This is <i>(name)</i> from Banglalink. How are you? May I have your time to get feedback about Banglalink connection? / আসসালামুয়ালাইকুম স্যার/ম্যাডাম, আমি বাংলালিংক থেকে <i>(নাম)</i> বলছি। কেমন আছেন? আপনার বাংলালিংক কানেকশন এর ব্যাপারে কিছু মতামত জানতে ফোন করেছি। আপনার কি সময় হবে কথা বলার?');
	         ?>
           <div class="row" style="color:#F26522"> <!--#FC915F-->
              <p style=" line-height: 1.6"><?php echo $gr;?> </p>    
           </div>
    
     <?php
		$q1	=	addslashes('Q1.Based on your experience with us, how satisfied are you with banglalink? / আপনি বাংলালিংক ব্যবহার করে কতটুকু সন্তুষ্ট?');
	?>
    
     <div class="row_left" style="width:100%; "> <?php echo $q1;?></div>
        <div class="row_left" style="width:100%;">

          <input type="radio" name="q1" value="Not at all satisfied"> Not at all satisfied<br/>
          <input type="radio" name="q1" value="Somewhat Satisfied"> Somewhat Satisfied<br/>
          <input type="radio" name="q1" value="Satisfied"> Satisfied<br/>
          <input type="radio" name="q1" value="Very Satisfied"> Very Satisfied<br/>

          <span id="anso" class="error">Please select an answer for question 1</span>
        </div>    
    
    <?php
		$q2	=	addslashes('Q2.2. Which thing do you like most? / কোন বিষয়টি আপনার সবচেয়ে ভালো লেগেছে?');
	?>
    <div id = q21 style="display:none">
     <div class="row_left" style="width:100%"> <?php echo $q2;?></div>
        <div class="row_left" style="width:100%;">

         <input type="checkbox" name="q2[]" value="Network"> Network <br/>
         <input type="checkbox" name="q2[]" value="Internet"> Internet <br/>
         <input type="checkbox" name="q2[]" value="Value added services"> Value added services <br/>
         <input type="checkbox" name="q2[]" value="Call rate & BL offers"> Call rate & BL offers <br/>
         <input type="checkbox" name="q2[]" value="Others"> Others (please specify) 
         <div id="q21_div" style="display: none"><input type="text" name=q21_o size="25">
         <span id="q21_o_e" class="error">Please provide Other options</span></div><br/>
         <span id="anst" class="error">Please select an answer for question 2.2</span>
        </div>
    </div>
    
    
    <?php
		$q3	=	addslashes('Q2.1. Can you please tell us which specific problem are you facing ? / অনুগ্রহ করে বলুন আপনি কী ধরনের সমস্যার সম্মুখীন হচ্ছেন?');
	?>
    <div id = q22 style="display:none">
     <div class="row_left" style="width:100%"> <?php echo $q3;?></div>
        <div class="row_right" style="width:100%;">
         <input type="checkbox" name="q3[]" value="Network"> Network Coverage <br/>
         <input type="checkbox" name="q3[]" value="Internet"> Internet Speed<br/>
         <input type="checkbox" name="q3[]" value="Value added services"> Value added services <br/>
         <input type="checkbox" name="q3[]" value="Call rate & BL offers"> Call rate & BL offers <br/>
         <input type="checkbox" name="q3[]" value="Others"> Others (please specify) 
         <div id="q22_div" style="display: none"><input type="text" name=q22_o size="25">
         <span id="q22_o_e" class="error">Please provide Other options</span></div><br/>
         <span id="ansth" class="error">Please select an answer for question 2.1</span>
        </div>        
    </div>

    <div class="row_left" style="width:100%">If customer has any feedback, first select below category and capture the detail remarks in the comment box at end of the call</div>
    <div class="row_right" style="width:100%;">
      <input type="radio" name="story" value="Suggestion/Advice"> Suggestion/Advice <br/>
      <input type="radio" name="story" value="Complaint"> Complaint
    </div>

    <!--<div class="row_right" style="width:100%;">-->
      <textarea id="txtstory" name="txtstory" cols="100" rows="8" maxlength="1000" ></textarea>
      <div id="textarea_feedback" style="font-size: 12px;font-style: italic;"></div>
    <!--</div>-->
    

    <?php
      $gr = addslashes('<b>Closing Statement:</b> Thank you for using Banglalink! / বাংলালিংক ব্যবহার করার জন্য আপনাকে ধন্যবাদ');
    ?>
    <div class="row">
        <p style="color:#F26522; line-height: 1.6;"><?php echo $gr;?></p>          
    </div>

    <div class="row_left" style="width:100%">If customer needs to get further callback from Customer care team please select below</div>
    <div class="row_right" style="width:100%;">
      <input type="checkbox" name="call_back" value="Yes"> Yes <br/>
      <!--<input type="radio" name="call_back" value="Yes"> Yes
      <input type="radio" name="call_back" value="No"> No-->
    </div>

    <div class="row_right" style="width:45%;">
      <input type="submit" name="submit" value="Save" class="btn btn-primary">
      <input type="reset" name="breset" id="breset" value="Cancel/Refresh" class="btn">
    </div>
</div>


<!--<div style="width:20%; height:auto; background-color:#eef8db; color:#003366; border:1px solid #a0ae86; float:right; padding:5px 5px 0px 10px;">
<p><b>If customer wants to register any complaint, please tick<tick sign> the box, and inform him we will call back the customer within 24 hrs</b>
<input type="checkbox" name="callback" value="Yes">
</p>
</div>-->
<!--<br>-->

<div style="float:right; height:auto; width:20%; padding: 0px 16px 12px 30px;"><b>Things to remember</b></div>

<div style="width:20%; height:auto; background-color:#ded3d3; color:#000; border:1px solid #aeb3a5; float:right; padding:5px 5px 0px 10px; margin-top:0px;">
<p>Let’s have a look at the below steps before starting the survey</p>
<marquee style="font-weight:bold; color:#F26522; font-size:14px;" scrollamount="5">Only two questions to be asked</marquee>
<p>
<b>Step 1</b>- first click “Get MSISDN” to get the mobile number<br/><br/>
<b>Step 2</b>- Start with greeting (you may follow the mentioned script)<br/><br/>
<b>Step 3</b>- If customer agree to talk, please ask question 1 and select level of satisfaction which will take you to another question<br/><br/>
<b>Step 4</b>- If customer does not agree to talk then provide closing statement and click again on “Get an MSISDN” button to get another number
</p>

<p><b>Some Tips</b></p>
<p>• Please call customers between <b>10:00 am to 6:00 pm only.</b></p>
<p>• Please use your office pool phone number to call the customers.</p>
<p>• For a better experience, please open the portal using Firefox/Chrome. Please speak to IT support if you do not this browser installed.</p>
<p>• Let’s be nice to the customer.</p>
</div>


</form>
 
</div>
<div class="foot_div">
  <p><strong>&copy; Digital Services & Solutions | DSS-AppDev | IT </strong></p>
</div>
 <?php //include 'footer.php'; ?>
<!-- /container --> 

<!-- Le javascript ================================================== --> 
<!-- Placed at the end of the document so the pages load faster -->
<?php include 'js_file.php'; ?>
</body>
</html>