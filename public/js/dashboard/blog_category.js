//Liting
$(function() {

  var imagepath= base_url+'/public/images/';
  $('#blog_cat_listing').on( 'processing.dt', function ( e, settings, processing ) {
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
            "url": base_url+"/admin/blogCategory",
            "type": "POST",
            "dataType": 'json',
            "data": function ( d ) {
              d.search = $('#search_blog_cat').val();
              d.status_type = $('#status_type').val();
            }
        },
        columns:[
            { "data": "index",className: "text-center"},
            
            { "data": "name_hi",sortable:!1,className: "text-center"},
            { "data": "name_en",sortable:!1,className: "text-center"},
            { "data": "status", sortable:!1,className: "text-center",
                render: function (data, type, row) {
                  var html = '';
                  var status = row.status==1 ? 'Active' :'Inactive';
                  var addClass = status=='Active' ? '' : 'inactiveClass';
                  if (row.status_access==true) {
                      html ='<span><a href="javascript:void(0)" class="changeStatus '+addClass+'" onclick="triggerStatus('+row.blog_category_id+')">'+status+'</span></a>'
                  }else{
                      html = status
                  }
                  return html;
                }
            },
            { "data": "status", sortable:!1,
              render: function (data, type, row) {
                var html = '';
                var deleted = '';
                var editBlog = '';
                if (row.deleted_access==true) {
                    deleted = '<a onclick="triggerDelete('+row.blog_category_id+')" href="javascript:void(0)"><img src="'+imagepath+'/ic_delete.png"></a>'
                }
                if (row.edit_access==true) {
                    editBlog = '<a cid="'+row.blog_category_id+'" onclick="triggerEdit('+row.blog_category_id+')" href="javascript:void(0)"><img src="'+imagepath+'/ic_mode_edit.png"></a>'
                }
                html = ''+editBlog+' \t\t\t\t\t\t '+deleted+''
                return html
              }
            },
      ],

  });
/*Listing End*/


  $("#search_blog_cat").on('keyup', function () {
        $('#blog_cat_listing').DataTable().ajax.reload();
  });

  
  $("#status_type").on('change', function () {
        $('#blog_cat_listing').DataTable().ajax.reload();
  });

  $('#add_new').on('hidden.bs.modal', function () {
    var validator = $( "form[name='add-category-form']" ).validate();
    validator.resetForm();
    $("form").trigger("reset");
    $("#pkCat").val('');
  })

  $('#add_new').on('shown.bs.modal', function () {
    $("form").data('validator').resetForm();
  })

  $('#delete_prompt').on('hidden.bs.modal', function () {
    $("#did").val('');
  })

//ADD NEW BLOG CATEGORY
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
          url: base_url+'/admin/addBlogCategory',
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
                $('#blog_cat_listing').DataTable().ajax.reload();
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

//DELETE BLOG CATEGORY
function confirmDelete(){
  showLoader(true);
  var cid = $('#did').val();
  $.ajax({
    url: base_url+'/admin/deleteBlogCategory',
    type: 'POST',
    dataType:'json',
    cache: false,              
    data: {'cid':cid},
    success: function(result)
    {
        $('#delete_prompt').modal('hide');
        if(result.status){
          toastr.success(result.message);
          $('#blog_cat_listing').DataTable().ajax.reload();
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

//EDIT BLOG CATEGORY
function triggerEdit(cid){
  showLoader(true);
  $.ajax({
    url: base_url+'/admin/editBlogCategory',
    type: 'POST',
    dataType:'json',
    cache: false,              
    data: {'cid':cid},
    success: function(result)
    {
        if(result.status){
          console.log(result.data);
          $.each(result.data.blog_cat_desc, function(index, value) {
            $("#name_"+value.lang_code).val(value.blog_category_title);
          });
          $("#blog_status").val(result.data.status);
          $("#blog_status").append(result.data.status);
          $("#pkCat").val(result.data.blog_category_id);
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

function triggerStatus(cid){
   $('#did').val(cid);   
   $( ".show_status_modal" ).click();
}

function confirmStatus(cid){
  showLoader(true);
  var cid = $('#did').val();
  $.ajax({
    url: base_url+'/admin/statusBlogCategory',
    type: 'POST',
    dataType:'json',
    cache: false,              
    data: {'cid':cid},
    success: function(result)
    {
        if(result.status){
          $('#status_prompt').modal('hide');
          $('#blog_cat_listing').DataTable().ajax.reload();
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