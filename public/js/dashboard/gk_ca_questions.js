$(function() {

	//LISTING
	var imagepath= base_url+'/public/images/';
  	$('#question_listing').on( 'processing.dt', function ( e, settings, processing ) {
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
            "url": base_url+"/admin/gkCa/questionBank",
            "type": "POST",
            "dataType": 'json',
            "data": function ( d ) {
              d.search = $('#search_question').val();
              d.status_type = $('#status_type').val();
            }
        },
        columns:[
            
            { "data": "index",className: "text-center"},
            
            { "data": "questions_id",className: "text-center",
            	render: function (data, type, row) {
                  var html = row.questions_id;
                  return 'Q_'+html;
                }
        	},
            
            { "data": "question", sortable:!1,className: "text-center",
            	render: function (data, type, row) {
                  var html = row.question_desc;
                  if (html) {
                  	html = html.question_text;
                  }else{
                  	html = 'NA'
                  }
                  	return html;
                }	
        	},

            { "data": "type", sortable:!1,className: "text-center",
              render: function (data, type, row) {
                  return 'GK'
                }
            },
            
            { "data": "status",className: "text-center",
                render: function (data, type, row) {
                  var status = row.status==1 ? 'Active' :'Inactive';
                  return '<span><a href="javascript:void(0)" onclick="triggerStatus('+row.questions_id+')">'+status+'</span></a>'
                }
            },
            { "data": "action", sortable:!1,
              render: function (data, type, row) {
                return'<a href="'+base_url+'/admin/viewQuestion/'+row.questions_id+'"><img src="'+imagepath+'/ic_eye_color.png"></a>\t\t\t\t\t\t<a href="'+base_url+'/admin/editQuestion/'+row.questions_id+'"><img src="'+imagepath+'/ic_mode_edit.png"></a>\t\t\t\t\t\t<a onclick="triggerDelete('+row.questions_id+')" href="javascript:void(0)"><img src="'+imagepath+'/ic_delete.png"></a>'
              }
            },
     	],

  	});
	
  $("#search_question").on('keyup', function () {
        $('#question_listing').DataTable().ajax.reload();
	});

	$('#delete_prompt').on('hidden.bs.modal', function () {
	    $("#did").val('');
	})

	$("#status_type").on('change', function () {
	    $('#question_listing').DataTable().ajax.reload();
	});
	/*Listing End*/


});


//CHANGE STATUS
function triggerStatus(cid){
   $('#did').val(cid);   
   $( ".show_status_modal" ).click();
}


function confirmStatus(cid){
  showLoader(true);
  var cid = $('#did').val();
  $.ajax({
    url: base_url+'/admin/questionStatus',
    type: 'POST',
    dataType:'json',
    cache: false,              
    data: {'cid':cid},
    success: function(result)
    {
        if(result.status){
          $('#status_prompt').modal('hide');
          $('#question_listing').DataTable().ajax.reload();
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




function triggerDelete(cid){
   $('#did').val(cid);   
   $( ".show_delete_modal" ).click();
}

function confirmDelete(){
  showLoader(true);
  var cid = $('#did').val();
  $.ajax({
    url: base_url+'/admin/deleteQuestion',
    type: 'POST',
    dataType:'json',
    cache: false,              
    data: {'cid':cid},
    success: function(result)
    {
        $('#delete_prompt').modal('hide');
        if(result.status){
          toastr.success(result.message);
          $('#question_listing').DataTable().ajax.reload();
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
