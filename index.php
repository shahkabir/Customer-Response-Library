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

require_once 'DBConnection.php';

$dbc = new DBConnection();

$arr = $dbc->fetchAll();

/*echo '<pre>';
print_r($arr);
echo '</pre>';
exit;*/

?>

<!-- <script type="text/javascript" src="js_file.js"></script> -->

<?php

if(isset($_POST['submit']))
{
	
}

?>
	
<style type="text/css">


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
     
	 <table id="example" class="display" style="width:100%">
        <thead>
            <tr style="border-bottom-color: #FFF;">
                <th>Sl</th>
                <th>SOURCE</th>
                <th>CATEGORY</th>
                <th>SUB CATEGORY</th>
                <th>LABEL</th>
                <th>LANGUAGE</th>
                <th>CONTENT</th>
                <th>ACTION</th>
            </tr>

            <tr style="border-bottom-color: #FFF;">
                <th></th>
                <th>Source</th>
                <th>Category</th>
                <th>Sub Category</th>
                <th>Label</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>

        </thead>
		
		 <tfoot>
            <tr>
                <th>Sl</th>
                <th>SOURCE</th>
                <th>CATEGORY</th>
                <th>SUB CATEGORY</th>
                <th>LABEL</th>
                <th>LANGUAGE</th>
                <th>CONTENT</th>
                <th>ACTION</th>
            </tr>
        </tfoot>
    </table>
 
</div>
<div class="foot_div">
  <p><strong>&copy; Digital Services & Solutions | DSS-AppDev</strong></p>
</div>
 <?php //include 'footer.php'; ?>
<!-- /container --> 

<!-- Le javascript ================================================== --> 
<!-- Placed at the end of the document so the pages load faster -->
<?php include 'js_file.php'; ?>
</body>
</html>