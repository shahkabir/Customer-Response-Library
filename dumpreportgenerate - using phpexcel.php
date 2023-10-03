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

		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet(0)->SetCellValue('A'.'1', 'Employee Name');
		$objPHPExcel->getActiveSheet(0)->SetCellValue('B'.'1', 'Customer MSISDN'); 
		$objPHPExcel->getActiveSheet(0)->SetCellValue('C'.'1', 'Connection Type');
		$objPHPExcel->getActiveSheet(0)->SetCellValue('D'.'1', 'Question One');
		$objPHPExcel->getActiveSheet(0)->SetCellValue('E'.'1', 'Question Two');
		$objPHPExcel->getActiveSheet(0)->SetCellValue('F'.'1', 'Others');
		$objPHPExcel->getActiveSheet(0)->SetCellValue('G'.'1', 'VOCdetailsStoryline');
		$objPHPExcel->getActiveSheet(0)->SetCellValue('H'.'1', 'VOC Story');
		$objPHPExcel->getActiveSheet(0)->SetCellValue('I'.'1', 'Callback Needed?');
		$objPHPExcel->getActiveSheet(0)->SetCellValue('J'.'1', 'Survey Date');
		$objPHPExcel->getActiveSheet(0)->SetCellValue('K'.'1', 'Survey Time');


		$objPHPExcel->getActiveSheet(0)->getColumnDimension('A')->setWidth(10);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('B')->setWidth(10);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('C')->setWidth(10);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('D')->setWidth(10);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('E')->setWidth(10);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('F')->setWidth(10);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('G')->setWidth(10);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('H')->setWidth(10);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('I')->setWidth(10);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('J')->setWidth(10);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('K')->setWidth(10);


		$objPHPExcel->getActiveSheet(0)->getStyle('A'.'1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet(0)->getStyle('B'.'1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet(0)->getStyle('C'.'1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet(0)->getStyle('D'.'1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet(0)->getStyle('E'.'1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet(0)->getStyle('F'.'1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet(0)->getStyle('G'.'1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet(0)->getStyle('H'.'1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet(0)->getStyle('I'.'1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet(0)->getStyle('J'.'1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet(0)->getStyle('K'.'1')->getFont()->setBold(true);


		$objPHPExcel->getActiveSheet(0)->setTitle('Customer Survey Report');

		$objPHPExcel->setActiveSheetIndex(0);
		$row = 2; 
		$i=1;
		 while($row_data = mysql_fetch_array($result)) 
		 {
				$i=$i+1;
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$row_data['Employee Name']);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$i,$row_data['Customer MSISDN']);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$i,$row_data['Connection Type']);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$i,$row_data['Question One']);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$i,addslashes($row_data['Question Two']));
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$i,addslashes($row_data['Others']));
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$i,addslashes($row_data['VOCdetailsStoryline']));
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$i,addslashes($row_data['VOC Story']));
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$i,$row_data['Callback Needed?']);
				$objPHPExcel->getActiveSheet()->setCellValue('J'.$i,$row_data['Survey Date']);
				$objPHPExcel->getActiveSheet()->setCellValue('K'.$i,$row_data['Survey Time']);
			}
			
		$objPHPExcel->setActiveSheetIndex(0);
		$filename = 'survey_report'.'.xlsx';
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		ob_get_clean();
		$objWriter->save('php://output');
		ob_end_flush();

		$objWriter->save('report_upload/'.$filename);
		//	$objWriter->save('php://output');

		$fname = $filename;
		$file = 'report_upload/'.$fname;

		if (file_exists($file)) {
			    header('Content-Description: File Transfer');
				//header("Content-Type: application/force-download");
			    //header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename='.Urlencode(basename($file)));
			   // header('Content-Transfer-Encoding: binary');
			    //header('Expires: 0');
			   // header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			    //header('Pragma: public');
			    //header('Content-Length: ' . filesize($file));
			    //ob_clean();
			    //flush();
			    //readfile($file);
			    
			    echo $file;
			    exit;
		}



		header('Location:dumpreport.php');
}

?>