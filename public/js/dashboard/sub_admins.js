
$(function() {

    // $(".active_disabled").attr('disabled',true);
    // $('.acc_pri_view:checkbox:checked').closest('.row').find('.active_disabled').attr('disabled',false);

    $(".acc_pri_view").on('click', function () {
        if ($(this).prop("checked")==true) {
          //$(this).closest('.row').find('.active_disabled').attr('disabled',false);
        }else{
          $(this).closest('.row').find('.active_disabled').prop('checked',false);
          //$(this).closest('.row').find('.active_disabled').attr('disabled',true);
        }
    });
    $(".acc_pri_other").on('click', function () {
        if ($(this).prop("checked")==true) {
          $(this).closest('.row').find('.acc_pri_view').prop('checked',true);
          //$(this).closest('.row').find('.active_disabled').attr('disabled',false);
        }
    });

    var imagepath= base_url+'/public/images/';
    $('#sub_admin_listing').on( 'processing.dt', function ( e, settings, processing ) {
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
              "url": base_url+"/admin/subAdmins",
              "type": "POST",
              "dataType": 'json',
              "data": function ( d ) {
                d.search = $('#search_sub_admin').val();
                d.status_type = $('#status_type').val();
              }
          },
          columns:[
              
              { "data": "index",className: "text-center"},
              
              { "data": "name",className: "text-center"},

              { "data": "mobile_number",className: "text-center"},
              
              { "data": "email",className: "text-center"},
              
              { "data": "user_id",className: "text-center",
                 render: function (data, type, row) {
                    return '<a href="'+base_url+'/admin/accessPrivileges/'+row.user_id+'" class="btn btn-success btn-sm">Access</a>';
                 }
              },
              { "data": "created_at",className: "text-center"},
              { "data": "user_status",className: "text-center",
                  render: function (data, type, row) {
                  var html = '';
                  var status = row.user_status==1 ? 'Active' :'Inactive';
                  var addClass = status=='Active' ? '' : 'inactiveClass';
                  html ='<span><a href="javascript:void(0)" class="changeStatus '+addClass+'" onclick="triggerStatus('+row.user_id+')">'+status+'</span></a>'
                  return html;
                }
              },
              { "data": "Action", sortable:!1,
                render: function (data, type, row) {
                  return'<a class="ajax_request" data-slug="admin/viewSubAdmin/'+row.user_id+'" href="'+base_url+'/admin/viewSubAdmin/'+row.user_id+'"><img src="'+imagepath+'/ic_eye_color.png"></a>\t\t\t\t\t\t<a  class="ajax_request" data-slug="admin/editSubAdmin/'+row.user_id+'" href="'+base_url+'/admin/editSubAdmin/'+row.user_id+'"><img src="'+imagepath+'/ic_mode_edit.png"></a>\t\t\t\t\t\t<a onclick="triggerDelete('+row.user_id+')" href="javascript:void(0)"><img src="'+imagepath+'/ic_delete.png"></a>'
                }
              },
        ],

    });
  /*Listing End*/


    $("#search_sub_admin").on('keyup', function () {
          $('#sub_admin_listing').DataTable().ajax.reload();
    });

    $('#delete_prompt').on('hidden.bs.modal', function () {
      $("#did").val('');
    })

    $("#status_type").on('change', function () {
          $('#sub_admin_listing').DataTable().ajax.reload();
    });



    
    $("form[name='add-sub-admin-form']").validate({
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
          }
       },
        submitHandler: function(form, event){
         event.preventDefault();
         showLoader(true);

        var formData = new FormData($(form)[0]);
        $.ajax({
            url: base_url+'/admin/addSubAdminPost',
            type: 'POST',
            processData: false,
            contentType: false,
            cache: false,              
            data: formData,
            success: function(result)
            {
                if(result.status){
                  toastr.success(result.message);
                  $('li a[data-slug="admin/subAdmin"]').trigger("click");
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

 
    $("form[name='give-access-form']").validate({
      errorClass: "error_msg",
       rules: {
          
       },
        submitHandler: function(form, event){
         event.preventDefault();
         showLoader(true);

        var formData = new FormData($(form)[0]);
        $.ajax({
            url: base_url+'/admin/accessPrivilegePost',
            type: 'POST',
            processData: false,
            contentType: false,
            cache: false,              
            data: formData,
            success: function(result)
            {
                if(result.status){
                  toastr.success(result.message);
                  $('li a[data-slug="admin/subAdmin"]').trigger("click");
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
    url: base_url+'/admin/deleteSubAdmin',
    type: 'POST',
    dataType:'json',
    cache: false,              
    data: {'cid':cid},
    success: function(result)
    {
        $('#delete_prompt').modal('hide');
        if(result.status){
          toastr.success(result.message);
          $('#sub_admin_listing').DataTable().ajax.reload();
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
    url: base_url+'/admin/statusSubAdmin',
    type: 'POST',
    dataType:'json',
    cache: false,              
    data: {'cid':cid},
    success: function(result)
    {
        if(result.status){
          $('#status_prompt').modal('hide');
          $('#sub_admin_listing').DataTable().ajax.reload();
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


$("#upload_profile").on('change', function () { 
  if( document.getElementById("upload_profile").files.length == 0 ){
      $('#user_img').attr('src', $('#img_tmp').val());
  }
    selectProfileImage(this);
});
function selectProfileImage(input){
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      var filename = input.files[0].name;
      var fileExtension = filename.substr((filename.lastIndexOf('.') + 1));
      var fileExtensionCase = fileExtension.toLowerCase();
      if (fileExtensionCase == 'png' || fileExtensionCase == 'jpeg' || fileExtensionCase == 'jpg' ) {
        reader.onload = function (e) {
            jQuery('#img_tmp').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);        
      }else{
        toastr.error($('#image_validation_msg').val());
        $('#upload_profile').val('');
        var img_tmp = base_url+"/public/images/user.png";
        
        $('#img_tmp').attr('src', img_tmp);
        
      }
  }
}