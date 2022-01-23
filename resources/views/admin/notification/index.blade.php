@extends('layout.app_with_login')
@section('title','Notifications')
@section('script', url('public/js/dashboard/notifications.js'))
@section('content')	
<!-- Page Content  -->
	<div class="section">
    	<div class="container-fluid">
    		<div class="text-right">
    			<button class="theme_btn small_btn">All Clear</button>
    		</div>
			<h5 class="title text-center">Notification</h5>
            <div class="white_box">
                <div class="notification_container">
                	<div class="noti_single">
                		<div class="noti_pera_img">
                    		<p class="noti_pera">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    		<div class="close_noti">
			          			<img src="assets/images/ic_close_circle.png">
			          		</div>
			          	</div>
		          		<div class="text-right date_time_span">
		          			<span>22/6/2019</span>
		          			<span>11:29 PM</span>
		          		</div>
                	</div>
                	<div class="noti_single">
                		<div class="noti_pera_img">
                    		<p class="noti_pera">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    		<div class="close_noti">
			          			<img src="assets/images/ic_close_circle.png">
			          		</div>
			          	</div>
		          		<div class="text-right date_time_span">
		          			<span>22/6/2019</span>
		          			<span>11:29 PM</span>
		          		</div>
                	</div>
                </div>
                
            </div>
			</div>
    </div>
@endsection

@push('custom-scripts')
<script type="text/javascript">
    $(function() {
      showLoader(false);
    });
</script>
<script type="text/javascript" src="{{ url('public/js/dashboard/notifications.js') }}"></script>
@endpush