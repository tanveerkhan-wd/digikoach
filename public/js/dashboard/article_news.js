
//Liting
$(function() {

  var imagepath= base_url+'/public/images/';
  $('#article_news_listing').on( 'processing.dt', function ( e, settings, processing ) {
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
            "url": base_url+"/admin/gkCa/articleNews",
            "type": "POST",
            "dataType": 'json',
            "data": function ( d ) {
              d.search = $('#search_article_news').val();
              d.status_type = $('#status_type').val();
            }
        },
        columns:[
            { "data": "index",className: "text-center"},
            
            { "data": "desc.article_title",sortable:!1,className: "text-center"},
            
            { "data": "desc.article_body",sortable:!1,className: "text-center",
                render: function (data, type, row) {
                  var html = '';
                  var data = row.desc.article_body;
                  html  = data.substring(0,80);
                  return html;
                }
            },

            { "data": "created_at",className: "text-center",
                render: function (data, type, row) {
                  return row.date;                  
                }
            },
            
            { "data": "status", sortable:!1,className: "text-center",
                render: function (data, type, row) {
                  var html = '';
                    var status = row.status==1 ? 'Active' :'Inactive';
                    var addClass = status=='Active' ? '' : 'inactiveClass';
                    if (row.status_access==true) {
                        html ='<span><a href="javascript:void(0)" class="changeStatus '+addClass+'" onclick="triggerStatus('+row.articles_news_id+')">'+status+'</span></a>'
                    }else{
                        html = status
                    }
                    return html;
                }
            },
            { "data": "action", sortable:!1,
              render: function (data, type, row) {
                var html = '';
                var deleted = '';
                var editExam = '';
                var viewExam = '';
                if (row.deleted_access==true) {
                    deleted = '<a onclick="triggerDelete('+row.articles_news_id+')" href="javascript:void(0)"><img src="'+imagepath+'/ic_delete.png"></a>'
                }
                if (row.edit_access==true) {
                    editExam = '<a href="'+base_url+'/admin/gkCa/editArticleNews/'+row.articles_news_id+'"><img src="'+imagepath+'/ic_mode_edit.png"></a>'
                }
                viewExam = '<a href="'+base_url+'/admin/gkCa/viewArticleNews/'+row.articles_news_id+'"><img src="'+imagepath+'/ic_eye_color.png"></a>'
                
                html = '\t\t\t\t\t\t '+editExam+' \t\t\t\t\t\t '+deleted+' \t\t\t\t\t\t '
                
                return viewExam + html
              }
            },
      ],

  });
/*Listing End*/


  $("#search_article_news").on('keyup', function () {
        $('#article_news_listing').DataTable().ajax.reload();
  });

  $("#status_type").on('change', function () {
        $('#article_news_listing').DataTable().ajax.reload();
  });

  $('#delete_prompt').on('hidden.bs.modal', function () {
    $("#did").val('');
  })
  

  $('#status_prompt').on('hidden.bs.modal', function () {
    $("#did").val('');
  })

  $("form[name='add-article-form']").validate({
    errorClass: "error_msg",
     rules: {
        title_en:{
          required:true,
        },
        title_hi:{
          required:true,
        },
        body_en:{
          required:true,
        },
        body_hi:{
          required:true,
        },
        seo_meta_title:{
          required:true,
        },
        seo_meta_description:{
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
          url: base_url+'/admin/gkCa/addArticleNewsPost',
          type: 'POST',
          processData: false,
          contentType: false,
          cache: false,              
          data: formData,
          success: function(result)
          {
              if(result.status){
                toastr.success(result.message);
                $('li a[data-slug="admin/gkCa/articleNews"]').trigger("click");
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
    url: base_url+'/admin/gkCa/deleteArticleNews',
    type: 'POST',
    dataType:'json',
    cache: false,              
    data: {'cid':cid},
    success: function(result)
    {
        $('#delete_prompt').modal('hide');
        if(result.status){
          toastr.success(result.message);
          $('#article_news_listing').DataTable().ajax.reload();
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
    url: base_url+'/admin/gkCa/statusArticleNews',
    type: 'POST',
    dataType:'json',
    cache: false,              
    data: {'cid':cid},
    success: function(result)
    {
        if(result.status){
          $('#status_prompt').modal('hide');
          $('#article_news_listing').DataTable().ajax.reload();
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