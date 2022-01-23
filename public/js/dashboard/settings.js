
  $('#header-tab').on('click', function() {
      $('.title').html('<span>Master</span> > Home Page Settings > Header');
  });

  $('#about-tab').on('click', function() {
      $('.title').html('<span>Master</span> > Home Page Settings > About');
  });

  $('#download-link-tab').on('click', function() {
      $('.title').html('<span>Master</span> > Home Page Settings > Download Link');
  });

  $('#seo-setting-tab').on('click', function() {
      $('.title').html('<span>Master</span> > Home Page Settings > SEO Settings');
  });

  $('#contact-tab').on('click', function() {
      $('.title').html('<span>Master</span> > Home Page Settings > Contact');
  });


$("#ab_upload_profile").on('change', function () { 
  if( document.getElementById("ab_upload_profile").files.length == 0 ){
      $('#about_img').attr('src', $('#about_img_tmp').val());
  }
    selectProfileImageab(this);
});

function selectProfileImageab(input){
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      var filename = input.files[0].name;
      var fileExtension = filename.substr((filename.lastIndexOf('.') + 1));
      var fileExtensionCase = fileExtension.toLowerCase();
      if (fileExtensionCase == 'png' || fileExtensionCase == 'jpeg' || fileExtensionCase == 'jpg' ) {
        reader.onload = function (e) {
            jQuery('#about_img').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);        
      }else{
        toastr.error($('#image_validation_msg').val());
        $('#ab_upload_profile').val('');
        var img_tmp = base_url+"/public/images/user.png";
        $('#about_img').attr('src', img_tmp);
      }
  }
}


$("#logo_upload_profile").on('change', function () { 
  if( document.getElementById("logo_upload_profile").files.length == 0 ){
      $('#logo_img').attr('src', $('#logo_img_tmp').val());
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
            jQuery('#logo_img').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);        
      }else{
        toastr.error($('#image_validation_msg').val());
        $('#logo_upload_profile').val('');
        var img_tmp = base_url+"/public/images/user.png";
        $('#logo_img').attr('src', img_tmp);
      }
  }
}


$("#banner_upload_profile").on('change', function () { 
  if( document.getElementById("banner_upload_profile").files.length == 0 ){
      $('#banner_img').attr('src', $('#banner_img_tmp').val());
  }
    selectProfileImage1(this);
});

function selectProfileImage1(input){
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      var filename = input.files[0].name;
      var fileExtension = filename.substr((filename.lastIndexOf('.') + 1));
      var fileExtensionCase = fileExtension.toLowerCase();
      if (fileExtensionCase == 'png' || fileExtensionCase == 'jpeg' || fileExtensionCase == 'jpg' || fileExtensionCase == 'mp4' || fileExtensionCase == 'WMV' || fileExtensionCase == 'AVI' ) {
        reader.onload = function (e) {
          jQuery('#banner_img').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);        
      }else{
        toastr.error($('#image_validation_msg').val());
        $('#banner_upload_profile').val('');
        var img_tmp = base_url+"/public/images/user.png";
        $('#banner_img').attr('src', img_tmp);
      }
  }
}


$("#gps_upload_profile").on('change', function () { 
  if( document.getElementById("gps_upload_profile").files.length == 0 ){
      $('#gps_img').attr('src', $('#gps_img_tmp').val());
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
            jQuery('#gps_img').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);        
      }else{
        toastr.error($('#image_validation_msg').val());
        $('#gps_upload_profile').val('');
        var img_tmp = base_url+"/public/images/user.png";
        $('#gps_img').attr('src', img_tmp);
      }
  }
}

$("#app_upload_profile").on('change', function () { 
  if( document.getElementById("app_upload_profile").files.length == 0 ){
      $('#app_img').attr('src', $('#app_img_tmp').val());
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
            jQuery('#app_img').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);        
      }else{
        toastr.error($('#image_validation_msg').val());
        $('#app_upload_profile').val('');
        var img_tmp = base_url+"/public/images/user.png";
        $('#app_img').attr('src', img_tmp);
      }
  }
}

$("#dl_upload_profile").on('change', function () { 
  if( document.getElementById("dl_upload_profile").files.length == 0 ){
      $('#dl_img').attr('src', $('#dl_img_tmp').val());
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
            jQuery('#dl_img').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);        
      }else{
        toastr.error($('#image_validation_msg').val());
        $('#dl_upload_profile').val('');
        var img_tmp = base_url+"/public/images/user.png";
        $('#dl_img').attr('src', img_tmp);
      }
  }
}


//HEADER PAGE
$("form[name='header-form']").validate({
  errorClass: "error_msg",
   rules: {
      first_tag_line:{
        required:true,
      },
      second_tag_line:{
        required:true,
      }
   },
    submitHandler: function(form, event) {
    event.preventDefault();
    showLoader(true);
    var formData = new FormData($(form)[0]);
    $.ajax({
        url: base_url+'/admin/editHeader',
        type: 'POST',
        processData: false,
        contentType: false,
        cache: false,              
        data: formData,
        success: function(result)
        {
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


// ABOUT US PAGE
$("form[name='about-form']").validate({
  errorClass: "error_msg",
   rules: {
      ab_title:{
        required:true,
      },
      ab_second_tag_line:{
        required:true,
      }
   },
    submitHandler: function(form, event) {
    event.preventDefault();
    showLoader(true);
    var formData = new FormData($(form)[0]);
    $.ajax({
        url: base_url+'/admin/editAbout',
        type: 'POST',
        processData: false,
        contentType: false,
        cache: false,              
        data: formData,
        success: function(result)
        {
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


// DOWNLOAD LINK PAGE
$("form[name='downlod-link-form']").validate({
  errorClass: "error_msg",
   rules: {
      dl_heading:{
        required:true,
      },
      gps_link:{
        required:true,
        url: true
      },
      app_store_link:{
        required:true,
        url: true
      }
   },
    submitHandler: function(form, event) {
    event.preventDefault();
    showLoader(true);
    var formData = new FormData($(form)[0]);
    $.ajax({
        url: base_url+'/admin/editDownloadLink',
        type: 'POST',
        processData: false,
        contentType: false,
        cache: false,              
        data: formData,
        success: function(result)
        {
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


// SEO SETTINGS PAGE
$("form[name='seo-form']").validate({
  errorClass: "error_msg",
   rules: {
      seo_title:{
        required:true,
      },
      seo_description:{
        required:true,
      }
   },
    submitHandler: function(form, event) {
    event.preventDefault();
    showLoader(true);
    var formData = new FormData($(form)[0]);
    $.ajax({
        url: base_url+'/admin/editSeo',
        type: 'POST',
        processData: false,
        contentType: false,
        cache: false,              
        data: formData,
        success: function(result)
        {
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



// Contact SETTINGS PAGE
$("form[name='contact-form']").validate({
  errorClass: "error_msg",
   rules: {
      contact_address:{
        required:true,
      },
      contact_phone:{
        required:true,
        maxlength:10,
        minlength:10,
      },
      contact_email:{
        required:true,
      }
   },
    submitHandler: function(form, event) {
    event.preventDefault();
    showLoader(true);
    var formData = new FormData($(form)[0]);
    $.ajax({
        url: base_url+'/admin/editContactSet',
        type: 'POST',
        processData: false,
        contentType: false,
        cache: false,              
        data: formData,
        success: function(result)
        {
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