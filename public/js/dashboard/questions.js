
var imagepath=base_url+'/public/images/';
$(".chnageClassHover").hover(
  function(){ $(this).toggleClass('text-white') }
);

//ADD BULK QUESTION 
  $(function () {
      $("body").on("click", ".remove", function () {
          $(this).closest("tr").remove();
      });
  });
//ADD BULK QUESTION 


$(document).on('change','#question_type', function() {
    var question_type_val = $(this).val();
      $("#get_sub_category").empty();
    if (question_type_val=='GK_CA') {
      $("#get_sub_category").empty();
      $('#category').val("").trigger('change.select2');
      $("#category").parent().addClass('hide_content');
      $("#gk_ca_category").append('<input type="hidden" name="category" value="0">');
    }else{
      $("#category").parent().removeClass('hide_content');
      $("#gk_ca_category").empty();
    }
});

$('.add_manually_btn').on('click', function() {
  $(this).addClass('active_button_que');
  $("#check_button_clicked").html('<input type="hidden" id="upload_manual_type" value="upload_manual_type">');
  $("#upload_csv").removeClass('active_button_que');
  $("#bulk_upload").removeClass('active_button_que');
});

$("#upload_csv").on('click', function() {
  $(this).addClass('active_button_que');
  $("#check_button_clicked").html('<input type="hidden" id="upload_csv_type" value="upload_csv_type">');
  $(".add_manually_btn").removeClass('active_button_que');
  $("#bulk_upload").removeClass('active_button_que');
});

$("#bulk_upload").on('click', function() {
  $(this).addClass('active_button_que');
  $("#check_button_clicked").html('<input type="hidden" id="bulk_upload_type" value="bulk_upload_type">');
  $(".add_manually_btn").removeClass('active_button_que');
  $("#upload_csv").removeClass('active_button_que');
});


$(document).on('click','#next-step-1', function() {
  var active_button_class = $("#check_button_clicked input").val();
  if($("#question_type").val()=='GK_CA'){
      if(active_button_class=='upload_manual_type'){
        $("#step-1").addClass('hide_content');
        $("#step-3").addClass('hide_content');
        $("#step-2").removeClass('hide_content');
      }else if(active_button_class=='upload_csv_type'){
        $("#step-1").addClass('hide_content');
        $("#step-3").addClass('hide_content');
        $("#upload_csv_box").removeClass('hide_content');
      }else if(active_button_class=='bulk_upload_type'){
        $("#step-1").addClass('hide_content');
        $("#upload_csv_box").addClass('hide_content');
        $("#step-3").removeClass('hide_content');
      }

  }else{
    if($("#category").val().length > 0 && $("#question_type").val().length>0){
      if(active_button_class=='upload_manual_type'){
          $("#step-1").addClass('hide_content');
          $("#step-2").removeClass('hide_content');
        }else if(active_button_class=='upload_csv_type'){
          $("#step-1").addClass('hide_content');
          $("#upload_csv_box").removeClass('hide_content');
        }else if(active_button_class=='bulk_upload_type'){
          $("#step-1").addClass('hide_content');
          $("#upload_csv_box").addClass('hide_content');
          $("#step-3").removeClass('hide_content');
        }
    }else{
        toastr.error('Please Select Category & Question Type');
    }
  }
});


$(".back_to_step1").on('click', function() {
	$("#step-1").removeClass('hide_content');
  $("#step-3").addClass('hide_content');
	$("#upload_csv_box").addClass('hide_content');
});

$('#prev-step-1').on('click', function() {
  	$("#step-1").removeClass('hide_content');
    $("#step-2").addClass('hide_content');
  	$("#step-3").addClass('hide_content');
});

$("#addNewOption").on('click', function() {
	var current = 1;
	$(".options_box").each(function() {
	   current++;
	});

  if(current <= 5){
		$(".addNewOptionList").append('<div class="options_box row"><a class="removeOption btn btn-primary btn-small text-white ml-3">Remove</a><div class="col-md-12 col-lg-12"><div class="form-group"><label>Option In English</label><textarea class="form-control icon_control" name="option_en[]" id="option_en'+current+'" rows="3"></textarea></div>     <ul class="clearfix media_list"><li class="myupload"><span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="OPTION" lang-data="en" input-name="option_media['+current+'][]" input-vali-name="opt_me_en['+current+'][]" click-type="type2" class="picuploadForAll picupload" multiple></span></li></ul>    <div class="form-group"><label>Option In Hindi</label><textarea class="form-control icon_control" name="option_hi[]" id="option_hi'+current+'" rows="3"></textarea></div>     <ul class="clearfix media_list"><li class="myupload"><span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="OPTION" lang-data="hi" input-name="option_media['+current+'][]" input-vali-name="opt_me_hi['+current+'][]" click-type="type2" class="picuploadForAll picupload" multiple></span></li></ul>     <div class="form-group pt-3 mt-3"><label>Is this a correct option?</label><label class="main"><input type="radio" name="correct_option" class="radio_button_correct" value="'+current+'"><p style="font-size: 18px;" class="correct_optionclass"></p><span class="geekmark"></span> </label></div>  <div class="form-group"><label>Option Order</label><select class="form-control icon_control dropdown_control" name="option_order[]" id="option_order"><option value="">Select</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option></select></div> </div></div>');
    $("#updateOptionVal").val(current);
	}else{
		toastr.error('Cannot Select More Then 5 Options');
	}
});

$(document).on('click','a.removeOption', function() {
	$(this).parent().remove();
});


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
            "url": base_url+"/admin/questions",
            "type": "POST",
            "dataType": 'json',
            "data": function ( d ) {
              d.search = $('#search_question').val();
              d.status_type = $('#status_type').val();
              d.search_category = $('#search_category').val();
              d.question_type = $('#question_type').val();
              d.question_used_unused = $('#question_used_unused').val();
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
            
            { "data": "questions_id", sortable:!1,className: "text-center",
            	render: function (data, type, row) {
                  var image = row.question_image;
                  var html = row.question_desc;
                  var imageDa = '';
                  var htmlDa = '';
                  if (html!=null && html) {
                  	htmlDa = row.question_desc.question_text==null ? '' :row.question_desc.question_text;
                  }

                  if(image && image!=false){
                  	imageDa = '<img src="'+base_url+'/public/storage/'+image+'" style="max-height: 190px;width: auto;">'
                  }
                  	return htmlDa + imageDa;
                }	
        	  },

            { "data": "question_type", sortable:!1,className: "text-center"},

            { "data": "category", sortable:!1,className: "text-center"},
            
            { "data": "marks",className: "text-center"},
            
            { "data": "status",className: "text-center",
                render: function (data, type, row) {
                  var html = '';
                  var status = row.status==1 ? 'Active' :'Inactive';
                  var addClass = status=='Active' ? '' : 'inactiveClass';
                  if (row.exams_id==null && row.status_access==true) {
                    html ='<span><a href="javascript:void(0)" class="changeStatus '+addClass+'" onclick="triggerStatus('+row.questions_id+')">'+status+'</span></a>'
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
                var editQue = '';
                var viewQue = '';
                if (row.deleted_access==true) {
                    deleted = '<a onclick="triggerDelete('+row.questions_id+')" href="javascript:void(0)"><img src="'+imagepath+'/ic_delete.png"></a>'
                }
                if (row.edit_access==true) {
                  editQue = '<a href="'+base_url+'/admin/editQuestion/'+row.questions_id+'"><img src="'+imagepath+'/ic_mode_edit.png"></a>'
                }

                viewQue = '<a href="'+base_url+'/admin/viewQuestion/'+row.questions_id+'"><img src="'+imagepath+'/ic_eye_color.png"></a>'
                
                if (row.exam_id==null) {
                  html =' '+viewQue+' \t\t\t\t\t\t '+editQue+' \t\t\t\t\t\t '+deleted+''
                }else{
                  html = viewQue 
                }
                return html;
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


  $("#question_used_unused").on('change', function () {
      $('#question_listing').DataTable().ajax.reload();
  });
  
	$("#status_type").on('change', function () {
	    $('#question_listing').DataTable().ajax.reload();
	});

  $("#question_type").on('change', function () {
      $('#question_listing').DataTable().ajax.reload();
  });
  
	$("#search_category").on('change', function () {
	    $('#question_listing').DataTable().ajax.reload();
	});
	/*Listing End*/
  var getButtonType = '';
  var getButtonName = '';
  $("button[type='submit']").on("click", function(){
    getButtonName =  $(this).attr('name');
    getButtonType = $(this).val();
  });

	// ADD NEW QUESTIONS
	$("form[name='add-question-form']").validate({
    errorClass: "error_msg",
	   rules: {
	   		"option_en[]": "required",
	   		"option_hi[]": "required",
	   		"option_order[]":"required",
	      	solution_en:{
	        	required:true,
	      	},
	      	solution_hi:{
	        	required:true,
	      	},
	      	question_en:{
	        	required:true,
	      	},
	      	question_hi:{
	       	 	required:true,
	      	},
	      	category:{
	      		required:true,	
	      	},
	      	marks:{
	      		required:true,
            digits: true,
            maxlength:1,
	      	},
	      	question_type:{
	      		required:true,				
	      	}
	   },
	    messages: {
            "option_en[]": "The field is required",
            "option_hi[]": "The field is required",
            "option_order[]": "The field is required",
      },
	    submitHandler: function(form, event) {
      event.preventDefault();
        
      for ( instance in CKEDITOR.instances )
      CKEDITOR.instances[instance].updateElement();
	    
	    showLoader(true);
	    var formData = new FormData($(form)[0]);
      formData.append(getButtonName,getButtonType);
	    $.ajax({
	        url: base_url+'/admin/addQuestionPost',
	        type: 'POST',
	        processData: false,
	        contentType: false,
	        cache: false,              
	        data: formData,
	        success: function(result)
	        {
	            if(result.status){
	              if (result.data=='save') {
                    toastr.success(result.message);
	              	  //$('li a[data-slug="admin/questions"]').trigger("click");
                    location.href = base_url+'/admin/questions';
	              }else if(result.data=='add_new'){
                  toastr.success(result.message);
	              	location.reload(true);
	              }else if(result.data=='bulk_upload_add'){
                  var counter = $("#bulk_counter").val();
                  $(".pane-hScroll").scrollLeft( 0 );
                  var quesId = '<input type="hidden" name="added_question_id[]" value="'+result.questions_id+'">';
                  $(quesId).appendTo("#added_question_id_box");
                  var tableRow = '<tr><td> <input name="bulk_question_en[]" type="text" class="form-control"></td><td>   <ul class="clearfix media_list"><li class="myupload"> <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="QUESTION" lang-data="en" input-name="question_media['+counter+'][]" input-vali-name="que_me_en['+counter+']" click-type="type2" class="picuploadForAll picupload" multiple></span></li></ul> </td><td> <input name="bulk_question_hi[]" type="text" class="form-control"></td><td><ul class="clearfix media_list"><li class="myupload"> <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="QUESTION" lang-data="hi" input-name="question_media['+counter+'][]" input-vali-name="que_me_hi['+counter+']" click-type="type2" class="picuploadForAll picupload" multiple></span></li></ul></td><td> <input name="bulk_solution_en[]" type="text" class="form-control"></td><td><ul class="clearfix media_list"><li class="myupload"> <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="SOLUTION" lang-data="en" input-name="question_media['+counter+'][]" input-vali-name="sol_me_en['+counter+']" click-type="type2" class="picuploadForAll picupload" multiple></span></li></ul></td><td> <input name="bulk_solution_hi[]" type="text" class="form-control"></td><td><ul class="clearfix media_list"><li class="myupload"> <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="SOLUTION" lang-data="hi" input-name="question_media['+counter+'][]" input-vali-name="sol_me_hi['+counter+']" click-type="type2" class="picuploadForAll picupload" multiple></span></li></ul></td><td> <input name="bulk_marks[]" type="number" class="form-control"></td><td> <input name="bulk_opt_en[1][]" type="text" class="form-control"></td><td><ul class="clearfix media_list"><li class="myupload"> <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="OPTION" lang-data="en" input-name="option_media['+counter+'][1][]" input-vali-name="opt_me_en['+counter+'][1]" click-type="type2" class="picuploadForAll picupload" multiple></span></li></ul></td><td> <input name="bulk_opt_hi[1][]" type="text" class="form-control"></td><td><ul class="clearfix media_list"><li class="myupload"> <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="OPTION" lang-data="hi" input-name="option_media['+counter+'][1][]" input-vali-name="opt_me_hi['+counter+'][1]" click-type="type2" class="picuploadForAll picupload" multiple></span></li></ul></td><td> <input name="bulk_opt_order[1][]" type="number" class="form-control"></td><td> <input name="bulk_opt_en[2][]" type="text" class="form-control"></td><td><ul class="clearfix media_list"><li class="myupload"> <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="OPTION" lang-data="en" input-name="option_media['+counter+'][2][]" input-vali-name="opt_me_en['+counter+'][2]" click-type="type2" class="picuploadForAll picupload" multiple></span></li></ul></td><td> <input name="bulk_opt_hi[2][]" type="text" class="form-control"></td><td><ul class="clearfix media_list"><li class="myupload"> <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="OPTION" lang-data="hi" input-name="option_media['+counter+'][2][]" input-vali-name="opt_me_hi['+counter+'][2]" click-type="type2" class="picuploadForAll picupload" multiple></span></li></ul></td><td> <input name="bulk_opt_order[2][]" type="number" class="form-control"></td><td> <input name="bulk_opt_en[3][]" type="text" class="form-control"></td><td><ul class="clearfix media_list"><li class="myupload"> <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="OPTION" lang-data="en" input-name="option_media['+counter+'][3][]" input-vali-name="opt_me_en['+counter+'][3]" click-type="type2" class="picuploadForAll picupload" multiple></span></li></ul></td><td> <input name="bulk_opt_hi[3][]" type="text" class="form-control"></td><td><ul class="clearfix media_list"><li class="myupload"> <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="OPTION" lang-data="hi" input-name="option_media['+counter+'][3][]" input-vali-name="opt_me_hi['+counter+'][3]" click-type="type2" class="picuploadForAll picupload" multiple></span></li></ul></td><td> <input name="bulk_opt_order[3][]" type="number" class="form-control"></td><td> <input name="bulk_opt_en[4][]" type="text" class="form-control"></td><td><ul class="clearfix media_list"><li class="myupload"> <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="OPTION" lang-data="en" input-name="option_media['+counter+'][4][]" input-vali-name="opt_me_en['+counter+'][4]" click-type="type2" class="picuploadForAll picupload" multiple></span></li></ul></td><td> <input name="bulk_opt_hi[4][]" type="text" class="form-control"></td><td><ul class="clearfix media_list"><li class="myupload"> <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="OPTION" lang-data="hi" input-name="option_media['+counter+'][4][]" input-vali-name="opt_me_hi['+counter+'][4]" click-type="type2" class="picuploadForAll picupload" multiple></span></li></ul></td><td> <input name="bulk_opt_order[4][]" type="number" class="form-control"></td><td> <input name="bulk_opt_en[5][]" type="text" class="form-control"></td><td><ul class="clearfix media_list"><li class="myupload"> <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="OPTION" lang-data="en" input-name="option_media['+counter+'][5][]" input-vali-name="opt_me_en['+counter+'][5]" click-type="type2" class="picuploadForAll picupload" multiple></span></li></ul></td><td> <input name="bulk_opt_hi[5][]" type="text" class="form-control"></td><td><ul class="clearfix media_list"><li class="myupload"> <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="OPTION" lang-data="hi" input-name="option_media['+counter+'][5][]" input-vali-name="opt_me_hi['+counter+'][5]" click-type="type2" class="picuploadForAll picupload" multiple></span></li></ul></td><td> <input name="bulk_opt_order[5][]" number="text" class="form-control"></td><td> <input name="bulk_right_opt[]" type="number" class="form-control"></td><td> <button type="button" class="btn btn-danger remove"><i class="fa fa-times"></i></button></td><tr>';
                    $("#TextBoxContainer").append(tableRow);
                    var bulk_cnt = parseInt(counter)+1;
                    $("#bulk_counter").val(bulk_cnt);
                }else if(result.data=='bulk_upload'){
                  toastr.success(result.message);
                  location.href = base_url+'/admin/questions';
                  //$('li a[data-slug="admin/questions"]').trigger("click");
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
	  }
	});



});



$(document).on('change','#category', function() {
	$("#get_sub_category").empty();
});


$(document).on('change','.getCategory', function() {

  $(this).parent().next('.category_sub_box').remove();

  var cid = $(this).val();
  if (cid.length<=0) {
  	return false;
  }
  showLoader(true);
  $.ajax({
    url: base_url+'/admin/getCategoryData',
    type: 'POST',
    dataType:'json',
    cache: false,              
    data: {'cid':cid},
    success: function(result)
    {
        if (result.data=='not_found') {
          showLoader(false);
          return false;
        }
        if(result.status){
            var aData = [];
            $.each(result.data, function(index, value) {
              if (value.category_desc) {
                  $.each(value.category_desc, function(index1, value1) {
                  if (value1.lang_code=='en') {
                    aData.push(value1);
                  }
                }); 
              }
            });

            if (aData.length >0) {
            	var html = '';
            	html+='<div class="form-group category_sub_box"><label>Select Sub-Category</label><select class="form-control icon_control dropdown_control getCategory" name="category"> <option>Select</option>'
              	for (var i = aData.length - 1; i >= 0; i--) {

            		html+='<option value="'+aData[i].category_id+'">'+aData[i].name+'</option>'
                	
              	}
              	html+='</select></div>'

              	$(html).appendTo("#get_sub_category");
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


//==================================//
//==================================//
//QUESTION EN//
$(function() {
    var names = [];
    $('body').on('change', '.picuploadForAll', function(event) {
        var $input = $(this);
        var input_name = $(this).attr('input-name');
        var input_vali_name = $(this).attr('input-vali-name');
        var type = $(this).attr('type-data');
        var lang_code = $(this).attr('lang-data');
        var ajaxData = new FormData();
        ajaxData.append('type',type);
        ajaxData.append('lang_code',lang_code);

        $.each(event.target.files,function(j, file){
            ajaxData.append('images['+j+']', file);
        })
        showLoader(true);
        $.ajax({
            url: base_url+'/admin/question/add_new_image',
            cache: false,
            contentType: false,
            processData: false,
            data: ajaxData,
            type: 'POST',
            success: function(response) {
              var allImagedata = response.data;
               if(allImagedata != '' || allImagedata != null){
                $.each(allImagedata,function(key, value){
                    
                  var div = document.createElement("li");
                  div.innerHTML = "<input type='hidden' name='"+input_vali_name+"' value='1'><input type='hidden' name='"+input_name+"' value='"+value.media_id+"'><img src='"+base_url+"/public/storage/"+value.directory+"/"+value.media_file+"' title="+value.media_file+"/><div  class='post-thumb'><div class='inner-post-thumb'><a href='javascript:void(0);' class='remove-pic'  onclick='removegalleryimage("+value.media_id+")'><i class='fa fa-times' aria-hidden='true'></i></a><div></div>";

                  $input.parent().parent().before(div);

                });
               }
               showLoader(false);
            },
            error: function(error) {
              showLoader(false);
              console.log(error);
            }
        });
        
    });
    $('body').on('click', '.remove-pic', function() {
      $(this).parent().parent().parent().remove();
    });
});

function removegalleryimage(id){
  $.ajax({
    type: "get",
    url: base_url+'/admin/queImage/remove/'+id,
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success: function(data) {

    }
  });
}

function removepic(id){
  var removeItem = id;  
  if(removeItem){
    $.ajax({
      url: base_url+'/admin/deleteQuestionMedia',
      type: 'POST',
      dataType:'json',
      cache: false,              
      data: {'cid':removeItem},
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
}

$(document).on('click',"#addNewOption", function () {
  $('html, body').animate({scrollTop: $('.options_box').last().offset().top -100}, 'slow');
});

$(document).on('change',"#upload_csv_file", function () {
    if( document.getElementById("upload_csv_file").files.length == 0 ){
        $('.add_csv_file_name_cl').addClass('hide_content');
    }
    selectProfileImage1(this);
});
  
function selectProfileImage1(input){
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      var filename = input.files[0].name;
      var fileExtension = filename.substr((filename.lastIndexOf('.') + 1));
      var fileExtensionCase = fileExtension.toLowerCase();
      if (fileExtensionCase == 'csv') {
          $('#add_csv_file_name').attr('value', filename);
          $('.add_csv_file_name_cl').removeClass('hide_content');
      }else{
        toastr.error('Please Upoad valid Csv');
        $('.add_csv_file_name_cl').addClass('hide_content');
      }
  }
}