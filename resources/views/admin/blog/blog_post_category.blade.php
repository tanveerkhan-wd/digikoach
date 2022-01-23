@extends('layout.app_with_login')
@section('title','Blog Category')
@section('script', url('public/js/dashboard/blog_category.js'))
@section('content')	
<!-- Page Content  -->
<div class="section">
	<div class="container-fluid">
		<div class="row ">
            <div class="col-12 mb-3">
    			<h2 class="title"><span>{{'Blog'}}</span> > {{'Blog Category'}}</h2>
            </div>
            <div class="col-md-4 mb-3">
                <input type="text" id="search_blog_cat" class="form-control without_border icon_control search_control" placeholder="{{'Search'}}">
            </div>  
            <div class="col-md-2 col-6 mb-3">
               
            </div>
            <div class="col-md-4 text-md-right mb-3">
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
                @php
                $accessPriData = !empty(session()->get('accessPriData')) ? session()->get('accessPriData') :'' ;
                @endphp
                @if(!empty($accessPriData['Blog_Categories']) && $accessPriData['Blog_Categories']->add==true || Auth::user()->user_type==0)
                <a><button class="theme_btn show_modal full_width small_btn" data-toggle="modal" data-target="#add_new">{{'Add New'}}</button></a>
                @elseif($accessPriData['Blog_Categories']->edit==true)
                <a><button class="theme_btn show_modal full_width small_btn hide_content" data-toggle="modal" data-target="#add_new"></button></a>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="theme_table">
                    <div class="table-responsive">
                        <table id="blog_cat_listing" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>{{'Sr. No.'}}</th>
                                    <th>{{'Category Name Hi'}}</th>
                                    <th>{{'Category Name En'}}</th>
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

<!-- Add New Popup -->
<div class="theme_modal modal fade" id="add_new" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <img src="{{url('public/images/ic_close_bg.png')}}" class="modal_top_bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <img src="{{url('public/images/ic_close_circle_white.png')}}">
                </button>
                <form name="add-category-form">
                    <div class="row">
                        <div class="col-lg-1"></div>
                        <div class="col-lg-10">
                            <h5 class="modal-title" id="exampleModalCenterTitle">{{'Blog Category'}}</h5>
                            <input type="hidden" name="pkCat" id="pkCat">
                            <div class="form-group">
                                <label>{{'Category Name In English'}}</label>
                                <input type="text" name="name_en" id="name_en" class="form-control icon_control">
                            </div>
                            <div class="form-group">
                                <label>{{'Category Name In Hindi'}}</label>
                                <input type="text" name="name_hi" id="name_hi" class="form-control icon_control">
                            </div>

                            <div class="form-group">
                                <label>{{'Select Status'}}</label>
                                <select class="form-control icon_control dropdown_control" name="blog_status" id="blog_status">
                                    <option value="">{{'Select'}}</option>
                                    <option value="1">{{'Active'}}</option>
                                    <option value="0">{{'Inactive'}}</option>
                                </select>
                            </div>

                            <div class="text-center modal_btn ">
                                <button type="submit" class="theme_btn">{{$translations['gn_submit'] ?? 'Submit'}}</button>
                            </div>
                        </div>
                        <div class="col-lg-1"></div>
                    </div>
                </form>
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
<script type="text/javascript" src="{{ url('public/js/dashboard/blog_category.js') }}"></script>
@endpush