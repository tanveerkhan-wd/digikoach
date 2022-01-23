//Liting
var img_base_name = $('#img_base_name').val();
$(function() {

  $("#upload_profile").on('change', function () { 
      if( document.getElementById("upload_profile").files.length == 0 ){
          $('#user_img').attr('src', $('#img_tmp').val());
      }
        selectProfileImage(this);
  });

  var imagepath= base_url+'/public/images/';
  $('#email_template_listing').on( 'processing.dt', function ( e, settings, processing ) {
        if(processing){
          showLoader(true);
        }else{
          showLoader(false);
        }
    } ).DataTable({
        "columnDefs": [{
          "targets": 7,
          "createdCell": function (td, cellData, rowData, row, col) {
            if ( cellData == 0 ) {
              $(td).addClass('active_status');
            }else{
              $(td).addClass('disable_status');
            }
          }
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
            "url": base_url+"/admin/category",
            "type": "POST",
            "dataType": 'json',
            "data": function ( d ) {
              d.search = $('#search_email_template').val();
              d.status_type = $('#status_type').val();
            }
        },
        columns:[
            { "data": "index",className: "text-center"},
            { "data": "icon_img",sortable:!1,className: "text-center",
              render: function (data, type, row) {
                if (row.icon_img) {
                  var image_url = base_url+'/public/storage/'+img_base_name+'/'+row.icon_img
                }else{
                  var image_url = base_url+'/public/images/user.png'
                }
                return '<img src="'+image_url+'" style="width:60px;height:auto">'
              }
            },
            { "data": "name_hi",sortable:!1,className: "text-center"},
            { "data": "name_en",sortable:!1,className: "text-center"},

            { "data": "prt_cat_hi",sortable:!1,className: "text-center"},
            { "data": "prt_cat_en",sortable:!1,className: "text-center"},
            
            { "data": "gk_ca",className: "text-center",
              render: function (data, type, row) {
                var html = '';
                if (row.parent_category==0) {
                  html = row.gk_ca==1?'Active' : 'Inactive';
                }else{
                  html = 'NA';
                }
                return html
              }
            },
            { "data": "status", sortable:!1,className: "text-center",
                render: function (data, type, row) {
                  var status = row.status==1 ? 'Active' :'Inactive';
                  return '<span><a href="javascript:void(0)" onclick="triggerStatus('+row.category_id+')">'+status+'</span></a>'
                }
            },
            { "data": "gk_ca", sortable:!1,
              render: function (data, type, row) {
                return'<a cid="'+row.category_id+'" onclick="triggerEdit('+row.category_id+')" href="javascript:void(0)"><img src="'+imagepath+'/ic_mode_edit.png"></a>\t\t\t\t\t\t<a onclick="triggerDelete('+row.category_id+')" href="javascript:void(0)"><img src="'+imagepath+'/ic_delete.png"></a>'
              }
            },
      ],

  });
/*Listing End*/


  $('input[name=is_parent]').change(function(){
    var value = $('input[name=is_parent]:checked').val();
    if(value==0){
      $(".gk_ca_box").addClass('hide_content');
      $("#gk_ca").prop("checked", false);
      $('#show_parent_category').removeClass('hide_content');
    }else{
      $('#parent_category option:eq(0)').prop('selected',true);
      $(".gk_ca_box").removeClass('hide_content');
      $('#show_parent_category').addClass('hide_content');
    }
  });

  $("#status_type").on('change', function () {
        $('#email_template_listing').DataTable().ajax.reload();
  });


  $("#search_email_template").on('keyup', function () {
        $('#email_template_listing').DataTable().ajax.reload();
  });

  $('#add_new').on('hidden.bs.modal', function () {
    var validator = $( "form[name='add-category-form']" ).validate();
    validator.resetForm();
    var img_tmp = base_url+"/public/images/user.png";
    $('#cat_img').attr('src', img_tmp);
    $("form").trigger("reset");
    $("#pkCat").val('');
  
  });

  $('#add_new').on('shown.bs.modal', function () {
    $("form").data('validator').resetForm();
    //$('.is_parent_yes').attr('checked',true);
  })

  $('#delete_prompt').on('hidden.bs.modal', function () {
    $("#did").val('');
  })


  $('#status_prompt').on('hidden.bs.modal', function () {
    $("#did").val('');
  })


  $("form[name='add-category-form']").validate({
    errorClass: "error_msg",
     rules: {
        name_en:{
          required:true,
        },
        name_hi:{
          required:true,
        }
     },
      submitHandler: function(form, event) {
       event.preventDefault();
       showLoader(true);
      var formData = new FormData($(form)[0]);
      $.ajax({
          url: base_url+'/admin/addcategory',
          type: 'POST',
          processData: false,
          contentType: false,
          cache: false,              
          data: formData,
          success: function(result)
          {
              if(result.status){
                toastr.success(result.message);
                $('#add_new').modal('hide');
                $('#email_template_listing').DataTable().ajax.reload();
                $('li a[data-slug="admin/category"]').trigger("click");
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


function confirmDelete(){
  showLoader(true);
  var cid = $('#did').val();
  $.ajax({
    url: base_url+'/admin/deleteCategory',
    type: 'POST',
    dataType:'json',
    cache: false,              
    data: {'cid':cid},
    success: function(result)
    {
        $('#delete_prompt').modal('hide');
        if(result.status){
          toastr.success(result.message);
          $('li a[data-slug="admin/category"]').trigger("click");
          $('#email_template_listing').DataTable().ajax.reload();
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

function triggerEdit(cid){
  showLoader(true);
  $.ajax({
    url: base_url+'/admin/editCategory',
    type: 'POST',
    dataType:'json',
    cache: false,              
    data: {'cid':cid},
    success: function(result)
    {
        if(result.status){
          $.each(result.data.category_desc, function(index, value) {
            $("#name_"+value.lang_code).val(value.name);
          });
          $("#pkCat").val(result.data.category_id);
          if (result.data.parent_category.length!=0) {
            $('#show_parent_category').removeClass('hide_content');
            $(".is_parent_no").prop("checked", true);
            $(".gk_ca_box").addClass('hide_content');
            if ($('#parent_category').find("option[value='" + result.data.parent_category[0].category_id + "']").length) {
              $('#parent_category').val(result.data.parent_category[0].category_id).trigger('change');            
              $('#parent_category').attr('checked',true).trigger('change');            
            }

          }else{
            $(".is_parent_yes").prop("checked", true);
            $('#parent_category option:eq(0)').prop('selected',true);
            $(".gk_ca_box").removeClass('hide_content');
            $('#show_parent_category').addClass('hide_content');
          }

          
          if (result.data.gk_ca==1) {
            $("input[name='gk_ca']").prop('checked', true);
          }else{
            $("input[name='gk_ca']").prop('checked', false);
          }

          $("#cat_img").attr('src',base_url+'/public/storage/'+img_base_name+'/'+result.data.icon_img);
          $( ".show_modal" ).click();
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

$.ajaxSetup({
headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});

function selectProfileImage(input){
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      var filename = input.files[0].name;
      var fileExtension = filename.substr((filename.lastIndexOf('.') + 1));
      var fileExtensionCase = fileExtension.toLowerCase();
      if (fileExtensionCase == 'png' || fileExtensionCase == 'jpeg' || fileExtensionCase == 'jpg' ) {
        reader.onload = function (e) {
            jQuery('#cat_img').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);        
      }else{
        toastr.error($('#image_validation_msg').val());
        $('#upload_profile').val('');
        var img_tmp = base_url+"/public/images/user.png";
        $('#cat_img').attr('src', img_tmp);
      }
  }
}

function triggerStatus(cid){
   $('#did').val(cid);   
   $( ".show_status_modal" ).click();
}

function confirmStatus(cid){
  showLoader(true);
  var cid = $('#did').val();
  $.ajax({
    url: base_url+'/admin/statusCategory',
    type: 'POST',
    dataType:'json',
    cache: false,              
    data: {'cid':cid},
    success: function(result)
    {
        if(result.status){
          $('#status_prompt').modal('hide');
          $('#email_template_listing').DataTable().ajax.reload();
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