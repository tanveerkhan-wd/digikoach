@extends('layout.app_with_login')
@section('title','Banner Images')
@section('script', url('public/js/dashboard/banner_images.js'))
@section('content')	
<!-- Page Content  -->
<div class="section">
    <input type="hidden" id="img_base_name" value="{{Config::get('siteglobal.images_dirs.BANNER')}}">
	<div class="container-fluid">
		<div class="row ">
            <div class="col-12 mb-3">
    			<h2 class="title"><span>{{'Master'}}</span> > {{'Banner Images'}}</h2>
            </div>
            <div class="col-md-4 mb-3">
                <input type="text" id="search_banner" class="form-control without_border icon_control search_control" placeholder="{{'Search'}}">
            </div>  
            <div class="col-md-4 text-md-right mb-3">
                
            </div> 
            <div class="col-md-2 col-6 mb-3">
               
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a><button class="theme_btn show_modal full_width small_btn" data-toggle="modal" data-target="#add_new">{{'Add New'}}</button></a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="theme_table">
                    <div class="table-responsive">
                        <table id="banner_listing" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>{{'Sr. No.'}}</th>
                                    <th>{{'Image Hindi'}}</th>
                                    <th>{{'Image English'}}</th>
                                    <th>{{'Sequence'}}</th>
                                    <th>{{'Date'}}</th>
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
                <form name="add-banner-form">
                    <div class="row">
                        <div class="col-lg-1"></div>
                        <div class="col-lg-10">
                            <h5 class="modal-title" id="exampleModalCenterTitle">{{'Banner Image'}}</h5>
                            <input type="hidden" name="pkCat" id="pkCat">
                            
                            <label>{{'Image In Hindi'}}</label>
                            <div class="text-center">
                                <div class="profile_box">
                                    <div class="square_pic">
                                        <img id="cat1_img" src="{{ url('public/images/user.png') }}">
                                        <input type="hidden" id="img_tmp1" value="{{ url('public/images/user.png') }}">
                                    </div>
                                    <div  class="upload_pic_link">
                                        <a href="javascript:void(0)">
                                        {{'Upload Image'}}<input type="file" id="upload_profile1" name="banner_img_hi" accept="image/jpeg,image/png"></a>
                                    </div>
                                </div>
                            </div>
                            
                            <label>{{'Image In English'}}</label>
                            <div class="text-center">
                                <div class="profile_box">
                                    <div class="square_pic">
                                        <img id="cat_img" src="{{ url('public/images/user.png') }}">
                                        <input type="hidden" id="img_tmp" value="{{ url('public/images/user.png') }}">
                                    </div>
                                    <div  class="upload_pic_link">
                                        <a href="javascript:void(0)">
                                        {{'Upload Image'}}<input type="file" id="upload_profile" name="banner_img_eng" accept="image/jpeg,image/png"></a>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <label>{{'Banner Sequence'}}</label>
                                <input type="number" name="sequence" id="sequence" class="form-control icon_control">
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

@endsection

@push('custom-scripts')
<script type="text/javascript">
    $(function() {
      showLoader(false);
    });
</script>
<script type="text/javascript" src="{{ url('public/js/dashboard/banner_images.js') }}"></script>
@endpush