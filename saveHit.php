<?php
session_start();
ini_set('session.gc_maxlifetime', 108000);
//set_time_limit(-30); 
//error_reporting(-1);

if(!isset($_SESSION['userId'])){
	$userId=$_SERVER['REMOTE_USER'];
	$userId=explode('\\',$userId);
	$userId=$userId[1];
	$_SESSION['userId']=$userId;
}


require_once 'DBConnection.php';
$dbc = new DBConnection();

$id = $_GET['id'];
$userId = $_SESSION['userId'];

if ((isset($id)) && !(is_null($id)))
{
	//echo 'user:'. $userId;
	$dbc->insertHit($id, $userId);
	return true;
}

/*
echo '<pre>';
print_r($arr);
echo '</pre>';
*/

?>