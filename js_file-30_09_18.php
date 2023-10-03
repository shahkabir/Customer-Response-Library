<script src="assets/js/jquery.js"></script>
<script src="assets/js/bootstrap-transition.js"></script>
<script src="assets/js/bootstrap-alert.js"></script>
<script src="assets/js/bootstrap-modal.js"></script>
<script src="assets/js/bootstrap-dropdown.js"></script>
<script src="assets/js/bootstrap-scrollspy.js"></script>
<script src="assets/js/bootstrap-tab.js"></script>
<script src="assets/js/bootstrap-tooltip.js"></script>
<script src="assets/js/bootstrap-popover.js"></script>
<script src="assets/js/bootstrap-button.js"></script>
<script src="assets/js/bootstrap-collapse.js"></script>
<script src="assets/js/bootstrap-carousel.js"></script>
<script src="assets/js/bootstrap-typeahead.js"></script>
<script src="assets/js/custom.js"></script>
<link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen">
<script type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script language="javascript" type="text/javascript">
$(document).ready(function() {

var maxT = 1000;
$('#textarea_feedback').html(maxT + ' characters remaining');

$('#txtstory').keyup(function(){
	var textL = $(this).val().length;
	var textR = maxT - textL;

	$('#textarea_feedback').html(textR + ' characters remaining');
});

$('#breset').click(function() {
    location.reload(true);
    $(window).scrollTop(0);
});

	$("#editMSISDN").hover(function() {
        $(this).css('cursor','pointer').attr('title', 'Please click here to input number manually, if customer called back.');
    }, function() {
        $(this).css('cursor','auto');
    });

    $("#close").click(function(){
    	if(confirm("Are you sure you want to close this page without saving?"))
		{
		    //Ok button pressed...
		    window.close();
		}
		else
		{
    	//Cancel button pressed...
		}

	});

		 $('#customersurvey').submit(validSurvey);

		 $('#feedbackForm').submit(validFeedback);

		 $('.status_message').delay(6000).fadeOut('slow');

		 $("#getMSISDN").click(function(){

		 	$.getJSON("http://blccdmis01/voc/getmsisdn.php",
		 		function(data)
		 		{
		 			if((data['msisdn']) != null)
		 			{
		 				$.each(data, function(i, item){
		 					$("#msisdn_txt").val(data['msisdn']);
							$("#con_type").val(data['contype']);
		 				});
		 			}
		 			else
		 			{
		 				alert('There are no msisdn left for survey! \nPlease contact concern person.');
		 			}
		 		});

		 	$('#msisdn_txt').addClass('disabledInput');
			$('#msisdn_txt').prop('readonly', true);

			$('#show_inp_con').css("display","block");
			$('#show_st').css("display","none");

		 	/*$.ajax({
				url:"getmsisdn.php",
				success:function(data){
					 alert(data);
					 if(data.length>=1)					
					 $('#msisdn_txt').val(data);

					 if(data.length<=0)
					 alert('There are no msisdn left for survey!!');
				}
			});*/
		});

		$("#editMSISDN").click(function(){
			//alert('test');
			$('#msisdn_txt').removeClass('disabledInput');
			$('#msisdn_txt').prop('readonly', false);//'#prqitms_purcatalogd'
			$('#msisdn_txt').val('');
			$('#msisdn_txt').focus();

			$('#show_inp_con').css("display","none");
			$('#show_st').css("display","block");

			$('#con_type').val('');

		});

		$('input[name="q1"]').click(function()
		{
			var q1 = $.trim($(this).val());

			if( q1 == 'Satisfied' || q1 == 'Very Satisfied') {
				$("#q22").hide(500);
				$("#q21").delay(200).show(1000);
			}

			if( q1 == 'Not at all satisfied' || q1 == 'Somewhat Satisfied') {
				$("#q21").hide(500);
				$("#q22").delay(200).show(1000);
			}
		});


		$("input[name='q2[]']").click(function() {
			//var id_t = $("#q2 option:selected").text();
			var id_t = $(this).val();
			//alert(id_t);

			if(id_t == 'Others')
				$("#q21_div").show(500);
			//else
			//	$("#q21_div").hide(500);
		});


		$("input[name='q3[]']").click(function() {
			//var id_t = $("#q2 option:selected").text();
			var id_t = $(this).val();
			//alert(id_t);

			if(id_t == 'Others')
				$("#q22_div").show(500);
			//else
			//	$("#q22_div").hide(500);
		});



	$('.chkStatus').click(function(){

		//var msisdnToChk = $('.chkStatus').val();

		var msisdnToChk = $(this).val();

		alert(msisdnToChk);

		$.ajax({
			method: "GET",
			url: "validticket.php",
			data: {"msisdnToChk": msisdnToChk},
			success: function(response){
				var result = $.parseJSON(response);
				//alert(result['flag']);
				if(result['flag'] == 1)
				{
					alert('This ticket is locked by another user.');
				}else
				{
					// similar behavior as an HTTP redirect
					window.location.replace('processTicket.php?m='+msisdnToChk);

					// similar behavior as clicking on a link
					//window.location.href = "http://stackoverflow.com";

					//var _href = $("a.chkStatus").attr("href");
					//$("a.chkStatus").attr("href", 'processTicket.php?m='+msisdnToChk);

					//$(this).attr("href", 'processTicket.php?m='+msisdnToChk);
				}
			},
			error: function(handle){
				 alert('There was problem fetching data, please contact concern person.');
			}

		})
	});



});


function validFeedback(e)
{
	var isValid = true;

	if($('#f_status').val() == 0){
		isValid = false;
		if(isValid==false){
					$('span#st_id').removeClass("error");
					$('span#st_id').addClass("error_show");
				}
	}else{
					$('span#st_id').removeClass("error_show");
					$('span#st_id').addClass("error");
	}

	//alert('before final return:'+isValid);
	//isValid = false;
	return isValid;
}

function validSurvey(e){
	
	//alert('here');
		var isValid = true;
		 
		 /*
		 $('input[name="employee_phone"]').each(function() {
			
				//alert('here');
				if ($.trim($(this).val()) == '') {
					//alert('here inside empty');
					
					isValid = false;
						$(this).css({
							"border": "1px solid red",
							"background": "#FFCECE"
						});
						
						if(isValid==false){
							//alert(isValid);
							$('span#employee_phone').removeClass("error");
							$('span#employee_phone').addClass("error_show");
					
					}
				}
				else {
						$(this).css({
							"border": "",
							"background": ""
						});
						$('span#employee_phone').removeClass("error_show");
						$('span#employee_phone').addClass("error");
            }
		
		});*/
		
		
		//alert($('input[name=q1]:checked').length);
		if($('input[name=q1]:checked').length==0){
				isValid = false;
				
				if(isValid==false){
					$('span#anso').removeClass("error");
					$('span#anso').addClass("error_show");
					
				}
				else{
					$('span#anso').removeClass("error_show");
					$('span#anso').addClass("error");
					
				}
		}

		if($('input[name=q1]:checked').length>0){
			
			isValid = true;
			
			if(isValid==true){
					$('span#anso').removeClass("error_show");
					$('span#anso').addClass("error");
					
				}
		}
		
		//$('input[name="q1"]').click(function()
		//{
			var q1 = $('input[name=q1]:checked').val();

			//alert(q1);

			if(q1 == 'Satisfied' || q1 == 'Very Satisfied') {

				if($("input[name='q2[]']:checked").length==0){
						isValid = false;
						if(isValid==false){
							$('span#anst').removeClass("error");
							$('span#anst').addClass("error_show");
						}
						else
						{
							$('span#anst').removeClass("error_show");
							$('span#anst').addClass("error");
						}	
				}
		
		
				if($("input[name='q2[]']:checked").length>0){
					isValid = true;
					if(isValid==true){
							$('span#anst').removeClass("error_show");
							$('span#anst').addClass("error");
						}
				}

				$("input[name='q2[]']:checked").each(function(){
					//alert(this.value);
					if(this.value == 'Others')
					{
						$('input[name="q21_o"]').each(function() {
			
						//alert('here');
						if ($.trim($(this).val()) == '') {
							//alert('here inside empty');
							
							isValid = false;
								$(this).css({
									"border": "1px solid red",
									"background": "#FFCECE"
								});
								
								if(isValid==false){
									//alert(isValid);
									$('span#q21_o_e').removeClass("error");
									$('span#q21_o_e').addClass("error_show");
							
							}
						}else{
								$(this).css({
									"border": "",
									"background": ""
								});
								$('span#q21_o_e').removeClass("error_show");
								$('span#q21_o_e').addClass("error");
				            }
						
						});
					}
				});
			}
			
			if( q1 == 'Not at all satisfied' || q1 == 'Somewhat Satisfied') {

				if($("input[name='q3[]']:checked").length==0){
						isValid = false;
						
						if(isValid==false){
							$('span#ansth').removeClass("error");
							$('span#ansth').addClass("error_show");
						}
						else{
							$('span#ansth').removeClass("error_show");
							$('span#ansth').addClass("error");
						}	
				}
			
				if($("input[name='q3[]']:checked").length>0){
					
					isValid = true;
					
					if(isValid==true){
							$('span#ansth').removeClass("error_show");
							$('span#ansth').addClass("error");
						}
				}

				$("input[name='q3[]']:checked").each(function(){
					//alert(this.value);
					if(this.value == 'Others')
					{
						$('input[name="q22_o"]').each(function() {
			
						//alert('here');
						if ($.trim($(this).val()) == '') {
							//alert('here inside empty');
							
							isValid = false;
								$(this).css({
									"border": "1px solid red",
									"background": "#FFCECE"
								});
								
								if(isValid==false){
									//alert(isValid);
									$('span#q22_o_e').removeClass("error");
									$('span#q22_o_e').addClass("error_show");
							
							}
						}else{
								$(this).css({
									"border": "",
									"background": ""
								});
								$('span#q22_o_e').removeClass("error_show");
								$('span#q22_o_e').addClass("error");
				            }
						
						});
					}
				})
			}

			$('input[name="msisdn_txt"]').each(function() {
			
				//alert('here');
				if ($.trim($(this).val()) == '') {
					
					isValid = false;
					
						$(this).css({
							"border": "1px solid red",
							"background": "#FFCECE"
						});
						
						if(isValid==false){
							//alert(isValid);
							$('span#msisdn_txt').removeClass("error");
							$('span#msisdn_txt').addClass("error_show");
					}
				}
				else {
						$(this).css({
							"border": "",
							"background": ""
						});

						$('span#msisdn_txt').removeClass("error_show");
						$('span#msisdn_txt').addClass("error");
            }
		
		});
		//}
		
		/*$('textarea[name="comntq4"]').each(function() {
			 if ($.trim($(this).val()) == '') {
				isValid = false;
				
				$(this).css({
                    "border": "1px solid red",
                    "background": "#FFCECE"
                });
				if(isValid==false){
					$('span#ansf').removeClass("error");
					$('span#ansf').addClass("error_show");
					
				}
			 }
			else{
					$(this).css({
                    	"border": "",
                   	 	"background": ""
                	});
					
					$('span#ansf').removeClass("error_show");
					$('span#ansf').addClass("error");
				}
		});*/
		
		//alert('before return: '+isValid);
		
		//return false;
		return isValid;	
}


</script>
