@extends('layout.app_with_login')
@section('title','Add New Live Test')
@section('script', url('public/js/dashboard/live_test.js'))
@section('content')	
<!-- Page Content  -->
<div class="section">
    <div class="container-fluid">
        <h2 class="title"><a  {{-- class="ajax_request" data-slug="admin/liveTest" --}} href="{{url('/admin/liveTest')}}"><span>{{'Live Test '}}</span></a>  >  {{'Add New'}}
        </h2>
        <div class="white_box">
            <div class="theme_tab">
                    
                <form name="add-live-test-form">
                    <div class="row">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-6">

                            <div class="form-group">
                                <label>{{'Live Test Name In English'}}</label>
                                <input type="text" name="name_en" id="name_en" class="form-control icon_control">
                            </div>

                            <div class="form-group">
                                <label>{{'Live Test Name In Hindi'}}</label>
                                <input type="text" name="name_hi" id="name_hi" class="form-control icon_control">
                            </div>

                            <div class="form-group">
                                <label>{{'Test Duration (In Minutes)'}}</label>
                                <input type="number" name="duration" id="duration" class="form-control icon_control" placeholder="">
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 col-lg-6">
                                    <label>{{'Start Date'}}</label>
                                    <input type="text" name="start_date" id="start_date" class="date_control form-control icon_control pl-1" autocomplete="off">
                                </div>

                                <div class="col-md-6 col-lg-6">
                                    <label>{{'Start Time'}}</label>
                                    <input type="time" name="start_time" id="start_time" class="time_icon form-control pl-1" autocomplete="off">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 col-lg-6">
                                    <label>{{'End Date'}}</label>
                                    <input type="text" name="end_date" id="end_date" class="date_control  form-control icon_control pl-1" autocomplete="off">
                                </div>

                                <div class="col-md-6 col-lg-6">
                                    <label>{{'End Time'}}</label>
                                    <input type="time" name="end_time" id="end_time" class="time_icon form-control pl-1" autocomplete="off">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 col-lg-6">
                                    <label>{{'Result Date'}}</label>
                                    <input type="text" name="result_date" id="result_date" class="date_control form-control icon_control pl-1" autocomplete="off">
                                </div>

                                <div class="col-md-6 col-lg-6">
                                    <label>{{'Result Time'}}</label>
                                    <input type="time" name="result_time" id="result_time" class="time_icon form-control pl-1" autocomplete="off">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>{{'Select Category'}}</label>
                                <select class="form-control icon_control dropdown_control select2 getCategory" name="main_category" id="category">
                                    <option value="">{{'Select'}}</option>
                                    @foreach($parent_category as $catVal)
                                        <option value="{{$catVal->category_id}}">{{$catVal->category_desc[0]->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="get_sub_category">
                                
                            </div>

                            <div id="get_sub_category_question">
                                
                            </div>

                            <div class="text-center modal_btn pt-3">
                                <a class="theme_btn red_btn ajax_request no_sidebar_active" data-slug="admin/liveTest" href="{{ url('admin/liveTest') }}">{{'Cancel'}}</a>
                                <button type="submit" class="theme_btn">{{'Create Test'}}</button>
                            </div>

                        </div>
                        <div class="col-lg-3"></div>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div> 

@endsection

@push('custom-styles')
<style type="text/css">
    .time_icon::-webkit-calendar-picker-indicator {width: 25px;height: 25px;}
    .date_control{background-size: 20px 19px;}
</style>
@endpush
@push('custom-scripts')

<script type="text/javascript" src="{{ url('public/js/dashboard/live_test.js') }}"></script>
<script type="text/javascript">
    $(function() {
      showLoader(false);
    });
</script>

@endpush