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

include 'DBConnection.php';


$msisdnToChk = $_GET['msisdnToChk'];

//if flag is 1 then locked, else 0 for not locked
$sql	=	"select if(now() < locked_time_limit,1,0) as flag from cust_survey where msisdn_txt = '$msisdnToChk'";

$exec	=	mysql_query($sql);

$row	=	mysql_fetch_array($exec);

$msisdn = $row['flag'];


$json_array = array(
			  "flag" => $row['flag']
		  );

echo json_encode($json_array);//return the JSON Array
//echo $msisdn;
 //echo json_encode(array('success'=>TRUE,'message'=>"Article deleted"));
?>
