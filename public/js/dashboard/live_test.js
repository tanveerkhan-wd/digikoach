
$('.change_category').on('click', function() {
    $('.remove_div_category_btn').remove();
    $('.change_subcategory').remove();
    $('.change_category').removeClass('hide_content');
    $("#get_sub_category_question").empty();
    $("#get_sub_category").empty();
});
var date = new Date();
var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());

$(document).ready(function(){

	$(".datepicker1").datepicker({
		format: "mm/dd/yyyy",
		autoclose: true,
		todayHighlight: true,
	});

	var start_date = $('#start_date').val();
	$("#end_date").datepicker({
		format: "mm/dd/yyyy",
		autoclose: true,
		todayHighlight: true,
		startDate: start_date,
	})

	var end_date = $('#end_date').val();
	$("#result_date").datepicker({
		format: "mm/dd/yyyy",
		autoclose: true,
		todayHighlight: true,
		startDate: end_date,
	})

    $("#start_date").datepicker({
        format: "mm/dd/yyyy",
		autoclose: true,
		todayHighlight: true,
		startDate: today,
    }).on('changeDate', function (selected) {
    	$("#end_date").val('');
    	$("#end_time").val('');
    	$("#result_date").val('');
    	$("#result_time").val('');
        var minDate = new Date(selected.date.valueOf());
        $('#end_date').datepicker('setStartDate', minDate);
    });

    $("#end_date").datepicker()
        .on('changeDate', function (selected) {
    		$("#end_time").val('');
    		$("#result_date").val('');
    		$("#result_time").val('');
            var maxDate = new Date(selected.date.valueOf());
            
            var minDate1 = new Date(selected.date.valueOf());
        	$('#result_date').datepicker('setStartDate', minDate1);

        });

     $("#result_date").datepicker()
        .on('changeDate', function (selected) {
        	$("#result_time").val('');
            var maxDate1 = new Date(selected.date.valueOf());
        });
    
    $("#start_time").on('change', function() {
		var val_start_time = $("#start_time").val();
		val_start_time = parseDMY(val_start_time);
		$("#end_time").attr('min',val_start_time);
	});

	$("#end_time").on('click', function() {
		var fit_end_date = $("#start_date").val();
		var  fit_result_date = $("#end_date").val();
		if(fit_end_date == fit_result_date)
		{
			var val_end_time = $("#start_time").val();
			val_end_time = parseDMY(val_end_time);
			$("#end_time").attr('min',val_end_time);
		}else{
			$("#end_time").removeAttr('min');
		}
	});

	$("#result_time").on('click', function() {
		var fit_end_date = $("#end_date").val();
		var  fit_result_date = $("#result_date").val();
		if(fit_end_date == fit_result_date)
		{
			var val_end_time = $("#end_time").val();
			val_end_time = parseDMY(val_end_time);
			$("#result_time").attr('min',val_end_time);
		}else{
			$("#result_time").removeAttr('min');
		}
	});
	
	function pad(n) {
    	return (n < 10) ? ("0" + n) : n;
	}
	function parseDMY(value) {
	    var date_t = value.split(":");
	    var H = parseInt(date_t[0]),
	        i = parseInt(++date_t[1]);
	    return (H+':'+pad(i));
	}
});

$(function() {

	//LIVE TEST LISTING
	//LISTING
	var imagepath=base_url+'/public/images/';
  	$('#live_test_listing').on( 'processing.dt', function ( e, settings, processing ) {
	        if(processing){
	          showLoader(true);
	        }else{
	          showLoader(false);
	        }
    	} ).DataTable({
	        "language": {
	          "sLengthMenu": $('#show_txt').val()+" _MENU_ "+$('#entries_txt').val(),
	          "info": $('#showing_txt').val()+" _START_ "+$('#to_txt').val()+" _END_ "+$('#of_txt').val()+" _TOTAL_ "+$('#entries_txt').val(),
	          "emptyTable": $('#msg_no_data_available_table').val(),
	          "paginate": {
	            "previous": $('#previous_txt').val(),
	            "next": $('#next_txt').val()
	          }
	        },
	        "lengthMenu": [10,20,30,50],
	        "searching": false,
	        "serverSide": true,
	        "deferRender": true,
	        "ajax": {
	            "url": base_url+"/admin/liveTests",
	            "type": "POST",
	            "dataType": 'json',
	            "data": function ( d ) {
	              d.search = $('#search_live_test').val();
	              d.status_type = $('#status_type').val();
	              d.search_category = $('#search_category').val();
	              d.date_from = $('#search_date_from').val();
	              d.date_to = $('#search_date_to').val();
	              d.test_type = $('#test_type').val();
	            }
	        },
	        columns:[
	            
	            { "data": "index",className: "text-center"},

	            { "data": "desc.exam_name", sortable:!1,className: "text-center"},

	            { "data": "category", sortable:!1,className: "text-center"},

	            { "data": "total_questions",className: "text-center"},
	            
	            { "data": "total_marks",className: "text-center"},

	            { "data": "exam_duration",className: "text-center",
	            	render: function (data, type, row) {
	                  var html = row.exam_duration;
	                  return html+ ' Mins';
	                }
	        	},
	            
	            { "data": "date_time", sortable:!1,className: "text-center"},

	            { "data": "result_date", className: "text-center"},

	            { "data": "status",className: "text-center",
	                render: function (data, type, row) {
	                	var html = '';
		                var status = row.status==1 ? 'Active' :'Inactive';
		                var addClass = status=='Active' ? '' : 'inactiveClass';
		                if (row.status_access==true) {
		                    html ='<span><a href="javascript:void(0)" class="changeStatus '+addClass+'" onclick="triggerStatus('+row.exam_id+')">'+status+'</span></a>'
		                }else{
		                    html = status
		                }
		                return html;
	                }
	            },
	            { "data": "Action", sortable:!1,
	              render: function (data, type, row) {
	              	var html = '';
	                var deleted = '';
	                var editExam = '';
	                var viewExam = '';
	                var student = '';
	                if (row.deleted_access==true) {
	                    deleted = '<a onclick="triggerDelete('+row.exam_id+')" href="javascript:void(0)"><img src="'+imagepath+'/ic_delete.png"></a>'
	                }
	                if (row.edit_access==true) {
	                  	editExam = '<a href="'+base_url+'/admin/editLiveTest/'+row.exam_id+'"><img src="'+imagepath+'/ic_mode_edit.png"></a>'
	                }
					if (row.student_access) {
						student = '<a href="'+base_url+'/admin/liveTest/appearedStudents/'+row.exam_id+'"><img src="'+imagepath+'/ic_users_color.png"></a>'
					}
	                viewExam = '<a href="'+base_url+'/admin/viewLiveTest/'+row.exam_id+'"><img src="'+imagepath+'/ic_eye_color.png"></a>'
	              	
	              	if(row.check_action == false){
	              		html = '\t\t\t\t\t\t '+editExam+' \t\t\t\t\t\t '+deleted+' \t\t\t\t\t\t '
	              	}
	              	return viewExam + html + student
	              }
	            },
	     	],

  	});
	
  	$("#search_live_test").on('keyup', function () {
        $('#live_test_listing').DataTable().ajax.reload();
	});
	$("#search_date_from").on('change', function () {
        $('#live_test_listing').DataTable().ajax.reload();
	});
	$("#search_date_to").on('change', function () {
        $('#live_test_listing').DataTable().ajax.reload();
	});
  
	$('#delete_prompt').on('hidden.bs.modal', function () {
	    $("#did").val('');
	})

	$("#status_type").on('change', function () {
	    $('#live_test_listing').DataTable().ajax.reload();
	});

	$("#search_category").on('change', function () {
	    $('#live_test_listing').DataTable().ajax.reload();
	});
	$("#test_type").on('change', function () {
	    $('#live_test_listing').DataTable().ajax.reload();
	});

	/*Listing End*/


	//LIVE TEST Appeared student LISTING
	//LISTING
  	$('#appear_stu_listing').on( 'processing.dt', function ( e, settings, processing ) {
	        if(processing){
	          showLoader(true);
	        }else{
	          showLoader(false);
	        }
    	} ).DataTable({
	        "language": {
	          "sLengthMenu": $('#show_txt').val()+" _MENU_ "+$('#entries_txt').val(),
	          "info": $('#showing_txt').val()+" _START_ "+$('#to_txt').val()+" _END_ "+$('#of_txt').val()+" _TOTAL_ "+$('#entries_txt').val(),
	          "emptyTable": $('#msg_no_data_available_table').val(),
	          "paginate": {
	            "previous": $('#previous_txt').val(),
	            "next": $('#next_txt').val()
	          }
	        },
	        "lengthMenu": [10,20,30,50],
	        "searching": false,
	        "serverSide": true,
	        "deferRender": true,
	        "ajax": {
	            "url": base_url+"/admin/appearedStudents",
	            "type": "POST",
	            "dataType": 'json',
	            "data": function ( d ) {
	              d.search = $('#search_appear_stu').val();
	              d.stu_exam_id = $('#stu_exam_id').val();
	            }
	        },
	        columns:[
	            
	            { "data": "index",className: "text-center"},

	            { "data": "user.name", sortable:!1,className: "text-center",
	            	render: function (data, type, row) {
	            		var getSessionData = $("#getSessionData").val();
	            		var viewUserPath = 'javascript:void(0)';
	            		if (getSessionData==false) {
		                	viewUserPath = base_url+'/admin/viewAppUser/'+row.user.user_id;
	            		}
		                var html = '<a class="changeStatus" href="'+viewUserPath+'">'+row.user.name+'</a>'
		                return html;
		            }
	        	},

	            { "data": "total_attempted",className: "text-center"},

	            { "data": "total_correct",className: "text-center"},
	            
	            { "data": "total_incorrect",className: "text-center"},

				{ "data": "total_marks",className: "text-center",
	            	render: function (data, type, row) {
	                  var html = row.total_obtain_marks +'/'+ row.total_marks;
	                  return html;
	                }
	        	},
	        	
	        	{ "data": "attempt_date",className: "text-center"},	            

	            { "data": "user_rank",className: "text-center",
	            	render: function (data, type, row) {
	            	var html ='';
	            	if (row.user_rank==0 || row.user_rank_base==0) {
	            		html = 'NA';
	            	}else{
	                  html = row.user_rank +'/'+ row.user_rank_base;
	            	}
	                return html;
	        		}
	        	},
	        	{ "data": "action",sortable:!1,className: "text-center",
	            	render: function (data, type, row) {
	                  return'<a href="'+base_url+'/admin/appearedStudents/viewUserTest/'+row.user_attempt_id+'" class"text-center"><img src="'+imagepath+'/ic_eye_color.png"></a>'
	                }
	        	},

	     	],

  	});
	
  	$("#search_appear_stu").on('keyup', function () {
        $('#appear_stu_listing').DataTable().ajax.reload();
	});
	//student list Listing End

	//ADD LIVE TEST
	$("form[name='add-live-test-form']").validate({
	  errorClass: "error_msg",
	   rules: {
	   		name_en:{
	        	required:true,
	      	},
	      	name_hi:{
	        	required:true,
	      	},
	      	duration:{
	        	required:true,
	      	},
	      	start_date:{
	       	 	required:true,
	      	},
	      	start_time:{
	      		required:true,	
	      	},
	      	end_date:{
	      		required:true,
	      	},
	      	end_time:{
	      		required:true,				
	      	},
	      	result_date:{
	      		required:true,				
	      	},
	      	main_category:{
	      		required:true,				
	      	}
	   	},
	  	submitHandler: function(form, event) {
      	event.preventDefault();
        showLoader(true);
	    var formData = new FormData($(form)[0]);
	    var parent_cate_id = $("#category").val();
	    formData.append('parent_cate_id',parent_cate_id);
	    $.ajax({
	        url: base_url+'/admin/addLiveTestPost',
	        type: 'POST',
	        processData: false,
	        contentType: false,
	        cache: false,              
	        data: formData,
	        success: function(result)
	        {
	            if(result.status){
	              toastr.success(result.message);
	              window.location.href = base_url+'/admin/liveTest';
	            }else{
	              toastr.error(result.message);
	            }
	            showLoader(false);
	        },
	        error: function(data)
	        {
	            toastr.error($('#something_wrong_txt').val());
	            showLoader(false);
	        }
	    });
	  }
	});


});


// CATEGORY MASTER 
$(document).on('change','#category', function() {
	$("#get_sub_category").empty();
});


$(document).on('keypress','#question_number',function (e) {
 	
 	if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
    	return false;
	}
});


$(document).on('input','#question_number', function () {

	var findExitQues = $(this).parent().parent().parent().parent();
	var remaining_ques = findExitQues.find("#remaining_ques").val();
	var requestQue = $(this).val();
	if (parseInt(requestQue)==0 ||  parseInt(requestQue) > parseInt(remaining_ques)) {
		$(this).addClass('active_category');
		$(this).val('');
	}else if(parseInt(requestQue) <= parseInt(remaining_ques)){
		$(this).removeClass('active_category');	
	}
})

$(document).on('change','.getCategory', function() {

  $(this).parent().nextAll('.category_sub_box').remove();

  var cid = $(this).val();
  if (cid.length<=0) {
  	return false;
  }
  showLoader(true);
  $.ajax({
    url: base_url+'/admin/liveTest/getCategoryData',
    type: 'POST',
    dataType:'json',
    cache: false,              
    data: {'cid':cid},
    success: function(result)
    {
        if (result.data=='not_found') {
          showLoader(false);
          $("#get_sub_category_question").empty();
          return false;
        }
        if(result.status){
            var aData = [];
            var aData1 = [];
            var is_parent_cate = false;
            $.each(result.data, function(index, value) {
            	aData1.push(value);
            	if (value.is_parent_cate >0) {
            		is_parent_cate = true;
            	}
              if (value.category_desc) {
                  $.each(value.category_desc, function(index1, value1) {
                  if (value1.lang_code=='en') {
                    aData.push(value1);
                  }
                }); 
              }
            });
            if (aData.length >0) {
            	if (is_parent_cate==true) {
	            	$(".category_sub_box").each(function() {
	            		$(this).find('select').attr("name","main_category");
					});
            	}
            	if (is_parent_cate==true) {
	            	var html = '';
	            	html+='<div class="form-group category_sub_box"><label>Select Sub-Category</label><select class="form-control icon_control dropdown_control getCategory" name="category"><option value="">Select</option>'
	              	for (var i = aData.length - 1; i >= 0; i--) {

	            		html+='<option value="'+aData[i].category_id+'">'+aData[i].name+'</option>'
	                }
	              	html+='</select></div>'
	              	html+='<input type="hidden" name="category" value"'+aData[0].category_id+'">'
	              	$(html).appendTo("#get_sub_category");
	            }
              	var html2 = '';
              	html2+='<div class="row"><div class="col-md-3 col-lg-3"><label><b>Sub Categories</b></label></div><div class="col-md-3 col-lg-3"><label><b>No. of Que. in QB</b></label></div><div class="col-md-3 col-lg-3"><label><b>No. of Unused Que. in QB</b></label></div><div class="col-md-3 col-lg-3"><label><b>How many Que. want to add in test</b></label></div></div>'

				for (var i = aData.length - 1; i >= 0; i--) {

              		html2+='<div class="row"><div class="col-md-3 col-lg-3"><label> '+aData[i].name+' </label></div><div class="col-md-3 col-lg-3"><label> '+aData1[i].no_of_que+' Questions </label></div><div class="col-md-3 col-lg-3"><label> '+aData1[i].no_of_unused_que+' Questions </label><input type="hidden" id="remaining_ques" value="'+aData1[i].no_of_unused_que+'"></div><div class="col-md-3 col-lg-3"><div class="row"><div class="col-md-8 col-lg-8 p-1"><input type="number" name="question_number[]" id="question_number" class="form-control" min="0"></div><div class="col-md-4 col-lg-4 p-1"><label>Ques</label></div></div><input type="hidden" name="sub_category_id[]" value="'+aData[i].category_id+'"></div></div>'
              	}
              	
              	$("#get_sub_category_question").html(html2);
              	
            }else{
            	$("#get_sub_category").empty();
            }

        }else{
          toastr.error(result.message);
        }
        
        showLoader(false);
    },
    error: function(data)
    {
        toastr.error($('#something_wrong_txt').val());
        showLoader(false);
    }
  });

});


//DELETE DATA

function triggerDelete(cid){
   $('#did').val(cid);   
   $( ".show_delete_modal" ).click();
}

function confirmDelete(){
  showLoader(true);
  var cid = $('#did').val();
  $.ajax({
    url: base_url+'/admin/deleteLiveTest',
    type: 'POST',
    dataType:'json',
    cache: false,              
    data: {'cid':cid},
    success: function(result)
    {
        $('#delete_prompt').modal('hide');
        if(result.status){
          toastr.success(result.message);
          $('#live_test_listing').DataTable().ajax.reload();
        }else{
          toastr.error(result.message);
        }
        
        showLoader(false);
    },
    error: function(data)
    {
        toastr.error($('#something_wrong_txt').val());
        showLoader(false);
    }
  });
}


//ACTIVE OR INACTIVE


function triggerStatus(cid){
   $('#did').val(cid);   
   $( ".show_status_modal" ).click();
}


function confirmStatus(cid){
  showLoader(true);
  var cid = $('#did').val();
  $.ajax({
    url: base_url+'/admin/statusLiveTest',
    type: 'POST',
    dataType:'json',
    cache: false,              
    data: {'cid':cid},
    success: function(result)
    {
        if(result.status){
          $('#status_prompt').modal('hide');
          $('#live_test_listing').DataTable().ajax.reload();
          toastr.success(result.message);

        }else{
          toastr.error(result.message);
        }
        
        showLoader(false);
    },
    error: function(data)
    {
        toastr.error($('#something_wrong_txt').val());
        showLoader(false);
    }
  });
}


function triggerDeclareResult(cid){
  showLoader(true);
  $.ajax({
    url: base_url+'/admin/diclareLiveTestResult',
    type: 'POST',
    dataType:'json',
    cache: false,              
    data: {'cid':cid},
    success: function(result)
    {
        $('#delete_prompt').modal('hide');
        if(result.status){
          toastr.success(result.message);
          $('#live_test_listing').DataTable().ajax.reload();
        }else{
          toastr.error(result.message);
        }
        
        showLoader(false);
    },
    error: function(data)
    {
        toastr.error($('#something_wrong_txt').val());
        showLoader(false);
    }
  });
}

$(document).ready(function () {
  //called when key is pressed in textbox
  $("#question_number").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
        return false;
    }
   });
});