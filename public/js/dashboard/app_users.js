
//Liting
$(function() {
  //Change Bredcrumb
  var date = new Date();
  var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
  $('.datepicker').datepicker({
    format: "mm/dd/yyyy",
    autoclose: true,
    todayHighlight: true,
    startDate: today
  });
  $('.datepicker1').datepicker({
    format: "mm/dd/yyyy",
    autoclose: true
  });

  $("#upload_profile1").on('change', function () { 
    if( document.getElementById("upload_profile1").files.length == 0 ){
        $('#cat1_img').attr('src', $('#img_tmp1').val());
    }
      selectProfileImage1(this);
  });

  $('#profile-tab').on('click', function() {
      $('.title').html('<a class="ajax_request" data-slug="admin/appUsers" href="'+base_url+'/admin/appUsers"><span>App Users</span></a> > Profile');
  });

  $('#live-test-tab').on('click', function() {
      $('.title').html('<a class="ajax_request" data-slug="admin/appUsers" href="'+base_url+'/admin/appUsers"><span>App Users</span></a> > Live Test');
  });

  $('#quizze-tab').on('click', function() {
      $('.title').html('<a class="ajax_request" data-slug="admin/appUsers" href="'+base_url+'/admin/appUsers"><span>App Users</span></a> > Quizze');
  });

  $('#practice-test-tab').on('click', function() {
      $('.title').html('<a class="ajax_request" data-slug="admin/appUsers" href="'+base_url+'/admin/appUsers"><span>App Users</span></a> > Practice Test');
  });

  $('#gk-quiz-tab').on('click', function() {
      $('.title').html('<a class="ajax_request" data-slug="admin/appUsers" href="'+base_url+'/admin/appUsers"><span>App Users</span></a> > GK Quiz');
  });


  $('#perfoemance-tab').on('click', function() {
      $('.title').html('<a class="ajax_request" data-slug="admin/appUsers" href="'+base_url+'/admin/appUsers"><span>App Users</span></a> > Performance');
  });

  var imagepath= base_url+'/public/images/';
  $('#app_user_listing').on( 'processing.dt', function ( e, settings, processing ) {
        if(processing){
          showLoader(true);
        }else{
          showLoader(false);
        }
    } ).DataTable({
        "columnDefs": [{
          
        }],
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
            "url": base_url+"/admin/appUsers",
            "type": "POST",
            "dataType": 'json',
            "data": function ( d ) {
              d.search = $('#search_app_user').val();
              d.status_type = $('#status_type').val();
              d.search_category = $('#search_category').val();
            }
        },
        columns:[
            
            { "data": "index",className: "text-center"},
            
            { "data": "user_id",className: "text-center"},
            
            { "data": "name",className: "text-center",
              render: function (data, type, row) {
                var name = row.name==null?'<b>-</b>':row.name;
                var viewUserPath = base_url+'/admin/viewAppUser/'+row.user_id;
                var html = '<a class="changeStatus" href="'+viewUserPath+'">'+name+'</a>'
                return html;
              }
            },

            { "data": "mobile_number",className: "text-center"},
            
            { "data": "email",className: "text-center",
              render: function (data, type, row) {
                var email = row.email==null?'<b>-</b>':row.email;
                return email;
              }
            },
            
            { "data": "user_fav_category",className: "text-center",
               render: function (data, type, row) {
                  if (row.fav_category != null && row.fav_category.name) {
                    var html = row.fav_category.name;
                  }else{
                    var html = 'NA';
                  }
                  return html;
               }
            },
            { "data": "created_at",className: "text-center"},
            
            { "data": "user_status", sortable:!1,className: "text-center",
                render: function (data, type, row) {
                  var html = '';
                  var status = row.user_status==1 ? 'Active' :'Inactive';
                  var addClass = status=='Active' ? '' : 'inactiveClass';
                  html ='<span><a href="javascript:void(0)" class="changeStatus '+addClass+'" onclick="triggerStatus('+row.user_id+')">'+status+'</span></a>'
                  return html;
                }
            },
            { "data": "name", sortable:!1,
              render: function (data, type, row) {
                return'<a href="'+base_url+'/admin/viewAppUser/'+row.user_id+'"><img src="'+imagepath+'/ic_eye_color.png"></a>\t\t\t\t\t\t<a href="'+base_url+'/admin/editAppUser/'+row.user_id+'"><img src="'+imagepath+'/ic_mode_edit.png"></a>\t\t\t\t\t\t<a onclick="triggerDelete('+row.user_id+')" href="javascript:void(0)"><img src="'+imagepath+'/ic_delete.png"></a>'
              }
            },
      ],

  });
/*Listing End*/


  $("#search_app_user").on('keyup', function () {
        $('#app_user_listing').DataTable().ajax.reload();
  });

  $('#delete_prompt').on('hidden.bs.modal', function () {
    $("#did").val('');
  })

  $("#status_type").on('change', function () {
        $('#app_user_listing').DataTable().ajax.reload();
  });

  $("#search_category").on('change', function () {
        $('#app_user_listing').DataTable().ajax.reload();
  });

  //LIVE TEST LISTING
  //LISTING
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
              "url": base_url+"/admin/appUser/liveTests",
              "type": "POST",
              "dataType": 'json',
              "data": function ( d ) {
                d.search = $('#search_live_test').val();
                d.search_category = $('#search_category').val();
                d.date_from = $('#search_date_from').val();
                d.date_to = $('#search_date_to').val();
                d.get_user_id = $('#get_user_id').val();
              }
          },
          columns:[
              
              { "data": "index",className: "text-center"},

              { "data": "exam_desc.exam_name", sortable:!1,className: "text-center"},

              { "data": "category", sortable:!1,className: "text-center"},

              { "data": "exam.total_questions",sortable:!1, className: "text-center"},
              
              { "data": "marks", sortable:!1,className: "text-center"},

              { "data": "exam_duration", sortable:!1,className: "text-center",
                render: function (data, type, row) {
                    var html = row.exam.exam_duration;
                    return html+ ' Mins';
                  }
              },
              
              { "data": "date_time", sortable:!1,className: "text-center"},

              { "data": "name", sortable:!1,className: "text-center",
                render: function (data, type, row) {
                  return'<a href="'+base_url+'/admin/appUser/viewUserTest/'+row.user_attempt_id+'" class"text-center"><img src="'+imagepath+'/ic_eye_color.png"></a>'
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
    
    $("#search_category").on('change', function () {
        $('#live_test_listing').DataTable().ajax.reload();
    });
    /*Listing End*/



  //Practice LISTING
  //LISTING
    $('#practice_test_listing').on( 'processing.dt', function ( e, settings, processing ) {
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
              "url": base_url+"/admin/appUser/practiceTest",
              "type": "POST",
              "dataType": 'json',
              "data": function ( d ) {
                d.search = $('#search_practice_test').val();
                d.search_category = $('#search_category1').val();
                d.get_user_id = $('#get_user_id').val();
              }
          },
          columns:[
              
              { "data": "index",className: "text-center"},

              // { "data": "exam_desc.exam_name", sortable:!1,className: "text-center"},

              { "data": "category", sortable:!1,className: "text-center"},
              
              { "data": "action", sortable:!1,className: "text-center",
                render: function (data, type, row) {
                  return'<a href="'+base_url+'/admin/appUser/viewUserTest/'+row.user_attempt_id+'" class"text-center"><img src="'+imagepath+'/ic_eye_color.png"></a>'
                }
              },
        ],

    });
  
    $("#search_practice_test").on('keyup', function () {
        $('#practice_test_listing').DataTable().ajax.reload();
    });
    $("#search_category1").on('change', function () {
        $('#practice_test_listing').DataTable().ajax.reload();
    });
    /*Listing End*/



    //quiz LISTING
   //LISTING
    $('#quiz_test_listing').on( 'processing.dt', function ( e, settings, processing ) {
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
              "url": base_url+"/admin/appUser/quizTest",
              "type": "POST",
              "dataType": 'json',
              "data": function ( d ) {
                d.search = $('#search_quiz_test').val();
                d.search_category = $('#search_category2').val();
                d.get_user_id = $('#get_user_id').val();
              }
          },
          columns:[
              
              { "data": "index",className: "text-center"},

              { "data": "exam_desc.exam_name", sortable:!1,className: "text-center"},

              { "data": "category", sortable:!1,className: "text-center"},
              
              { "data": "exam.total_questions", sortable:!1,className: "text-center"},
              
              { "data": "marks", sortable:!1,className: "text-center"},

              { "data": "exam_duration", sortable:!1,className: "text-center",
                render: function (data, type, row) {
                    var html = row.exam.exam_duration;
                    return html+ ' Mins';
                  }
              },

              { "data": "action", sortable:!1,className: "text-center",
                render: function (data, type, row) {
                  return'<a href="'+base_url+'/admin/appUser/viewUserTest/'+row.user_attempt_id+'" class"text-center"><img src="'+imagepath+'/ic_eye_color.png"></a>'
                }
              },
        ],

    });
  
    $("#search_quiz_test").on('keyup', function () {
        $('#quiz_test_listing').DataTable().ajax.reload();
    });
    $("#search_category2").on('change', function () {
        $('#quiz_test_listing').DataTable().ajax.reload();
    });
    /*Listing End*/


    //quiz LISTING
   //LISTING
    $('#gk_quiz_test_listing').on( 'processing.dt', function ( e, settings, processing ) {
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
              "url": base_url+"/admin/appUser/gkQuizTest",
              "type": "POST",
              "dataType": 'json',
              "data": function ( d ) {
                d.search = $('#search_gk_quiz_test').val();
                d.get_user_id = $('#get_user_id').val();
              }
          },
          columns:[
              
              { "data": "index",className: "text-center"},

              { "data": "exam_desc.exam_name", sortable:!1,className: "text-center"},

              { "data": "exam.total_questions", sortable:!1,className: "text-center"},
              
              { "data": "marks", sortable:!1,className: "text-center"},

              { "data": "exam_duration", sortable:!1,className: "text-center",
                render: function (data, type, row) {
                    var html = row.exam.exam_duration;
                    return html+ ' Mins';
                  }
              },

              { "data": "action", sortable:!1,className: "text-center",
                render: function (data, type, row) {
                  return'<a href="'+base_url+'/admin/appUser/viewUserTest/'+row.user_attempt_id+'" class"text-center"><img src="'+imagepath+'/ic_eye_color.png"></a>'
                }
              },
        ],

    });
  
    $("#search_gk_quiz_test").on('keyup', function () {
        $('#gk_quiz_test_listing').DataTable().ajax.reload();
    });
    /*Listing End*/




  $("form[name='edit-app-user-profile-form']").validate({
    errorClass: "error_msg",
     rules: {
        name:{
          required:true,
        },
        mobile_number:{
          required:true,
          maxlength:10,
          minlength:10,
        },
        email:{
          required:true,
        },
        category_id:{
          required:true,
        }
     },
      submitHandler: function(form, event){
      event.preventDefault();
      var radioValue = $("input[type='checkbox']:checked").val();
      if (radioValue==undefined) {
          toastr.error('Please select sub-category');
          return false;
      }
      showLoader(true);
      var formData = new FormData($(form)[0]);
      $.ajax({
          url: base_url+'/admin/editAppUser',
          type: 'POST',
          processData: false,
          contentType: false,
          cache: false,              
          data: formData,
          success: function(result)
          {
              if(result.status){
                toastr.success(result.message);
                $('li a[data-slug="admin/appUsers"]').trigger("click");
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

function triggerDelete(cid){
   $('#did').val(cid);   
   $( ".show_delete_modal" ).click();
}

function confirmDelete(){
  showLoader(true);
  var cid = $('#did').val();
  $.ajax({
    url: base_url+'/admin/deleteAppUser',
    type: 'POST',
    dataType:'json',
    cache: false,              
    data: {'cid':cid},
    success: function(result)
    {
        $('#delete_prompt').modal('hide');
        if(result.status){
          toastr.success(result.message);
          $('#app_user_listing').DataTable().ajax.reload();
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



function triggerStatus(cid){
   $('#did').val(cid);   
   $( ".show_status_modal" ).click();
}


function confirmStatus(cid){
  showLoader(true);
  var cid = $('#did').val();
  $.ajax({
    url: base_url+'/admin/statusAppUser',
    type: 'POST',
    dataType:'json',
    cache: false,              
    data: {'cid':cid},
    success: function(result)
    {
        if(result.status){
          $('#status_prompt').modal('hide');
          $('#app_user_listing').DataTable().ajax.reload();
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


$("#parent_category").on('change', function(){

  showLoader(true);
  var cid = $(this).val();
  $.ajax({
    url: base_url+'/admin/getAppUserCategory',
    type: 'POST',
    dataType:'json',
    cache: false,              
    data: {'cid':cid},
    success: function(result)
    {
        if(result.status){
            var aData = [];
            $.each(result.data, function(index, value) {
              if (value.category_desc) {
                $("#get_sub_category").empty();
                  $.each(value.category_desc, function(index1, value1) {
                  if (value1.lang_code=='en') {
                    aData.push(value1);
                  }
                }); 
              }
            });
            if (aData.length >0) {
              for (var i = aData.length - 1; i >= 0; i--) {
                //$('<div class="form-group"><input type="text" class="form-control" value="'+aData[i].name+'" readonly></div>').appendTo("#get_sub_category");
                $('<div class="form-group"><label class="form-control add_col_class"><input type="checkbox" name="active_category[]" class="mybox" style="opacity: 0;" value="'+aData[i].category_id+'">'+aData[i].name+'</label></div>').appendTo("#get_sub_category");
              }
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
  
function selectProfileImage1(input){
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      var filename = input.files[0].name;
      var fileExtension = filename.substr((filename.lastIndexOf('.') + 1));
      var fileExtensionCase = fileExtension.toLowerCase();
      if (fileExtensionCase == 'png' || fileExtensionCase == 'jpeg' || fileExtensionCase == 'jpg' ) {
        reader.onload = function (e) {
            jQuery('#cat1_img').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);        
      }else{
        toastr.error($('#image_validation_msg').val());
        $('#upload_profile1').val('');
        var img_tmp = base_url+"/public/images/user.png";
        $('#cat1_img').attr('src', img_tmp);
      }
  }
}

$(document).on('click', 'input[type="checkbox"]', function(){ 
    $(this).attr('checked',false);
    $(this).parent().removeClass('active_category');
    $('.mybox:checked').each(function(){
      if ($(this).val().length >0) {
        $(this).parent().addClass('active_category');
      }else{
        $('.add_col_class').removeClass('active_category');  
      }

    });

    /*if($(this). prop("checked") == true){
      $(this).parent().addClass('active_category');
    }else if($(this). prop("checked") == false){
      $('.add_col_class').removeClass('active_category');
    }*/
}); 
/*
$('input[type="checkbox"]'). click(function(){
if($(this). prop("checked") == true){
console.log("Checkbox is checked." );
}
else if($(this). prop("checked") == false){
console.log("Checkbox is unchecked." );
}*/

/*$("input[name='active_category']").on('click', function () {

});*/

if ($('#errors').val().length > 3) {
  toastr.error($('#errors').val());
}