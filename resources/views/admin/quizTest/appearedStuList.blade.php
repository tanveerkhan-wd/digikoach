@extends('layout.app_with_login')
@section('title','Appeared Student List')
@section('script', url('public/js/dashboard/quiz_test.js'))
@section('content')	
<!-- Page Content  -->
<div class="section">
	<div class="container-fluid">
		<div class="row ">
            <input type="hidden" id="stu_exam_id" value="{{ $appearedStu->exam_id ?? ''}}">
            <div class="col-12 mb-3">
    			<h2 class="title"><a  class="ajax_request" data-slug="admin/quizTest" href="{{url('/admin/quizTest')}}"><span>{{'Quiz Test '}}</span></a>  > {{'Appeared Student List'}}</h2>
            </div>
            <div class="col-md-4 mb-3">
                <input type="text" id="search_appear_stu" class="form-control without_border icon_control search_control" placeholder="{{'Search'}}">
            </div>  
            <div class="col-md-3 text-md-right mb-3">
                
            </div>
            <div class="col-md-3 text-md-right mb-3">
                
            </div> 
            <div class="col-md-2 col-6 mb-3">
                
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="theme_table">
                    <div class="table-responsive">
                        <table id="appear_stu_listing" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>{{'Sr. No.'}}</th>
                                    <th>{{'User Name'}}</th>
                                    <th>{{'Attempted'}}</th>
                                    <th>{{'Correct'}}</th>
                                    <th>{{'Incorrect'}}</th>
                                    <th>{{'Marks'}}</th>
                                    <th>{{'Attempted Date'}}</th>
                                    <th>{{'Rank'}}</th>
                                    <th>{{'Action'}}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

	</div>
</div>
@php
$accessPriData = !empty(session()->get('accessPriData')) ? true :false ;
@endphp
<input type="hidden" id="getSessionData" value="{{$accessPriData}}">
@endsection

@push('custom-scripts')
<script type="text/javascript">
    $(function() {
      showLoader(false);
    });
</script>

<!-- Include this Page JS -->
<script type="text/javascript" src="{{ url('public/js/dashboard/quiz_test.js') }}"></script>
@endpush