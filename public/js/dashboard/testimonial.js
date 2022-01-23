//Liting
var img_base_name = $('#img_base_name').val();
$(function() {

  $("#upload_profile").on('change', function () { 
      if( document.getElementById("upload_profile").files.length == 0 ){
          $('#testimonial_img').attr('src', $('#img_tmp').val());
      }
        selectProfileImage(this);
  });

  var imagepath= base_url+'/public/images/';
  $('#testimonial_listing').on( 'processing.dt', function ( e, settings, processing ) {
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
            "url": base_url+"/admin/testimonial",
            "type": "POST",
            "dataType": 'json',
            "data": function ( d ) {
              d.search = $('#search_testimonial').val();
            }
        },
        columns:[
            { "data": "index",className: "text-center"},
            { "data": "name_hi",sortable:!1,className: "text-center"},
            { "data": "name_en",sortable:!1,className: "text-center"},

            { "data": "image",sortable:!1,className: "text-center",
              render: function (data, type, row) {
                if (row.image) {
                  var image_url = base_url+'/public/storage/'+img_base_name+'/'+row.image
                }else{
                  var image_url = base_url+'/public/images/user.png'
                }
                  return '<img src="'+image_url+'" style="width:60px;height:auto">'
              }
            },
            { "data": "sequence",className: "text-center"},
            
            { "data": "created_date", sortable:!1,
              render: function (data, type, row) {
                return'<a cid="'+row.testimonial_id+'" onclick="triggerEdit('+row.testimonial_id+')" href="javascript:void(0)"><img src="'+imagepath+'/ic_mode_edit.png"></a>\t\t\t\t\t\t<a onclick="triggerDelete('+row.testimonial_id+')" href="javascript:void(0)"><img src="'+imagepath+'/ic_delete.png"></a>'
              }
            },
      ],

  });
/*Listing End*/

  $("#search_testimonial").on('keyup', function () {
        $('#testimonial_listing').DataTable().ajax.reload();
  });

    $('#add_new').on('hidden.bs.modal', function () {
    var validator = $( "form[name='add-testimonial-form']" ).validate();
    validator.resetForm();
    var img_tmp = base_url+"/public/images/user.png";
    $('#testimonial_img').attr('src', img_tmp);
    $("form").trigger("reset");
    $("#pkCat").val('');
  })

  $('#add_new').on('shown.bs.modal', function () {
    $("form").data('validator').resetForm();
  })

  $('#delete_prompt').on('hidden.bs.modal', function () {
    $("#did").val('');
  })

  $("form[name='add-testimonial-form']").validate({
    errorClass: "error_msg",
     rules: {
        name_en:{
          required:true,
        },
        name_hi:{
          required:true,
        },
        desc_en:{
          required:true,
        },
        desc_hi:{
          required:true,
        },
        sequence:{
          required:true,
        }
     },
      submitHandler: function(form, event) {
       event.preventDefault();
       showLoader(true);
      var formData = new FormData($(form)[0]);
      $.ajax({
          url: base_url+'/admin/addTestimonial',
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
                $('#testimonial_listing').DataTable().ajax.reload();
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
    url: base_url+'/admin/deleteTestimonial',
    type: 'POST',
    dataType:'json',
    cache: false,              
    data: {'cid':cid},
    success: function(result)
    {
        $('#delete_prompt').modal('hide');
        if(result.status){
          toastr.success(result.message);
          $('#testimonial_listing').DataTable().ajax.reload();
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
    url: base_url+'/admin/editTestimonial',
    type: 'POST',
    dataType:'json',
    cache: false,              
    data: {'cid':cid},
    success: function(result)
    {
        if(result.status){
          $.each(result.data.testimonial_desc, function(index, value) {
            $("#name_"+value.lang_code).val(value.testimonial_name);
            $("#desc_"+value.lang_code).val(value.description);
          });

          $("#sequence").val(result.data.sequence);
          $("#pkCat").val(result.data.testimonial_id);
          if (result.data.image) {
              $("#testimonial_img").attr('src',base_url+'/public/storage/'+img_base_name+'/'+result.data.image);
          }else{
              $("#testimonial_img").val(base_url+'/public/images/user.png');
          }
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
            jQuery('#testimonial_img').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);        
      }else{
        toastr.error($('#image_validation_msg').val());
        $('#upload_profile').val('');
        var img_tmp = base_url+"/public/images/user.png";
        $('#testimonial_img').attr('src', img_tmp);
      }
  }
}
