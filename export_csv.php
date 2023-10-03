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


if(isset($_POST['btnsubmit']))
{

	$startdate=$_POST['StartDate'];
	$enddate=$_POST['EndDate'];
		
	$qry =	"SELECT employee_name as` Employee Name`,msisdn_txt as `Customer MSISDN`,con_type as `Connection Type`,quest_one_ans as `Question One`,quest_two_ans as `Question Two`,quest_two_others as `Others`,VOCdetailsStoryline,txtstory as `VOC Story`,survey_date as `Survey Date`,survey_time as `Survey Time`,callback as `Callback Needed?` FROM cust_survey WHERE survey_date BETWEEN '$startdate' AND '$enddate'";
}

//echo $qry; exit;
$rsSearchResults = mysql_query($qry) or die(mysql_error()); 
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
			$l=mysql_field_name($rsSearchResults, $i);
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


$filename = $file."_".$from.'to'.$to;

//application/download or application/force-download //Content-Type:  //text/csv
header('Content-type: application/octet-stream');
header('Content-Length: '.strlen($out));
//header("Content-type: application/vnd.ms-excel");
//header("Content-disposition: csv" . date("Y-m-d") . ".csv");
header('Pragma: no-cache');
header('Content-Disposition: attachment; filename=file.csv');


echo $out;
exit;

//header('Location:dumpreport.php');

?>
