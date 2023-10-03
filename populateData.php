<?php

require_once 'DBConnection.php';
$dbc = new DBConnection();



$utype= $_GET['utype'];

if ((isset($utype)) && !(is_null($utype)))
{

	if($utype == 'agent')
	{
		$arr = $dbc->fetchAll();		
	}else if ($utype == 'admin')
	{
		$arr = $dbc->fetchAllAdmin();
	}else{
		echo '[]';
	}
}

echo $arr;

/*
echo '<pre>';
print_r($arr);
echo '</pre>';
*/

?>