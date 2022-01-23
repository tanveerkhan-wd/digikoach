/**
* Change or Reset Password
*
* This file is used for admin JS
* 
* @package    Laravel
* @subpackage JS
* @since      1.0
*/
$(function() {
  $("form[name='loginForm']").validate({
    errorClass: "error_msg",
    // Specify validation rules
    rules: {
      new_password: {
        required: true,
        minlength:6,
        maxlength:15,
        passwordCheck:true
      },
      confirm_password: {
        required: true,        
        equalTo:"#new_password",
        minlength:6,
        maxlength:15
      },
    },
    // Specify validation error messages
    messages: {    
      confirm_password: {
        //required:"Please enter Confirm Password",
        equalTo: $('#validate_password_equalto_txt').val()
      }, 
    },
    submitHandler: function(form) {
      event.preventDefault();
      showLoaderFull(true);
      var formData = new FormData($(form)[0]);
      formData.append("token", $("#token").val());
      $.ajax({
          url: base_url+'/resetPasswordPost',
          type: 'POST',
          processData: false,
          contentType: false,
          cache: false,              
          data: formData,
          success: function(result)
          {
              //location.reload();
              console.log('result',result);
              if(result.status){
                toastr.success(result.message);
                $("form").trigger("reset");
                setTimeout(function() {
                  window.location.href = result.redirect;
                }, 3000);
                
              }else{
                toastr.error(result.message);
              }
              
              showLoaderFull(false);
          },
          error: function(data)
          {
              toastr.error('Something went wrong');
              showLoaderFull(false);
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