<?php
session_start();
ini_set('session.gc_maxlifetime', 108000);
include("custom_users.php");

if(!isset($_SESSION['userId']))
{
  $userId=$_SERVER['REMOTE_USER'];
  $userId=explode('\\',$userId);
  $userId=$userId[1];
  $_SESSION['userId']=$userId;
}
//print_r($_SESSION);
$userId = $_SESSION['userId'];
//exit;
?>

<div class="container">
  <ul class="nav nav-pills">
    <li><a href="index.php">Messages</a></li>
    <?php
      if(in_array($userId, $admins))
      {
    ?>
    <li class="nav-pills dropdown"> <a class="dropdown-toggle" data-toggle="dropdown" href="#">Admin  
      <b class="caret"></b></a>
      <ul class="dropdown-menu">
          <li><a href="create.php">Add New Message</a></li>
          <li><a href="edit.php">Update/Delete/Export</a></li>
          <!-- <li><a href="report_callbackByuser.php">Callback by user</a></li>
          <li><a href="report_question1.php">Q1 category wise</a></li>
          <li><a href="report_question21.php">Q2 category wise</a></li> -->
      </ul>
    </li>

    <li class="nav-pills dropdown"> <a class="dropdown-toggle" data-toggle="dropdown" href="#">Reports  
      <b class="caret"></b></a>
      <ul class="dropdown-menu">
          <li><a href="reportHitCount.php">Message Hit Count</a></li>
      </ul>
    </li>
    <li><a href="dimelo_int.php">Dimelo Interactions</a></li>
  <?php } ?>
    <li style="float: right;"><a>Welcome <?php echo $_SESSION['userId'];?>!</a></li>
  </ul>
</div>