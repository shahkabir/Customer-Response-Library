<?php
ini_set('session.gc_maxlifetime', 108000);
session_start(); 
include("DBConnection.php");

if (isset($_POST['userName']) && isset($_POST['pass'])) 
{
		
		$userid = $_POST['userName'];
		$password = $_POST['pass'];

// check if the user id and password combination exist in database
	     $sql = "SELECT user_id,password,access_level,full_name
           		FROM tbl_user
           		WHERE user_id = '$userid' 
				AND `password` = '$password'";
				
			echo $sql;
//exit;
   		$result = mysqli_query($conn,$sql) or die('Query failed. ' . mysql_error());
			  
    	//$accessLevel=mysql_result($result,0,"access_level");
	
		if (mysqli_num_rows($result) == 1) 
   		{ 
      // the user id and password match, 
      // set the session
	  	$row=mysqli_fetch_array($result);
		
			//echo $row['user_id'];
		
		
	  		$userid				=		$row["user_id"];
	  		$accessLevel		=		$row["access_level"];
			$fullname			=		$row["full_name"];
	      	        
	  			$_SESSION['uid']=$userid;
	  			$_SESSION['access']=$accessLevel;
				$_SESSION['fullname']=$fullname;
				
	 // print_r($_SESSION);
	  			if($accessLevel=="0" || $accessLevel=="1" || $accessLevel=="3")
				{	
  	    			$redirectLoc="Location: reportPage.php";
					header($redirectLoc);
					exit;
	  			}	
	   			else if($accessLevel=="2") 
				{	
  	    			$redirectLoc="Location: reportPage.php";
					header($redirectLoc);
					exit;
	  			}	
				
	     } 
   		else {
      			//$errorMessage = 'Sorry, wrong user id / password';
				$redirectLoc="Location: index.php?login=failure";
		        header($redirectLoc);
   			}
 }
 
 


?>
