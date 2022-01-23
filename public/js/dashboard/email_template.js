$(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('message');
    //bootstrap WYSIHTML5 - text editor   
  });

//Liting
$(function() {
  
var imagepath= base_url+'/public/images/';
  $('#email_template_listing').on( 'processing.dt', function ( e, settings, processing ) {
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
            "url": base_url+"/admin/emailTemplates",
            "type": "POST",
            "dataType": 'json',
            "data": function ( d ) {
              d.search = $('#search_email_template').val();
            }
        },
        columns:[
            { "data": "index",className: "text-center"},
            { "data": "title" },
            { "data": "parameters" },
            { "data": "subject"},
            { "data": "title", sortable:!1,
              render: function (data, type, row) {
                    return'<a href="'+base_url+'/admin/emailTemplate/edit/'+row.email_master_id+'"><img src="'+imagepath+'/ic_mode_edit.png"></a>'
              }
            },
      ],

  });


  $("#search_email_template").on('keyup', function () {
    
        $('#email_template_listing').DataTable().ajax.reload()
   
  });


});

  $('#logo-form').validate({
     errorPlacement: function (error, element) {
            var name = $(element).attr("name");
            error.appendTo($("#" + name + "_validate"));
    },
  });



  $("form[name='edit-form']").validate({
    errorClass: "error_msg",
     rules: {
        subject:{
          required:true,
        },
        content:{
          required:true,
        }
     },
      submitHandler: function(form, event) {
      //form.submit();
       event.preventDefault();
       showLoader(true);
       for ( instance in CKEDITOR.instances )
      CKEDITOR.instances[instance].updateElement();
      var formData = new FormData($(form)[0]);
      $.ajax({
          url: base_url+'/admin/emailTemplate/edit',
          type: 'POST',
          processData: false,
          contentType: false,
          cache: false,              
          data: formData,
          success: function(result)
          {
              console.log('result',result);
              if(result.status){
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
  });