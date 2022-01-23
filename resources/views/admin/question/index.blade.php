@extends('layout.app_with_login')
@section('title','Question Bank')
@section('script', url('public/js/dashboard/questions.js'))
@section('content')	
<!-- Page Content  -->
<div class="section">
	<div class="container-fluid">
        <div class="row ">
            <div class="col-12">
                <h2 class="title">{{'Question Bank'}}</h2>
            </div>
            <div class="col-md-4 mb-3">
                <input type="text" id="search_question" class="form-control without_border icon_control search_control" placeholder="{{'Search'}}">
            </div>  
            <div class="col-md-5 text-md-right mb-3">
                <div class="row">
                    <div class="col-3 m-auto">
                        <label class="blue_label">Category</label>
                    </div>
                    <div class="col-9">
                        <select class="form-control without_border icon_control dropdown_control" id="search_category">
                            <option value="" selected>Select</option>
                            @foreach($getCategoryWithSubCat as $key=> $value)
                                <option value="{{$value['cat']}}">{{$value['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div> 
            <div class="col-md-3 mb-3">
            </div>
        </div>
		<div class="row ">
            <div class="col-md-4 mb-3">
                <div class="row">
                    <div class="col-5 m-auto text-right">
                        <label class="blue_label">Question Type</label>
                    </div>
                    <div class="col-7">
                        <select class="form-control without_border icon_control dropdown_control" id="question_type">
                            <option value="" selected>Select</option>
                            @php
                              $accessPriData = session()->get('accessPriData');
                            @endphp
                            @if(!empty($accessPriData['Question_Bank_Live_Test']) && $accessPriData['Question_Bank_Live_Test']->view==true || Auth::user()->user_type==0)
                                <option value="LIVE_TEST">{{'Live Test'}}</option>
                            @endif
                            @if(!empty($accessPriData['Question_Bank_Quizz_Test']) && $accessPriData['Question_Bank_Quizz_Test']->view==true || Auth::user()->user_type==0)
                                <option value="QUIZZES">{{'Quizzes'}}</option>
                            @endif
                            @if(!empty($accessPriData['Question_Bank_Practice_Test']) && $accessPriData['Question_Bank_Practice_Test']->view==true || Auth::user()->user_type==0)
                                <option value="PRACTICE_TEST">{{'Practice Test'}}</option>
                            @endif
                            @if(!empty($accessPriData['Question_Bank_GK_CA_Test']) && $accessPriData['Question_Bank_GK_CA_Test']->view==true || Auth::user()->user_type==0)
                                <option value="GK_CA">{{'Gk & Ca'}}</option>
                            @endif
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-3 text-md-right mb-3">
                <div class="row">
                    <div class="col-5 m-auto text-right">
                        <label class="blue_label">Used Que.</label>
                    </div>
                    <div class="col-7">
                        <select class="form-control without_border icon_control dropdown_control" id="question_used_unused">
                            <option value="" selected>Select</option>
                            <option value="USED">{{'Used'}}</option>
                            <option value="UNUSED" @if(Request::get('unused')==true) selected @endif>{{'Unused'}}</option>
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
                        <select class="form-control without_border icon_control dropdown_control" id="status_type">
                            <option value="" selected>Select</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>
            </div> 
            <div class="col-md-2 col-6 mb-3">
                <a href="{{url('/admin/addQuestion')}}"><button class="theme_btn show_modal full_width small_btn">{{'Add New'}}</button></a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="theme_table">
                    <div class="table-responsive">
                        <table id="question_listing" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>{{'Sr. No.'}}</th>
                                    <th>{{'Q_ID'}}</th>
                                    <th>{{'Question'}}</th>
                                    <th>{{'Question Type'}}</th>
                                    <th>{{'Category'}}</th>
                                    <th>{{'Marks'}}</th>
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
<script type="text/javascript" src="{{ url('public/js/dashboard/questions.js') }}"></script>
@endpush