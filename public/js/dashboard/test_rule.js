
// Contact SETTINGS PAGE
$("form[name='test-rule-text-form']").validate({
  errorClass: "error_msg",
   rules: {
      live_test_en:{
        required:true,
      },
      live_test_hi:{
        required:true,
      },
      quizzes_test_en:{
        required:true,
      },
      quizzes_test_hi:{
        required:true,
      }
   },
    submitHandler: function(form, event) {
    event.preventDefault();
    showLoader(true);
    var formData = new FormData($(form)[0]);
    $.ajax({
        url: base_url+'/admin/editTestRule',
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