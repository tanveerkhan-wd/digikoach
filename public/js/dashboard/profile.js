$(function() {

  showLoader(false);

  $('#home-tab').on('click', function() {
      $('.title').html('<span>My Profile</span> > Edit Profile');
  });

  $('#Current-tab').on('click', function() {
      $('.title').html('<span>My Profile</span> > Change Password');
  });


  $("#upload_profile").on('change', function () { 
    if( document.getElementById("upload_profile").files.length == 0 ){
        $('#user_img').attr('src', $('#img_tmp').val());
    }
      selectProfileImage(this);
  });


  $('#change_pass').on('hidden.bs.modal', function () {
    var validator = $( "form[name='change-password-form']" ).validate();
    validator.resetForm();
    $("form").trigger("reset");
  })


  $('.datepicker').datepicker({format: "mm/dd/yyyy",autoclose: true, endDate: '+0d',});

  $("form[name='loginForm']").validate({
    // Specify validation rules
    rules: {
      name:{
        required:true,
      },
      email: {
        required: true,
        email: true
      }      
    },
    submitHandler: function(form) {
      form.submit();
    }
  });

  $("form[name='edit-profile']").validate({
    errorClass: "error_msg",
     rules: {
        email:{
          required:true,
          email: true,
          emailfull: true
        },
        name:{
          required:true,
          minlength:5
        },
        mobile_number:{
          required:true,
          minlength:10,
          maxlength:10
        },
        info_email: {
          required: true,
          email: true
        }
     },
      submitHandler: function(form, event) {
      //form.submit();
       event.preventDefault();
       showLoader(true);
      var formData = new FormData($(form)[0]);
      $.ajax({
          url: base_url+'/admin/editProfile',
          type: 'POST',
          processData: false,
          contentType: false,
          cache: false,              
          data: formData,
          success: function(result)
          {
            if(result.status){
              toastr.success(result.message);
              $('a[data-slug="admin/profile"]').trigger("click");
              $('.profile-cover img').attr('src',$('#user_img').attr('src'));
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

  jQuery.validator.addMethod("emailfull", function(value, element) {
     return this.optional(element) || /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i.test(value);
    }, $('#email_validate_txt').val());

//Validate form for Change Password
  $("form[name='change-password-form']").validate({
    errorClass: "error_msg",
     rules: {
        old_password:{
          required:true,
          minlength:6,
          maxlength:15
        },
        new_password:{
          required:true,
          minlength:6,
          maxlength:15,
          passwordCheck:true
        },
        confirm_password:{
          required:true,
          equalTo: "#new_password",
          minlength:6,
          maxlength:15
        },
     },
      messages: {
        confirm_password:{
          equalTo: $('#validate_password_equalto_txt').val(),
        },
      },

      submitHandler: function(form, event) {
      //form.submit();
       event.preventDefault();
       showLoader(true);
      var formData = new FormData($(form)[0]);
      $.ajax({
          url: base_url+'/admin/changePasswordPost',
          type: 'POST',
          processData: false,
          contentType: false,
          cache: false,              
          data: formData,
          success: function(result)
          {
              if(result.status){
                toastr.success(result.message);
                window.location.href = result.redirect;
              }else{
                toastr.error(result.message);
              }
              
              showLoader(false);
          },
          error: function(data)
          {
              showMessage(result.message,false);
              showLoader(false);
          }
      });
    }
  });
});

jQuery.validator.addMethod("passwordCheck",
  function(value, element, param) {
      if (this.optional(element)) {
          return true;
      } else if (!/[A-Z]/.test(value)) {
          return false;
      } else if (!/[a-z]/.test(value)) {
          return false;
      } else if (!/[0-9]/.test(value)) {
          return false;
      } else if (!/[\+\-\_\@\#\$\%\&\*\!]/.test(value)) {
          return false;
      }

      return true;
  },
  $('#validate_password_txt').val());

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
            jQuery('#img_tmp').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);        
      }else{
        toastr.error($('#image_validation_msg').val());
        $('#upload_profile').val('');
        var img_tmp = base_url+"/public/images/user.png";
        
        $('#img_tmp').attr('src', img_tmp);
        
      }
  }
}