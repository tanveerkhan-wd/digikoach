$(function () {
    CKEDITOR.replace('view_desc_hi');
    CKEDITOR.replace('view_desc_en');
  });

//Liting
$(function() {

  $("#upload_profile").on('change', function () { 
      if( document.getElementById("upload_profile").files.length == 0 ){
          $('#user_img').attr('src', $('#img_tmp').val());
      }
        selectProfileImage(this);
  });

  var imagepath= base_url+'/public/images/';
  $('#blog_listing').on( 'processing.dt', function ( e, settings, processing ) {
        if(processing){
          showLoader(true);
        }else{
          showLoader(false);
        }
    } ).DataTable({
        "columnDefs": [{
          "targets": 5,
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
            "url": base_url+"/admin/blog",
            "type": "POST",
            "dataType": 'json',
            "data": function ( d ) {
              d.search = $('#search_blog').val();
              d.category_type = $('#category_type').val();
              d.status_type = $('#status_type').val();
            }
        },
        columns:[
            { "data": "index",className: "text-center"},
            
            { "data": "name_hi",sortable:!1,className: "text-center"},
            { "data": "name_en",sortable:!1,className: "text-center"},

            { "data": "blog_category_id.blog_category_title",sortable:!1,className: "text-center"},
            { "data": "date",sortable:!1,className: "text-center"},
            
            { "data": "status", sortable:!1,className: "text-center",
                render: function (data, type, row) {
                  var html = '';
                  var status = row.status==1 ? 'Active' :'Inactive';
                  var addClass = status=='Active' ? '' : 'inactiveClass';
                  if (row.status_access==true) {
                      html ='<span><a href="javascript:void(0)" class="changeStatus '+addClass+'" onclick="triggerStatus('+row.blog_post_id+')">'+status+'</span></a>'
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
                var viewBlog = '';
                if (row.deleted_access==true) {
                    deleted = '<a onclick="triggerDelete('+row.blog_post_id+')" href="javascript:void(0)"><img src="'+imagepath+'/ic_delete.png"></a>'
                }
                if (row.edit_access==true) {
                    editBlog = '<a href="'+base_url+'/admin/editBlog/'+row.blog_post_id+'"><img src="'+imagepath+'/ic_mode_edit.png"></a>'
                }
                viewBlog = '<a href="'+base_url+'/admin/viewBlog/'+row.blog_post_id+'"><img src="'+imagepath+'/ic_eye_color.png"></a>'
                html = '  '+viewBlog+' \t\t\t\t\t\t '+editBlog+' \t\t\t\t\t\t '+deleted+''
                return html
              }
            },
      ],

  });
/*Listing End*/


  $("#search_blog").on('keyup', function () {
        $('#blog_listing').DataTable().ajax.reload();
  });

  $("#category_type").on('change', function () {
        $('#blog_listing').DataTable().ajax.reload();
  });

  $("#status_type").on('change', function () {
        $('#blog_listing').DataTable().ajax.reload();
  });

  $('#delete_prompt').on('hidden.bs.modal', function () {
    $("#did").val('');
  })
  

  $('#status_prompt').on('hidden.bs.modal', function () {
    $("#did").val('');
  })

  $("form[name='add-blog-form']").validate({
    errorClass: "error_msg",
     rules: {
        name_en:{
          required:true,
        },
        name_hi:{
          required:true,
        },
        category:{
          required:true,
        },
        seo_meta_title:{
          required:true,
        },
        seo_meta_description:{
          required:true,
        },
        desc_en:{
          required:true,
        },
        desc_hi:{
          required:true,
        }
     },
      submitHandler: function(form, event){
       event.preventDefault();
       showLoader(true);

      for ( instance in CKEDITOR.instances )
      CKEDITOR.instances[instance].updateElement();

      var formData = new FormData($(form)[0]);
      $.ajax({
          url: base_url+'/admin/addBlog',
          type: 'POST',
          processData: false,
          contentType: false,
          cache: false,              
          data: formData,
          success: function(result)
          {
              if(result.status){
                toastr.success(result.message);
                $('li a[data-slug="admin/blog"]').trigger("click");
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
    url: base_url+'/admin/deleteBlog',
    type: 'POST',
    dataType:'json',
    cache: false,              
    data: {'cid':cid},
    success: function(result)
    {
        $('#delete_prompt').modal('hide');
        if(result.status){
          toastr.success(result.message);
          $('#blog_listing').DataTable().ajax.reload();
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
    url: base_url+'/admin/statusBlog',
    type: 'POST',
    dataType:'json',
    cache: false,              
    data: {'cid':cid},
    success: function(result)
    {
        if(result.status){
          $('#status_prompt').modal('hide');
          $('#blog_listing').DataTable().ajax.reload();
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