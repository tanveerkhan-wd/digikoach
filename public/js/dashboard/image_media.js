//Liting
$(function() {

  var imagepath= base_url+'/public/images/';
  $('#image_listing').on( 'processing.dt', function ( e, settings, processing ) {
        if(processing){
          showLoader(true);
        }else{
          showLoader(false);
        }
    } ).DataTable({
        "columnDefs": [{
          "targets": 3,
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
            "url": base_url+"/admin/imageMedias",
            "type": "POST",
            "dataType": 'json',
            "data": function ( d ) {
              d.search = $('#search_image').val();
            }
        },
        columns:[
            { "data": "index",className: "text-center"},
            
            { "data": "image_media_id",className: "text-center"},
            { "data": "file",className: "text-center"},

            { "data": "file",className: "text-center",
              render: function (data, type, row) {
                return'<img src="'+base_url+'/public/storage/'+row.image+'" style="width:auto;height:50px;">'
              }
            },
            
            { "data": "Action", sortable:!1,
              render: function (data, type, row) {
                return'<a onclick="triggerDelete('+row.image_media_id+')" href="javascript:void(0)"><img src="'+imagepath+'/ic_delete.png"></a>'
              }
            },
      ],

  });
/*Listing End*/


  $("#search_image").on('keyup', function () {
        $('#image_listing').DataTable().ajax.reload();
  });

  $('#delete_prompt').on('hidden.bs.modal', function () {
    $("#did").val('');
  })

  $("form[name='add-image-form']").validate({
    errorClass: "error_msg",
     rules: {
        image:{
          required:true,
        }
     },
      submitHandler: function(form, event) {
       event.preventDefault();
       showLoader(true);
        var formData = new FormData($(form)[0]);
        $.ajax({
          url: base_url+'/admin/addImageMediaPost',
          type: 'POST',
          processData: false,
          contentType: false,
          cache: false,              
          data: formData,
          success: function(result)
          {
              if(result.status){
                toastr.success(result.message);
                $('li a[data-slug="admin/imageMedia"]').trigger("click");
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
    url: base_url+'/admin/deleteImageMedia',
    type: 'POST',
    dataType:'json',
    cache: false,              
    data: {'cid':cid},
    success: function(result)
    {
        $('#delete_prompt').modal('hide');
        if(result.status){
          toastr.success(result.message);
          $('#image_listing').DataTable().ajax.reload();
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
   $( ".show_delete_modal").click();
}

    $(function() {
    // Multiple images preview in browser
    var imagesPreview = function(input, placeToInsertImagePreview) {
        if (input.files) {
            var filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();
                  reader.onload = function(e) {
                var file = e.target;
                $(placeToInsertImagePreview).append("<span class=\"pip\"><img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/></span>"); 
                };

                reader.readAsDataURL(input.files[i]);
                //console.log(input.files[i]);
            }
        }

    };


    $('#imagemedia').on('change', function() {
        imagesPreview(this, 'div.imagemediaGallery');
    });

});