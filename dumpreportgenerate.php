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
<?php

if(isset($_POST['btnsubmit']))
{
		require_once 'Classes/PHPExcel/IOFactory.php';
		require_once 'Classes/PHPExcel.php';

		$startdate=$_POST['StartDate'];
		$enddate=$_POST['EndDate'];
		
		$sql =	"SELECT employee_name as` Employee Name`,msisdn_txt as `Customer MSISDN`,con_type as `Connection Type`,quest_one_ans as `Question One`,quest_two_ans as `Question Two`,quest_two_others as `Others`,VOCdetailsStoryline,txtstory as `VOC Story`,survey_date as `Survey Date`,survey_time as `Survey Time`,callback as `Callback Needed?` FROM cust_survey WHERE survey_date BETWEEN '$startdate' AND '$enddate'";
		
		//echo $sql;
		//exit;
		//$result	=	mysql_query($sql) or die(mysql_error());
	
	
		$rsSearchResults = mysql_query($sql) or die(mysql_error()); 

		if(mysql_num_rows($rsSearchResults)<1)
		{
			echo "<br><b>No Data Found</b>";
			exit;
		}
	
	
		$out = '';	
		
		$columns = mysql_num_fields($rsSearchResults);
	       
		 // Put the name of all fields	
			for ($i = 0; $i < $columns; $i++) 
			{	
				$l = mysql_field_name($rsSearchResults, $i);
				$out .= '"'.$l.'",';	
			}	

			$out .="\n"; 	
	        // Add all values in the table	
			while ($l = mysql_fetch_array($rsSearchResults)) 
			{	
				for ($i = 0; $i < $columns; $i++) 
				{					
					$out .='"'.$l["$i"].'",';	
					
				}	
				$out .="\n";	
			}	


			$filename = 'survey_report';
			header("Content-type: text/x-csv");
			//header("Content-type: application/vnd.ms-excel");
			//header("Content-disposition: csv" . date("Y-m-d") . ".csv");
			header( "Content-disposition: attachment; filename=".$filename.".csv");

			//header('Location:dumpreport.php');
}

echo $out;
exit;

?>