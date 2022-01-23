
//Liting
var img_base_name = $('#img_base_name').val();
$(function() {
  var imagepath= base_url+'/public/images/';
  $('#banner_listing').on( 'processing.dt', function ( e, settings, processing ) {
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
            "url": base_url+"/admin/bannerImage",
            "type": "POST",
            "dataType": 'json',
            "data": function ( d ) {
              d.search = $('#search_banner').val();
            }
        },
        columns:[
            { "data": "index",className: "text-center"},
            
            { "data": "hours",sortable:!1,className: "text-center",
                render: function (data, type, row) {
                  if (row.banner_file_hi) {
                    var image_url = base_url+'/public/storage/'+img_base_name+'/'+row.banner_file_hi
                  }else{
                    var image_url = base_url+'/public/images/user.png'
                  }
                  return '<img src="'+image_url+'" style="width:60px;height:auto">'
                }
            },

            { "data": "hours",sortable:!1,className: "text-center",
                render: function (data, type, row) {
                  if (row.banner_file_en) {
                    var image_url = base_url+'/public/storage/'+img_base_name+'/'+row.banner_file_en
                  }else{
                    var image_url = base_url+'/public/images/user.png'
                  }
                  return '<img src="'+image_url+'" style="width:60px;height:auto">'
                }
            },

            { "data": "sequence",className: "text-center"},
/*
            { "data": "hours",className: "text-center"},*/
            
            { "data": "created_at",className: "text-center",
              render: function (data, type, row) {
                return row.date;
              }
            },
            
            { "data": "sequence", sortable:!1,
              render: function (data, type, row) {
                return'<a cid="'+row.banner_id+'" onclick="triggerEdit('+row.banner_id+')" href="javascript:void(0)"><img src="'+imagepath+'/ic_mode_edit.png"></a>\t\t\t\t\t\t<a onclick="triggerDelete('+row.banner_id+')" href="javascript:void(0)"><img src="'+imagepath+'/ic_delete.png"></a>'
              }
            },
      ],

  });
/*Listing End*/


  $("#search_banner").on('keyup', function () {
        $('#banner_listing').DataTable().ajax.reload();
  });

  $('#add_new').on('hidden.bs.modal', function () {
    var validator = $( "form[name='add-banner-form']" ).validate();
    validator.resetForm();
    var img_tmp = base_url+"/public/images/user.png";
    $('#cat_img').attr('src', img_tmp);
    $('#cat1_img').attr('src', img_tmp);
    $("form").trigger("reset");
    $("#pkCat").val('');
  })

  $('#add_new').on('shown.bs.modal', function () {
    $("form").data('validator').resetForm();
  })

  $('#delete_prompt').on('hidden.bs.modal', function () {
    $("#did").val('');
  })

  $("form[name='add-banner-form']").validate({
    errorClass: "error_msg",
     rules: {
        sequence:{
          required:true,
        }
     },
      submitHandler: function(form, event){
      event.preventDefault();
      showLoader(true);
      var formData = new FormData($(form)[0]);
      $.ajax({
          url: base_url+'/admin/addBannerImage',
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
                $('#banner_listing').DataTable().ajax.reload();
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


function triggerEdit(cid){
  showLoader(true);
  $.ajax({
    url: base_url+'/admin/editBannerImage',
    type: 'POST',
    dataType:'json',
    cache: false,              
    data: {'cid':cid},
    success: function(result)
    {
      console.log(result.data);
        if(result.status){
          $.each(result.data.banner_desc, function(index, value) {
              if(value.lang_code=='en' && value.banner_file){
                  $("#cat_img").attr('src',base_url+'/public/storage/'+img_base_name+'/'+value.banner_file);
              }else if(value.lang_code=='hi' && value.banner_file){
                  $("#cat1_img").attr('src',base_url+'/public/storage/'+img_base_name+'/'+value.banner_file);              
              }else{
                  var img_tmp = base_url+"/public/images/user.png";
                  $("#img_tmp").val(base_url+'/public/images/'+img_tmp);
              }
          });

          $("#pkCat").val(result.data.banner_id);

          $("#sequence").val(result.data.sequence);
          /*$("#hours").val(result.data.hours);*/
          
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

function confirmDelete(){
  showLoader(true);
  var cid = $('#did').val();
  $.ajax({
    url: base_url+'/admin/deleteBannerImage',
    type: 'POST',
    dataType:'json',
    cache: false,              
    data: {'cid':cid},
    success: function(result)
    {
        $('#delete_prompt').modal('hide');
        if(result.status){
          toastr.success(result.message);
          $('#banner_listing').DataTable().ajax.reload();
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
        $('#cat_img').attr('src', $('#img_tmp').val());
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

$("#upload_profile1").on('change', function () { 
    if( document.getElementById("upload_profile1").files.length == 0 ){
        $('#cat1_img').attr('src', $('#img_tmp1').val());
    }
      selectProfileImage1(this);
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