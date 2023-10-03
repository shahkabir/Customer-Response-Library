<!-- Below scripts are for Datatable -->
<script type="text/javascript" src="assets/js/jquery-3.5.1.js"></script>
<script type="text/javascript" src="assets/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="assets/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="assets/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="assets/js/buttons.html5.min.js"></script>





<link rel="stylesheet" href="assets/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="assets/css/buttons.dataTables.min.css">


<!-- Below script are for Bootstrap -->
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
<!-- <script src="assets/js/custom.js"></script> -->

<!-- <link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen">
<script type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script> -->



<script type="text/javascript">
	

$(document).ready(function(){

	var table = $('#example').DataTable({

	  "bLengthChange": false,
	  "bAutoWidth": false,

      "ajax": {
      	"url": "populateData.php?utype=agent",
      	"dataSrc": ""
      },

      /*"columnDefs": [ {
            "targets": -1,
            "data": null,
            "defaultContent": "<button>Click!</button>"
        	} ],*/

      "columns": [
		    		{ data: "source"}, //"defaultContent": ""
				    { data: "category"},
				    { data: "subcategory"},
				    { data: "label"},
				    { data: "language"},
				    { data: "content"},
				    { data: null,
				      "targets" : -1,
				      "defaultContent": "<a href='#'>Copy</a>",
				      /"render": function(data,type,row,meta) { // render event defines the markup of the cell text //render
                             var a = '<a target="_blank" href="">COPY</a>'; // row object contains the row data
                            
                              //copyToClipboard(row.content);

                              //var copyText = row.content;//document.getElementById("myInput");

                              var fullLink = document.createElement('input');
    						  document.body.appendChild(fullLink);
							  fullLink.value = row.content;
							  /* Select the text field */
							  fullLink.select();
							  //copyText.setSelectionRange(0, 99999); /*For mobile devices*/

							  /* Copy the text inside the text field */
							  document.execCommand("copy",false);

							  /* Alert the copied text */
							  //alert("Copied the text: " + fullLink.value);

                              //return a;
                        }
				    }
    			 ],
           /*{
        
      },*/

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

      //var table = this.api();

	});

	function copyToClipboard(text) 
	{
	    var $temp = $("<input>");
	    $("body").append($temp);
	    $temp.val(text);
	    document.execCommand("copy",false);
	    alert(text);
	    $temp.remove();
	}	

	//var table = $('#example');

	//alert(table);
	//console.log(table);

	$('#example tbody').on( 'click', 'button', function () { //tbody
        var data = table.row( $(this).parents('tr') ).data();
        alert(data);
        alert( data[0] +"'s salary is: "+ data[ 4 ] );
    } );




    /*EDIT-DELETE TABLE*/

    var table = $('#edit-delete').DataTable({

      "bLengthChange": false,
	  "bAutoWidth": false,

      "ajax": {
      	"url": "populateData.php?utype=admin",
      	"dataSrc": ""
      },

      "dom" : 'Bfrtip',
      "buttons" : 
      //['csv'],
      [
	      {
	      		extend: 'csv', //csvHtml5
	   			charset: 'utf-8', //For Bangla
				text: 'Export to CSV',
				bom: true,//fieldSeparator: ';',
				filename: 'Canned_Messages'
	      }
      ],

      /*"columnDefs": [ {
            "targets": -1,
            "data": null,
            "defaultContent": "<button>Click!</button>"
        	} ],*/

      "columns": [
      				{ data: "Id"},
		    		{ data: "source"}, //"defaultContent": ""
				    { data: "category"},
				    { data: "subcategory"},
				    { data: "label"},
				    { data: "language"},
				    { data: "content"},
				    { data: "agentID"},
				    { data: "entryDate"},
				    { data: "publish"},
				    { data: "user"},
				    { data: "modifyDate"},
				    { data: null,
				      "targets" : -1,
				      "defaultContent": "<a href='#'>Edit</a>",
				      "render": function(data,type,row,meta) { // render event defines the markup of the cell text 
                            var a = '<a target="_blank" href="http://blccdmis01/dimelo_int/modify.php?id='+row.Id+'">EDIT</a>'; // row object contains the row data
                            return a;
                        } //http://blccdmis01/dimelo_int/modify.php?id=
				    }
    			 ],
           /*{
        
      },*/

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

	});

	$('#edit-delete tbody').on( 'click', 'button', function () { //tbody
        var data = table.row( $(this).parents('tr') ).data();
        alert(data);
        alert( data[0] +"'s salary is: "+ data[4] );
    } );

    /*$("a").on( "click", function( event ) {
	    event.preventDefault();
	    console.log( $( this ).text() );
	});*/

});


/*$(document).ready(function() {
    var table = $('#example').DataTable( {
        "ajax": "data/arrays.txt",
        "columnDefs": [ {
            "targets": -1,
            "data": null,
            "defaultContent": "<button>Click!</button>"
        } ]
    } );
 
    $('#example tbody').on( 'click', 'button', function () {
        var data = table.row( $(this).parents('tr') ).data();
        alert( data[0] +"'s salary is: "+ data[ 5 ] );
    } );
} );*/


    /*
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

	});*/

	/*
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
			*/

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
	//	});
	
/*
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

		
	});
	*/

//});

function chkStatus(v)
{
		var msisdnToChk = v;//$(this).val();//'1942533484'

		//alert(msisdnToChk);

		$.ajax({
			method: "GET",
			url: "validticket.php",
			data: {"msisdnToChk": "0"+msisdnToChk},
			success: function(response){
				var result = $.parseJSON(response);
				
				//alert(msisdnToChk);

				if(result['flag'] == 1)
				{
					alert('This ticket is locked by another user.');
				}else
				{
					// similar behavior as an HTTP redirect
					window.location.replace('processTicket.php?m=0'+msisdnToChk);//starting 0 is added

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

		});
}


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

function validSurvey(e)
{
	
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