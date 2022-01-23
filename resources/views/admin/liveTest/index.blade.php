@extends('layout.app_with_login')
@section('title','Live Test')
@section('script', url('public/js/dashboard/live_test.js'))
@section('content')	
<!-- Page Content  -->
<div class="section">
	<div class="container-fluid">
		<div class="row ">
            <div class="col-12 mb-3">
    			<h2 class="title">{{'Live Test'}}</h2>
            </div>
            <div class="col-md-3 text-md-right mb-3">
                <div class="row">
                    <div class="col-2 m-auto">
                        <label class="blue_label">Date</label>
                    </div>
                    <div class="col-10">
                        <input type="text" class="form-control without_border icon_control datepicker1" autocomplete="off" id="search_date_from">
                    </div>
                </div>
            </div> 
            <div class="col-md-3 text-md-right mb-3">
                <div class="row">
                    <div class="col-2 m-auto">
                        <label class="blue_label text-center">To</label>
                    </div>
                    <div class="col-10">
                        <input type="text" class="form-control without_border icon_control datepicker1" autocomplete="off"  id="search_date_to">
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-6 mb-3">
                
            </div>
            <div class="col-md-4 mb-3">
                <div class="row">
                    <div class="col-3 m-auto">
                        <label class="blue_label">Test Type</label>
                    </div>
                    <div class="col-9">
                        <select class="form-control without_border icon_control dropdown_control " id="test_type">
                            <option value="">Select</option>
                            <option value="Attempted" @if(Request::get('attempt')==true) selected @endif>Attempted</option>
                            <option value="Not_Attempted">Not Attempted</option>
                        </select>
                    </div>
                </div>
            </div>  
            
        </div>

        <div class="row ">
            
            <div class="col-md-4 mb-3">
                <input type="text" id="search_live_test" class="form-control without_border icon_control search_control" placeholder="{{'Search'}}">
            </div>  
            <div class="col-md-3 text-md-right mb-3">
                <div class="row">
                    <div class="col-3 m-auto">
                        <label class="blue_label">Category</label>
                    </div>
                    <div class="col-9">
                        <select class="form-control without_border icon_control dropdown_control " id="search_category">
                            <option value="" selected>Select</option>
                            @foreach($getCategoryWithSubCat as $key=> $value)
                                <option value="{{$value['cat']}}">{{$value['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-3 text-md-right mb-3">
                <div class="row">
                    <div class="col-3 m-auto">
                        <label class="blue_label">Status</label>
                    </div>
                    <div class="col-9">
                        <select class="form-control without_border icon_control dropdown_control " id="status_type">
                            <option value="" selected>Select</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>
            </div> 
            <div class="col-md-2 col-6 mb-3">
                @php
                $accessPriData = !empty(session()->get('accessPriData')) ? session()->get('accessPriData') :'' ;
                @endphp
                @if(!empty($accessPriData['Live_Test']) && $accessPriData['Live_Test']->add==true || Auth::user()->user_type==0)
                <a href="{{url('/admin/addLiveTest')}}"><button class="theme_btn show_modal full_width small_btn">{{'Add New'}}</button></a>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="theme_table">
                    <div class="table-responsive">
                        <table id="live_test_listing" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>{{'Sr. No.'}}</th>
                                    <th>{{'Test Name'}}</th>
                                    <th>{{'Category'}}</th>
                                    <th>{{'Total Question'}}</th>
                                    <th>{{'Total Marks'}}</th>
                                    <th>{{'Test Duration'}}</th>
                                    <th>{{'Date & Time'}}</th>
                                    <th>{{'Result Date'}}</th>
                                    <th>{{'Status'}}</th>
                                    <th><div class="action">{{'Actions'}}</div></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

	</div>
</div>

<div class="theme_modal modal fade" id="delete_prompt" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <img src="{{url('public/images/ic_close_bg.png')}}" class="modal_top_bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <img src="{{url('public/images/ic_close_circle_white.png')}}">
                </button>
                    <div class="row">
                        <div class="col-lg-1"></div>
                        <div class="col-lg-10">
                            <h5 class="modal-title" id="exampleModalCenterTitle">{{'Delete'}}</h5>
                            <div class="form-group text-center">
                                <label>{{$translations['gn_delete_prompt'] ?? 'Are you sure you want to delete ?'}}</label>
                                <input type="hidden" id="did">
                            </div>
                            <div class="text-center modal_btn ">
                                <button style="display: none;" class="theme_btn show_delete_modal full_width small_btn" data-toggle="modal" data-target="#delete_prompt">{{$translations['gn_delete'] ?? 'Delete'}}</button>
                                <button type="button" onclick="confirmDelete()" class="theme_btn">{{$translations['gn_yes'] ?? 'Yes'}}</button>
                                <button type="button" data-dismiss="modal" class="theme_btn red_btn">{{$translations['gn_no'] ?? 'No'}}</button>
                            </div>
                        </div>
                        <div class="col-lg-1"></div>
                    </div>
            </div>
        </div>
    </div>
</div>

<div class="theme_modal modal fade" id="status_prompt" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <img src="{{url('public/images/ic_close_bg.png')}}" class="modal_top_bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <img src="{{url('public/images/ic_close_circle_white.png')}}">
                </button>
                    <div class="row">
                        <div class="col-lg-1"></div>
                        <div class="col-lg-10">
                            <h5 class="modal-title" id="exampleModalCenterTitle">{{'Change Status'}}</h5>
                            <div class="form-group text-center">
                                <label>{{'Are you sure you want to change status ?'}}</label>
                                <input type="hidden" id="did">
                            </div>
                            <div class="text-center modal_btn ">
                                <button style="display: none;" class="theme_btn show_status_modal full_width small_btn" data-toggle="modal" data-target="#status_prompt">{{$translations['gn_delete'] ?? 'Delete'}}</button>
                                <button type="button" onclick="confirmStatus()" class="theme_btn">{{$translations['gn_yes'] ?? 'Yes'}}</button>
                                <button type="button" data-dismiss="modal" class="theme_btn red_btn">{{$translations['gn_no'] ?? 'No'}}</button>
                            </div>
                        </div>
                        <div class="col-lg-1"></div>
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

<!-- Include this Page JS -->
<script type="text/javascript" src="{{ url('public/js/dashboard/live_test.js') }}"></script>
@endpush