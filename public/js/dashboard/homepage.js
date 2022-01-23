
$(function () {
  $("form[name='contact-query-form']").validate({
    errorClass: "error_msg",
     rules: {
        name:{
          required:true,
        },
        email:{
          required:true,
        },
        message:{
          required:true,
        }
     },
      submitHandler: function(form, event) {
       form.submit();

      /* event.preventDefault();
        var formData = new FormData($(form)[0]);
        $.ajax({
            url: base_url+'/contactQueryPost',
            type: 'POST',
            processData: false,
            contentType: false,
            cache: false,              
            data: formData,
            success: function(result)
            {
                if(result.status){
                    console.log('success');  
                }else{
                    console.log('fail');  
                  
                }
                showLoader(false);
            },
            error: function(data)
            {
                console.log('data');
            }
        });*/

    }
  });



});