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

$sql	=	"SELECT msisdn, contype FROM msisdnlist where in_use='0' AND statusd='0' ORDER BY RAND() LIMIT 0,1";

$exec	=	mysql_query($sql);

$row	=	mysql_fetch_array($exec);

$msisdn = $row['msisdn'];

$sql_up = "UPDATE msisdnlist SET in_use='1' where msisdn='".$msisdn."'";
$exec_up=	mysql_query($sql_up);

$json_array = array(
			  "msisdn" => $row['msisdn'],
			  "contype" =>$row['contype']
		  );

echo json_encode($json_array);//return the JSON Array
//echo $msisdn;
 //echo json_encode(array('success'=>TRUE,'message'=>"Article deleted"));
?>
