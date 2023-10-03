// JavaScript Document

function validate(){
		
		if(document.getElementById('name').value == ""){
			alert("Please Enter ID!!!");
			document.getElementById('name').focus();
			return false;
		}else if(document.getElementById('pass').value == ""){
			alert("Please Enter Password !!!");
			document.getElementById('pass').focus();
			return false;
		}
		return true;
	
	}  