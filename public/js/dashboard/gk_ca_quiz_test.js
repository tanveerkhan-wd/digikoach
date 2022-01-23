
$(function() {
	// TEST LISTING
	//LISTING
	var imagepath=base_url+'/public/images/';
  	$('#quiz_test_listing').on( 'processing.dt', function ( e, settings, processing ) {
	        if(processing){
	          showLoader(true);
	        }else{
	          showLoader(false);
	        }
    	}).DataTable({
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
	            "url": base_url+"/admin/gkCa/quizTest",
	            "type": "POST",
	            "dataType": 'json',
	            "data": function ( d ) {
	              d.search = $('#search_quiz_test').val();
	              d.status_type = $('#status_type').val();
	              d.test_type = $('#test_type').val();
	            }
	        },
	        columns:[
	            
	            { "data": "index",className: "text-center"},

	            { "data": "desc.exam_name", sortable:!1,className: "text-center"},
	            
	            { "data": "total_questions", sortable:!1,className: "text-center"},

	            { "data": "exam_duration", sortable:!1,className: "text-center"},
	            
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
	            { "data": "action", sortable:!1,
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
	                  	editExam = '<a href="'+base_url+'/admin/gkCa/editQuizTest/'+row.exam_id+'"><img src="'+imagepath+'/ic_mode_edit.png"></a>'
	                }
					if (row.student_access) {
						student = '<a href="'+base_url+'/admin/gkCa/quizTest/appearedStudents/'+row.exam_id+'"><img src="'+imagepath+'/ic_users_color.png"></a>'
					}
	                viewExam = '<a href="'+base_url+'/admin/gkCa/viewQuizTest/'+row.exam_id+'"><img src="'+imagepath+'/ic_eye_color.png"></a>'
	              	
	              	if (row.check_action==false) {
	              		html = '  \t\t\t\t\t\t '+editExam+' \t\t\t\t\t\t '+deleted+' \t\t\t\t\t\t '
	              	}
	                return viewExam + html + student	              	

	              }
	            },
	     	],

  	});
	
  	$("#search_quiz_test").on('keyup', function () {
        $('#quiz_test_listing').DataTable().ajax.reload();
	});
	
	$('#delete_prompt').on('hidden.bs.modal', function () {
	    $("#did").val('');
	})

	$("#status_type").on('change', function () {
	    $('#quiz_test_listing').DataTable().ajax.reload();
	});
	
	$("#test_type").on('change', function () {
	    $('#quiz_test_listing').DataTable().ajax.reload();
	});
	/*Listing End*/


	//PRACTICE TEST Appeared student LISTING
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
	            "url": base_url+"/admin/gkCa/quizTest/appearedStudents",
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
	        	
	        	{ "data": "attempt_date",sortable:!1,className: "text-center"},	            

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
	                  return'<a href="'+base_url+'/admin/gkCa/quiz/viewUserTest/'+row.user_attempt_id+'" class"text-center"><img src="'+imagepath+'/ic_eye_color.png"></a>'
	                }
	        	},

	     	],

  	});
	
  	$("#search_appear_stu").on('keyup', function () {
        $('#appear_stu_listing').DataTable().ajax.reload();
	});
	//student list Listing End



	//ADD TEST
	$("form[name='add-quiz-test-form']").validate({
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
	      	question_number:{
	        	required:true,
	      	}
	   	},
	  	submitHandler: function(form, event) {
      	event.preventDefault();
        showLoader(true);
	    var formData = new FormData($(form)[0]);
	    $.ajax({
	        url: base_url+'/admin/gkCa/addQuizTestPost',
	        type: 'POST',
	        processData: false,
	        contentType: false,
	        cache: false,              
	        data: formData,
	        success: function(result)
	        {
	            if(result.status){
	              toastr.success(result.message);
	              $('li a[data-slug="admin/gkCa/quizTest"]').trigger("click");
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

//DELETE DATA

function triggerDelete(cid){
   $('#did').val(cid);   
   $( ".show_delete_modal" ).click();
}

function confirmDelete(){
  showLoader(true);
  var cid = $('#did').val();
  $.ajax({
    url: base_url+'/admin/gkCa/deleteQuizTest',
    type: 'POST',
    dataType:'json',
    cache: false,              
    data: {'cid':cid},
    success: function(result)
    {
        $('#delete_prompt').modal('hide');
        if(result.status){
          toastr.success(result.message);
          $('#quiz_test_listing').DataTable().ajax.reload();
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
    url: base_url+'/admin/gkCa/statusQuizTest',
    type: 'POST',
    dataType:'json',
    cache: false,              
    data: {'cid':cid},
    success: function(result)
    {
        if(result.status){
          $('#status_prompt').modal('hide');
          $('#quiz_test_listing').DataTable().ajax.reload();
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

$(document).ready(function () {
  $("#question_number").keypress(function (e) {
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        return false;
    }
   });
});