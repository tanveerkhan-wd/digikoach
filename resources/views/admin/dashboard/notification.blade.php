@extends('layout.app_with_login')
@section('title','Notifications')
@section('script', url('public/js/dashboard/notifications.js'))
@section('content')	
<!-- Page Content  -->
	<div class="section">
    	<div class="container-fluid">
    		<div class="text-right">
    			<button class="theme_btn small_btn clearAll">All Clear</button>
    		</div>
			<h5 class="title text-center">Notification</h5>
            <div class="white_box">
                <div class="notification_container">
                	@foreach($desc as $data)
                        <div class="noti_single">
                    		<div class="noti_pera_img">
                                <p class="noti_pera">
                                    @php
                                        $doubt_data = json_decode($data['notification_data']);
                                        $doubt_id = $doubt_data->doubt_id ?? 0;
                                    @endphp
                                    @if($data['notification_type']=='PROF_COMP')
                                        <a href="{{url('/admin/viewAppUser')}}/{{$data['ntoification_type_id']}}">
                                            {{ 'Student Profile Completed' }}
                                        </a>
                                    @elseif($data['notification_type']=='PROF_DEACT')
                                        <a href="{{url('/admin/viewAppUser')}}/{{$data['ntoification_type_id']}}">
                                            {{ 'Student Profile Deactivated' }}
                                        </a>
                                    @elseif($data['notification_type']=='DOUBT_ANSWER')
                                        <a href="{{url('/admin/viewDoubt')}}/{{$doubt_id}}">
                                            {{ 'New Doubt Answer' }}
                                        </a>
                                    @elseif($data['notification_type']=='NEW_DOUBT')
                                        <a href="{{url('/admin/viewDoubt')}}/{{$data['ntoification_type_id']}}">
                                            {{ 'New Doubt Created' }}
                                        </a>
                                    @elseif($data['notification_type']=='DOUBT_REPLY')
                                        <a href="{{url('/admin/viewDoubt')}}/{{$data['ntoification_type_id']}}">
                                            {{ $data['desc']['message'] ?? 'Message Not Available'}}
                                        </a>
                                    @else
                                        {{ $data['desc']['message'] ?? 'Message Not Available'}}
                                    @endif
                                </p>
                        		<div class="close_noti" single-id="{{$data['notification_id']}}">
    			          			<img src="{{url('public/images/ic_close_circle.png')}}">
    			          		</div>
    			          	</div>
    		          		<div class="text-right date_time_span">
    		          			<span>{{ date('d-M-Y',strtotime($data['created_at'])) ?? '' }}</span>
    		          			<span>{{ date('m:h:s',strtotime($data['created_at'])) ?? '' }}</span>
    		          		</div>
                    	</div>
                    @endforeach
                </div>
            </div>
            {{$desc->links()}}
			</div>
    </div>
@endsection
@push('custom-styles')
<style type="text/css">
    ul.pagination {
    margin-top: 20px;
    justify-content: center;
}
.page-item.active .page-link, .page-link:hover {
    background-color: #0FB2AB;
    border: 1px solid #0FB2AB;
    color: #fff;
}
.page-link {
    color: #333;
}
</style>
@endpush
@push('custom-scripts')
<script type="text/javascript">
    $(function() {
      showLoader(false);
    });
</script>
@endpush