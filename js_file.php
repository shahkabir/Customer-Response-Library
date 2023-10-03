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

<style type="text/css">
table #example, #edit-delete
{
  margin: 0 auto;
  width: 100%;
  clear: both;
  border-collapse: collapse;
  table-layout: fixed; 
  word-wrap:break-word;
}

/*.copyClass
{
	margin-right: 5px;
	padding: 5px 10px;
	font-size: 11px;
	line-height: 20px;
	background-color: #00a65a;
	border-color: #008d4c;
	color: #fff;
	display: inline-block;
padding: 6px 12px;
margin-bottom: 0;
font-size: 14px;
font-weight: 400;
line-height: 1.42857143;
text-align: center;
white-space: nowrap;
vertical-align: middle;
-ms-touch-action: manipulation;
touch-action: manipulation;
cursor: pointer;
-webkit-user-select: none;
-moz-user-select: none;
-ms-user-select: none;
user-select: none;
background-image: none;
border: 1px solid transparent;
border-radius: 4px;
}*/
</style>


<script type="text/javascript">
	

$(document).ready(function(){

	var table = $('#example').DataTable({

	  "order": false,
	  "bLengthChange": false,
	  "bAutoWidth": false,

      "ajax": {
      	"url": "populateData.php?utype=agent",
      	"dataSrc": ""
      },

      "columnDefs": [ 
      		{"width": "5px", "targets": 0},
      		{"width": "10px", "targets": 1},
      		{"width": "10px", "targets": 2},
      		{"width": "10px", "targets": 3},
      		{"width": "10px", "targets": 4},
      		{"width": "10px", "targets": 5},
      		{"width": "350px", "targets": 6},
      		{"width": "5px", "targets": 7},
	      		{
		      		"targets": "_all",
		      		"orderable": false
	      		}

      		// "visible": false,
            // "searchable": false
       ],

      "columns": [
      				{ data: "Id"},
		    		{ data: "source"}, //"defaultContent": ""
				    { data: "category"},
				    { data: "subcategory"},
				    { data: "label"},
				    { data: "language"},
				    { data: "content"},
				    { data: null,
				      "targets" : -1,
				      "defaultContent": "<button class='copyClass'>Copy</button>"
				    }
    			 ],
           /*{
        
      },*/

      initComplete: function(){
          this.api().columns([1,2,3,4]).every(function(){

	          var column = this;

	          var select = $('<select style="width:115px"><option value="">'+'-select-'+'</option></select>')
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
        	})

          /*this.api().columns([1,2,3,4]).every(function () {
                
                var that = this;
 
                $('input', this.header()).on('keyup change clear', function(){
                    if (that.search() !== this.value) {
                        that.search(this.value).draw();
                    }
                });
            });
			*/
      }


        /*initComplete: function() {
            // Apply the search
            
        }*/
    

      //var table = this.api();

	});

	/*$('#example thead th.forText').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="'+title+'" />' );
    } );*/

	// Run function for each tbody tr
	$("#example tbody").on('click','button.copyClass', function() {

	  var tr = $(this).closest('tr');
	  var tds = tr.find('td');
	  //console.log(tds);
      var currText = tds.eq(6).text();
      //var currText = tds.eq(6).firstChild();
      var messageID = tds.eq(0).text();

      //alert(messageID);
      //Copy the text to clipboard
      copyToClipboard(currText);


      //Now call AJAX to save hit count
      $.ajax({
      	type: 'POST',
      	url: 'saveHit.php?id='+messageID,
      	success: function(data)
      	{
      		//alert(data);
      	}

      });

      //console.log(tds);
	  //console.log(currText);

      //var currTexthtml = tds.eq(5).innerHTML();
      //var row = table.row(tr);
	  //var row = table.row($(this).closest('tr'));
	  //console.log(currTexthtml);
	  //console.log(tr);
	  //console.log(row);
	  // Within tr we find the last td child element and get content
	  //console.log(($(this).find("td:first-child").text()));
	});

	/*$('#example tbody').on( 'click', 'td', function () {
		console.log(table.cell($(this).data()));
		var v = table.cell($(this).data());
    	alert(typeof(v));
	} );*/

	/*$('#example tbody').on( 'click', 'button', function (e) { //tbody

		

		//table.ajax.reload();
        //var t1 = e.parentNode('tr');
        //var t1 = table.row(5).data();//this.parents('tr')
        //console.log(t1[0].innerHTML);
        //var d1 = t1.data();
        //console.log(d1);
        //alert(typeof(data));

        //console.log(table.api());

        //alert( d1[0] +"'s salary is: "+ d1[1] );
    } );*/

	function copyToClipboard(text) 
	{
	    var temp = $("<textarea>");
	    $("body").append(temp);
	    temp.val(text).select();
	    //$temp.val($(element).text()).select();
	    document.execCommand("copy");//false
	    //alert(text);
	    temp.remove();
	}	



    /*EDIT-DELETE TABLE*/

    var table_edit = $('#edit-delete').DataTable({

      "order": false,
      "bLengthChange": false,
	  "bAutoWidth": false,

      "ajax": {
      	"url": "populateData.php?utype=admin",
      	"dataSrc": ""
      },

      "dom" : 'Bfrtip',
      "buttons" : 
      [
	      {
	      		extend: 'csv', //csvHtml5
	   			charset: 'utf-8', //For Bangla
				text: 'Export to CSV',
				bom: true,//fieldSeparator: ';',
				filename: 'Canned_Messages'
	      }
      ],

      /*columnDefs: [
            { width: 100, targets: 0 }
      ],*/

       "columnDefs": [ 
      		{"width": "5px", "targets": 0},
      		{"width": "20px", "targets": 1},
      		{"width": "20px", "targets": 2},
      		{"width": "20px", "targets": 3},
      		{"width": "20px", "targets": 4},
      		{"width": "10px", "targets": 5},
      		{"width": "80px", "targets": 6},
      		{"width": "6px", "targets": 7},
      		{"width": "6px", "targets": 8},
      		{"width": "6px", "targets": 9},
      		{"width": "6px", "targets": 10},
      		{"width": "6px", "targets": 11},
      		{"width": "6px", "targets": 12},
      		{"width": "6px", "targets": 13},
      		{
	      		"targets": "_all",
	      		"orderable": false
      		}

      		// "visible": false,
            // "searchable": false
       ],

      //fixedColumns: true,

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
                            var a = '<a target="_blank" href="http://blccdmis01/crlibrary/modify.php?id='+row.Id+'">EDIT</a>'; // row object contains the row data
                            return a;
                        } 
				    },
				    {
				    	data: null,
				    	"targets" : -1,
				    	"defaultContent": "<button class='unPublish'>Un Publish</button>"
				    }
    			 ],
           /*{
        
      },*/

      initComplete: function(){

          this.api().columns([1,2,3,4]).every(function(){

	          var column = this;

	          var select = $('<select style="width:80px;"><option value="">-Select-</option></select>')
	              .appendTo($(column.header()).empty())
	              .on('change',function(){
	                    var val = $.fn.dataTable.util.escapeRegex(
	                          $(this).val()
	                      );

	                    column
	                    .search( val ? '^'+val+'$' : '', true, false)
	                    .draw();
	              });

            column.data().unique().sort().each(function (d,j){

                 	select.append('<option value="'+d+'">'+d+'</option>');

               	 });
        	});
      }

	});

	$("#edit-delete tbody").on('click','button.unPublish', function() {

		  var tr = $(this).closest('tr');
		  var tds = tr.find('td');
	      var messageID = tds.eq(0).text();
	      //alert(messageID);

	      //Now call AJAX to save hit count
	      $.ajax({
	      	type: 'POST',
	      	url: 'unPublish.php?id='+messageID,
	      	success: function(data)
	      	{
	      		//alert(data);
	      		if(data == 1)
	      		{
	      			alert('Selected message has been un published.');
	      		}
	      		
	      	}

	      });
		});

});

$('#anchor-cat').click(function(){
	$('#div-cat').css("display","block");
	$("#div-cat").delay(5000).show(6000);	
});

$('#anchor-subcat').click(function(){
	$('#div-Subcat').css("display","block");
	$("#div-Subcat").delay(5000).show(6000);	
});

$('#anchor-source').click(function(){
	$('#div-source').css("display","block");
	$("#div-source").delay(5000).show(6000);	
});

$('#cat').attr('placeholder','2174,1012,2075.....');
$('#text').attr('placeholder','contents in messages...');


/*$(document).ready(function () {
  var table = $("#example").DataTable({
    ajax: "data/arrays.txt",
    columnDefs: [
      {
        targets: -1,
        data: null,
        defaultContent: "<button>Click!</button>",
      },
    ],
  });

  $("#example tbody").on("click", "button", function () {
    var data = table.row($(this).parents("tr")).data();
    alert(data[0] + "'s salary is: " + data[5]);
  });
});*/


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