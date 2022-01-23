
//Liting
$(function() {

  $("form[name='add-feature-form']").validate({
    errorClass: "error_msg",
     rules: {
        feature_heading:{
          required:true,
        },
        title:{
          required:true,
        },
        description:{
          required:true,
        },
        title1:{
          required:true,
        },
        description1:{
          required:true,
        },
        title2:{
          required:true,
        },
        description2:{
          required:true,
        },
        title3:{
          required:true,
        },
        description3:{
          required:true,
        },
        title4:{
          required:true,
        },
        description4:{
          required:true,
        },
        title5:{
          required:true,
        },
        description5:{
          required:true,
        },
        video_link:{
          required:true,
        }
     },
      submitHandler: function(form, event){
      event.preventDefault();
      showLoader(true);
      var formData = new FormData($(form)[0]);
      $.ajax({
          url: base_url+'/admin/updateFeature',
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
                $('#feature_listing').DataTable().ajax.reload();
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

  $("#upload_profile").on('change', function () { 
      if( document.getElementById("upload_profile").files.length == 0 ){
          $('#feature_img').attr('src', $('#img_tmp').val());
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
            jQuery('#feature_img').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);        
      }else{
        toastr.error($('#image_validation_msg').val());
        $('#upload_profile').val('');
        var img_tmp = base_url+"/public/images/user.png";
        $('#feature_img').attr('src', img_tmp);
      }
  }
}


  $("#upload_profile1").on('change', function () { 
      if( document.getElementById("upload_profile1").files.length == 0 ){
          $('#feature_img1').attr('src', $('#img_tmp1').val());
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
            jQuery('#feature_img1').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);        
      }else{
        toastr.error($('#image_validation_msg').val());
        $('#upload_profile1').val('');
        var img_tmp = base_url+"/public/images/user.png";
        $('#feature_img1').attr('src', img_tmp);
      }
  }
}



  $("#upload_profile2").on('change', function () { 
      if( document.getElementById("upload_profile2").files.length == 0 ){
          $('#feature_img2').attr('src', $('#img_tmp2').val());
      }
        selectProfileImage2(this);
  });

  function selectProfileImage2(input){
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      var filename = input.files[0].name;
      var fileExtension = filename.substr((filename.lastIndexOf('.') + 1));
      var fileExtensionCase = fileExtension.toLowerCase();
      if (fileExtensionCase == 'png' || fileExtensionCase == 'jpeg' || fileExtensionCase == 'jpg' ) {
        reader.onload = function (e) {
            jQuery('#feature_img2').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);        
      }else{
        toastr.error($('#image_validation_msg').val());
        $('#upload_profile2').val('');
        var img_tmp = base_url+"/public/images/user.png";
        $('#feature_img2').attr('src', img_tmp);
      }
  }
}



  $("#upload_profile3").on('change', function () { 
      if( document.getElementById("upload_profile3").files.length == 0 ){
          $('#feature_img3').attr('src', $('#img_tmp3').val());
      }
        selectProfileImage3(this);
  });

  function selectProfileImage3(input){
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      var filename = input.files[0].name;
      var fileExtension = filename.substr((filename.lastIndexOf('.') + 1));
      var fileExtensionCase = fileExtension.toLowerCase();
      if (fileExtensionCase == 'png' || fileExtensionCase == 'jpeg' || fileExtensionCase == 'jpg' ) {
        reader.onload = function (e) {
            jQuery('#feature_img3').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);        
      }else{
        toastr.error($('#image_validation_msg').val());
        $('#upload_profile3').val('');
        var img_tmp = base_url+"/public/images/user.png";
        $('#feature_img3').attr('src', img_tmp);
      }
  }
}




  $("#upload_profile4").on('change', function () { 
      if( document.getElementById("upload_profile4").files.length == 0 ){
          $('#feature_img4').attr('src', $('#img_tmp4').val());
      }
        selectProfileImage4(this);
  });

  function selectProfileImage4(input){
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      var filename = input.files[0].name;
      var fileExtension = filename.substr((filename.lastIndexOf('.') + 1));
      var fileExtensionCase = fileExtension.toLowerCase();
      if (fileExtensionCase == 'png' || fileExtensionCase == 'jpeg' || fileExtensionCase == 'jpg' ) {
        reader.onload = function (e) {
            jQuery('#feature_img4').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);        
      }else{
        toastr.error($('#image_validation_msg').val());
        $('#upload_profile4').val('');
        var img_tmp = base_url+"/public/images/user.png";
        $('#feature_img4').attr('src', img_tmp);
      }
  }
}




  $("#upload_profile5").on('change', function () { 
      if( document.getElementById("upload_profile5").files.length == 0 ){
          $('#feature_img5').attr('src', $('#img_tmp5').val());
      }
        selectProfileImage5(this);
  });

  function selectProfileImage5(input){
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      var filename = input.files[0].name;
      var fileExtension = filename.substr((filename.lastIndexOf('.') + 1));
      var fileExtensionCase = fileExtension.toLowerCase();
      if (fileExtensionCase == 'png' || fileExtensionCase == 'jpeg' || fileExtensionCase == 'jpg' ) {
        reader.onload = function (e) {
            jQuery('#feature_img5').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);        
      }else{
        toastr.error($('#image_validation_msg').val());
        $('#upload_profile5').val('');
        var img_tmp = base_url+"/public/images/user.png";
        $('#feature_img5').attr('src', img_tmp);
      }
  }
}


//About video
$("#ab_video_upload_profile").on('change', function () { 
  if( document.getElementById("ab_video_upload_profile").files.length == 0 ){
      $('#vi_img').attr('src', $('#vi_img_tmp').val());
  }
    selectProfileImageVi(this);
});

function selectProfileImageVi(input){
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      var filename = input.files[0].name;
      var fileExtension = filename.substr((filename.lastIndexOf('.') + 1));
      var fileExtensionCase = fileExtension.toLowerCase();
      
      if (fileExtensionCase == 'mp4' || fileExtensionCase == 'wmv' || fileExtensionCase == 'avi' ) {
          var img_tmp = base_url+"/public/images/video.png";
          jQuery('#vi_img').attr('src', img_tmp);
      }else{
        toastr.error($('#video_validation_msg').val());
        $('#ab_video_upload_profile').val('');
        var img_tmp = base_url+"/public/images/user.png";
        $('#vi_img').attr('src', img_tmp);
      }
  }
}
