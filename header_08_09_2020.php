<?php 
session_start(); 
//print_r($_SESSION);

if(!isset($_SESSION['userId'])){
  $userId=$_SERVER['REMOTE_USER'];
  $userId=explode('\\',$userId);
  $userId=$userId[1];
  $_SESSION['userId']=$userId;


require_once 'DBConnection.php';

$dbc = new DBConnection();

$arr = $dbc->fetchAll();

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>::Customer Feedback::</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="assets/css/bootstrap.css" rel="stylesheet">
<style type="text/css">
body {
	padding-bottom: 40px;
}

a{
	font-weight: bold;
}

.subscript
{
	text-align: right;
}
</style>
<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">

<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="assets/js/html5shiv.js"></script>
    <![endif]-->

<!-- Fav and touch icons -->
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
<!--<link rel="shortcut icon" href="assets/ico/favicon.png">-->

<!-- Below scripts are for Datatable -->
<script type="text/javascript" src="assets/js/jquery-3.5.1.js"></script>
<script type="text/javascript" src="assets/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="assets/css/jquery.dataTables.min.css">

<script type="text/javascript">
$(document).ready(function() {
    $('#example').DataTable({

      "ajax":{
        "url" : "<?php $arr;?>",
      },

      "columnDefs": [ {
            "targets": -1,
            "data": null,
            "defaultContent": "<button>Click!</button>"
        } ],

      initComplete: function(){
        this.api().columns([1,2]).every(function(){

          var column = this;
          var select = $('<select><option value="">-Select-</option></select>')
              .appendTo($(column.header()).empty())
              .on('change',function(){
                    var val = $.fn.dataTable.util.escapeRegex(
                          $(this).val()
                      );

                    column
                    .search( val ? '^'+val+'$' : '', true, false)
                    .draw();
              });

                column.data().unique().sort().each (function (d,j){

                select.append('<option value="'+d+'">'+d+'</option>');

              });
          });
        }

        // "columnDefs": [ {
        //     "targets": -1,
        //     "data": null,
        //     "defaultContent": "<button>Click!</button>"
        // } ];

    });

    // var table = $('#example').DataTable( {
    //     // "ajax": "data/arrays.txt",
    //     "columnDefs": [ {
    //         "targets": -1,
    //         "data": null,
    //         "defaultContent": "<button>Click!</button>"
    //     } ]
    // } );

});
</script>

</head>

<?php 
include 'DBConnection.php';
?>
<body>
<?php 
$br = $_SERVER['HTTP_USER_AGENT'];
$ie = strpos($br,'.NET');
if($ie>0)
{
?>
	<div style="border:1px solid #f5d000; text-align:center; font: bold 20px Arial, serif;"><p>Please use Firefox or Chrome browser for best performance. Thank you.</p>
	<p>http://blccdmis01/voc/</p></div>
<?php
exit;
}
?>
<?php

  /*$sql  = "SELECT COUNT(*) AS TOTCNT FROM cust_survey WHERE employee_name='".$_SESSION['userId']."'";
  $exec = mysql_query($sql) or die(mysql_error());
  $rows = mysql_fetch_array($exec);
  $ton  = $rows['TOTCNT'];
  */
  
  /*$sqlT = "SELECT employee_name,COUNT(msisdn_txt) AS TOTCNTM FROM cust_survey
        GROUP BY employee_name
        ORDER BY TOTCNTM DESC LIMIT 1";
        
  $execT  = mysql_query($sqlT) or die(mysql_error());     
  $rost = mysql_fetch_array($execT);*/

?>
<div class="container">
  <div class="head_div">
    <div class="head_logo"></div>
    <div class="head_title">
      <h3 class="muted" style="font-size: 30px; color:#fff;">GET CLOSER TO CUSTOMER</h3>
      <h6 class="subscript">Dear <?php echo $_SESSION['userId'];?>, so far you talked with <?php echo $ton;?> customers. Keep it up..</h6>
    </div>
  </div>
</div>
<?php include 'nav.php'; ?>