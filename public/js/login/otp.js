/**
* OTP
*
* This file is used for admin JS
* 
* @package    Laravel
* @subpackage JS
* @since      1.0
*/
//Validate Form
$(function() {
  // Initialize form validation on the registration form.
  // It has the name attribute "registration"
  $("form[name='loginForm']").validate({
    // Specify validation rules
    errorClass: "error_msg",
    rules: {
      otp: {
        required: true,
      },
    },
     messages: {
      otp: {
        required: "Please enter the OTP",
      },
    },
    submitHandler: function(form) {
      form.submit();
    }
  });

  $('#resendOtp-form').on('click', function () {
      $(".resendOtp-form").submit();
  });

});
