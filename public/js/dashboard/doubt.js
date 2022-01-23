//Liting
$(function() {

  var imagepath= base_url+'/public/images/';
  $('#doubt_listing').on( 'processing.dt', function ( e, settings, processing ) {
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
            "url": base_url+"/admin/doubt",
            "type": "POST",
            "dataType": 'json',
            "data": function ( d ) {
              d.search = $('#search_doubt').val();
              d.status_type = $('#status_type').val();
            }
        },
        columns:[
            { "data": "index",className: "text-center"},
            
            { "data": "doubt_text",sortable:!1,className: "text-center"},

            { "data": "category_name",sortable:!1,className: "text-center"},

            { "data": "user_name",sortable:!1,className: "text-center",
              render: function (data, type, row) {
                var getSessionData = $("#getSessionData").val();
                var viewUserPath = 'javascript:void(0)';
                var user_id = row.user?row.user.user_id:false;
                var user_name = row.user!=null?row.user.name:'-';
                
                if (getSessionData==false && user_id) {
                    viewUserPath = base_url+'/admin/viewAppUser/'+user_id;
                }
                var html = '<a class="changeStatus" href="'+viewUserPath+'">'+user_name+'</a>'
                return html;
              }
            },
            
            { "data": "created_at",className: "text-center"},

            { "data": "status", sortable:!1,className: "text-center",
              render: function (data, type, row) {
                var html = '';
                var status = row.status==1 ? 'Active' :'Inactive';
                var addClass = status=='Active' ? '' : 'inactiveClass';
                if (row.status_access==true) {
                    html ='<span><a href="javascript:void(0)" class="changeStatus '+addClass+'" onclick="triggerStatus('+row.doubt_id+')">'+status+'</span></a>'
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
                var viewExam = '';
                if (row.deleted_access==true) {
                    deleted = '<a onclick="triggerDelete('+row.doubt_id+')" href="javascript:void(0)"><img src="'+imagepath+'/ic_delete.png"></a>'
                }
                viewExam = '<a href="'+base_url+'/admin/viewDoubt/'+row.doubt_id+'"><img src="'+imagepath+'/ic_eye_color.png"></a>'
                
                html = ' \t\t\t\t\t\t\t\t\t '+deleted+' '
                
                return viewExam + html

              }
            },
      ],

  });
/*Listing End*/


  
  $("#status_type").on('change', function () {
        $('#doubt_listing').DataTable().ajax.reload();
  });


  $("#search_doubt").on('keyup', function () {
        $('#doubt_listing').DataTable().ajax.reload();
  });

  
  $('#delete_prompt').on('hidden.bs.modal', function () {
    $("#did").val('');
  })


  $('#status_prompt').on('hidden.bs.modal', function () {
    $("#did").val('');
  })


  $("form[name='add-doubt-form']").validate({
      submitHandler: function(form, event){
       event.preventDefault();
       showLoader(true);

      for ( instance in CKEDITOR.instances )
      CKEDITOR.instances[instance].updateElement();

      var formData = new FormData($(form)[0]);
      $.ajax({
          url: base_url+'/admin/doubt/addAnswer',
          type: 'POST',
          processData: false,
          contentType: false,
          cache: false,              
          data: formData,
          success: function(result)
          {
              if(result.status){
                toastr.success(result.message);
                location.reload(true);
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
    url: base_url+'/admin/deleteDoubt',
    type: 'POST',
    dataType:'json',
    cache: false,              
    data: {'cid':cid},
    success: function(result)
    {
        $('#delete_prompt').modal('hide');
        if(result.status){
          toastr.success(result.message);
          $('li a[data-slug="admin/doubt"]').trigger("click");
          $('#doubt_listing').DataTable().ajax.reload();
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

function triggerStatus(cid){
   $('#did').val(cid);   
   $( ".show_status_modal" ).click();
}

function confirmStatus(cid){
  showLoader(true);
  var cid = $('#did').val();
  $.ajax({
    url: base_url+'/admin/statusDoubt',
    type: 'POST',
    dataType:'json',
    cache: false,              
    data: {'cid':cid},
    success: function(result)
    {
        if(result.status){
          $('#status_prompt').modal('hide');
          $('#doubt_listing').DataTable().ajax.reload();
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


  $('#add_new').on('hidden.bs.modal', function () {
    $(".answers_box_popup").empty();
  });

$(".show_model_popup").on('click', function(){
  var cid = $(this).attr('data-id');
  if (cid) {
    showLoader(true);
    $.ajax({
      url: base_url+'/admin/getAnswerReply',
      type: 'POST',
      dataType:'json',
      cache: false,              
      data: {'cid':cid},
      success: function(result)
      {
          if(result.status){
            var alldata = '';
            $.each(result.data, function(index, value) {
                var user_image = '';
                var reply_img = '';
                var viewUserPath = base_url+'/admin/viewAppUser/'+value.user_id;
                var reply_imgage = value.reply_image!=null ? value.reply_image : false;
                var user_name = value.user!=null && value.user.name!=null ? value.user.name:'NA';
                var user_photo = value.user!=null && value.user.user_photo!=null ? value.user.user_photo:false;
                if (user_photo) {
                  user_image = base_url+'/public/storage/'+value.userImagePath+'/'+user_photo;
                }else{
                  user_image = base_url+'/public/images/user.png';
                }
                if (reply_imgage) {
                  reply_img = base_url+'/public/storage/'+value.doubtImagePath+'/'+reply_imgage;
                }
                alldata += '<div class="text-left row align-item-center mt-3"><div class="col-md-8"><div class="doubt_pic_name"><div class="profile_box"><a href="'+viewUserPath+'"><div class="profile_pic" style="height: 55px !important;width: 55px !important;box-shadow:none !important;"><img src="'+user_image+'"></div></a></div><div class="doubt_user_name text-left"><strong><a href="'+viewUserPath+'">'+user_name+'</a></strong></div></div></div><div class="col-md-4 mt-md-0 mt-2"><div class="doubt_user_date text-md-right">'+value.date+'</div></div><div class="col-md-12"><div class="doubt_answers"><label> '+value.doubt_reply+'</label> </div></div></div>'
            });
            
            $( ".show_reply_modal" ).click();
            $(alldata).appendTo(".answers_box_popup");
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