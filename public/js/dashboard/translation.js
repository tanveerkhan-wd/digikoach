
//Liting
$(function() {

  var imagepath= base_url+'/public/images/';
  $('#translation_listing').on( 'processing.dt', function ( e, settings, processing ) {
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
            "url": base_url+"/admin/translation",
            "type": "POST",
            "dataType": 'json',
            "data": function ( d ) {
              d.search = $('#search_translation').val();
            }
        },
        columns:[
            { "data": "index",className: "text-center"},
            

            { "data": "group",sortable:!1,className: "text-center"},
            { "data": "key",sortable:!1,className: "text-center"},
            
            { "data": "name_en",sortable:!1,className: "text-center"},
            { "data": "name_hi",sortable:!1,className: "text-center"},
            
            { "data": "group", sortable:!1,
              render: function (data, type, row) {
                return'<a cid="'+row.translation_id+'" onclick="triggerEdit('+row.translation_id+')" href="javascript:void(0)"><img src="'+imagepath+'/ic_mode_edit.png"></a>\t\t\t\t\t\t<a onclick="triggerDelete('+row.translation_id+')" href="javascript:void(0)"><img src="'+imagepath+'/ic_delete.png"></a>'
              }
            },
      ],

  });
/*Listing End*/


  $("#search_translation").on('keyup', function () {
        $('#translation_listing').DataTable().ajax.reload();
  });

  $('#add_new').on('hidden.bs.modal', function () {
    var validator = $( "form[name='add-translation-form']" ).validate();
    validator.resetForm();
    $("form").trigger("reset");
  })

  $('#add_new').on('shown.bs.modal', function () {
    $("form").data('validator').resetForm();
  })

  $('#delete_prompt').on('hidden.bs.modal', function () {
    $("#did").val('');
  })

  $("form[name='add-translation-form']").validate({
    errorClass: "error_msg",
     rules: {
        name_en:{
          required:true,
        },
        name_hi:{
          required:true,
        },
        group:{
          required:true,
        },
        key:{
          required:true,
        }
     },
      submitHandler: function(form, event) {
      event.preventDefault();
      showLoader(true);
      var formData = new FormData($(form)[0]);
      $.ajax({
          url: base_url+'/admin/addTranslation',
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
                $('#translation_listing').DataTable().ajax.reload();
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
    url: base_url+'/admin/deleteTranslation',
    type: 'POST',
    dataType:'json',
    cache: false,              
    data: {'cid':cid},
    success: function(result)
    {
        $('#delete_prompt').modal('hide');
        if(result.status){
          toastr.success(result.message);
          $('#translation_listing').DataTable().ajax.reload();
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
    url: base_url+'/admin/editTranslation',
    type: 'POST',
    dataType:'json',
    cache: false,              
    data: {'cid':cid},
    success: function(result)
    {
      console.log(result.data);
        if(result.status){
          $("#name_en").val(result.data.name_en);
          $("#name_hi").val(result.data.name_hi);
          $("#pkCat").val(result.data.translation_id);
          
          $("#key").val(result.data.key);
          $("#group").val(result.data.group);

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

/*$.ajaxSetup({
headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});*/