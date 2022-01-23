$(function() {

	$('.close_noti ,.clearAll').on('click' , function(){
		
		var cid = '';
		var getsingleId = $(this).attr('single-id');
		if (getsingleId) {
			cid = getsingleId;
			$(this).parent().parent().remove();
		}else{
			cid = 'ALL';
			$('.notification_container').empty();
		}
		showLoader(true);
		$.ajax({
		    url: base_url+'/admin/changeNotificationStatus',
		    type: 'POST',
		    dataType:'json',
		    cache: false,              
		    data: {'cid':cid},
		    success: function(result)
		    {
		        if(result.status){
		          toastr.success(result.message);
		          $('li a[data-slug="admin/notifications"]').trigger("click");
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
		


	});

});