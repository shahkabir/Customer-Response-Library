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
		$result	=	mysql_query($sql) or die(mysql_error());
	
	
		$objPHPExcel = new PHPExcel();

		$objWorkSheet = $objPHPExcel->createSheet(0); //Setting index when creating
		//$objPHPExcel->getActiveSheet(0)->setTitle('Customer Survey Report');
		
		//Populate column names first
		for($j = 0; $j < mysql_num_fields($result); $j++) {
    		$field_info = mysql_field_name($result, $j);
			$objWorkSheet->setCellValueByColumnAndRow($j, 1, $field_info);
		}
		
		$row = 2; //First row is for column names

		//Then populate data
		while($row_data = mysql_fetch_assoc($result)){
			$col = 0;
			foreach($row_data as $key=>$value) {
				//$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);
				$objWorkSheet->setCellValueByColumnAndRow($col, $row, addslashes($value));
				$col++;
			}
			$row++;
		}
		
		
//$filename = 'WMS_Workcode_dump_'.$rptDate.'.xlsx';
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
						
//$sp = $_SERVER['DOCUMENT_ROOT'].'\wms/all_workcode_dump/download/';

//echo $sp;

//$objWriter->save($sp.$filename);


//$objPHPExcel->setActiveSheetIndex(0);
$filename = 'survey_report'.'.xlsx';
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

$sp = $_SERVER['DOCUMENT_ROOT'].'/voc/'.'report_upload/';

$objWriter->save($sp.$filename);

//echo $sp.$filename;
//exit;

ob_get_clean();
$objWriter->save('php://output');
ob_end_flush();

$fname = $filename;
$file = 'report_upload/'.$fname;

if (file_exists($file)) {
	//echo 'here';

    header('Content-Description: File Transfer');
	header("Content-Type: application/force-download");
    header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename='.Urlencode(basename($file)));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($sp.$filename));
    ob_clean();
    flush();
    readfile($sp.$filename); //readfile($file);
    exit;
}

header('Location:dumpreport.php');
}

?>