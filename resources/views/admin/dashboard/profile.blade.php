@extends('layout.app_with_login')
@section('title','Profile')
@section('script', url('public/js/dashboard/profile.js'))
@section('content')
 <!-- Page Content  -->
<div class="section">
  <div class="container-fluid">
    <h5 class="title"><span>{{'My Profile'}}</span> > {{'Edit Profile'}}</h5>
        <div class="white_box">
            <div class="theme_tab">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                  <li class="nav-item">
                      <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Edit Profile</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" id="Current-tab" data-toggle="tab" href="#Current" role="tab" aria-controls="Current" aria-selected="true">Change Password</a>
                  </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                      <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                          <div class="inner_tab" id="edit_profile_detail">
                            <form id="edit-profile-form" name="edit-profile">
                              <div class="row">
                                  <div class="col-lg-3"></div>
                                  <div class="col-lg-6">
                                      <div class="text-center">
                                          <div class="profile_box">
                                              <div class="profile_pic">
                                                  <img id="img_tmp" src="@if(!empty(Auth::user()->user_photo)) {{url('public/storage/'.Config::get('siteglobal.images_dirs.USERS') )}}/{{Auth::user()->user_photo}} @else {{ url('public/images/user.png') }}@endif">
                                                  <input type="hidden" id="img_tmp" value="{{ url('public/images/user.png') }}">
                                              </div>
                                              <div class="edit_pencile">
                                                <img src="{{ url('public/images/ic_pen.png') }}">
                                              <input type="file" id="upload_profile" name="user_img" accept="image/jpeg,image/png">
                                              </div>
                                          </div>
                                      </div>
                                      <div class="">
                                          <div class="form-group">
                                              <label>Name*</label>
                                              <input type="text" name="name" class="form-control" value="{{Auth::user()->name}}" placeholder="Enter Name">
                                            </div>
                                            <div class="form-group">
                                              <label>Email*</label>
                                              <input type="email" name="email" class="form-control" placeholder="Enter Email" value="{{Auth::user()->email}}">
                                            </div>
                                            <div class="form-group">
                                              <label>Mobile Number*</label>
                                              <input type="number" name="mobile_number" class="form-control" placeholder="Enter Mobile Number" value="{{Auth::user()->mobile_number}}">
                                            </div>
                                            @if(Auth::check() &&  Auth::user()->user_type==0)
                                              <div class="form-group">
                                                <label>Info Email*</label>
                                                <input type="email" name="info_email" class="form-control" placeholder="Enter Info Email" value="{{Auth::user()->info_email}}">
                                              </div>
                                            @endif
                                      </div>
                                      <div class="text-center">
                                          <button class="theme_btn">Submit</button>
                                          <a class="theme_btn red_btn ajax_request no_sidebar_active" data-slug="admin/dashboard" href="{{ url('admin/dashboard') }}">{{$translations['gn_cancel'] ?? 'Cancel'}}</a>
                                      </div>
                                  </div>
                                  <div class="col-lg-3"></div>
                              </div>
                            </form>
                          </div>
                      </div>
                      <div class="tab-pane fade show" id="Current" role="tabpanel" aria-labelledby="Current-tab">
                          <div class="inner_tab" id="edit_profile_detail2">
                              <form name="change-password-form">
                              <div class="row">
                                  <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                      <div class="">
                                        <div class="form-group">
                                          <label>{{$translations['gn_old_password'] ?? 'Old Password'}} *</label>
                                          <input type="password" name="old_password" name="id" class="form-control pass_control icon_control" placeholder="">
                                        </div>
                                        <div class="form-group">
                                          <label>{{$translations['gn_new_password'] ?? 'New Password'}} *</label>
                                          <input type="password" name="new_password" id="new_password" class="form-control pass_control icon_control" placeholder="">
                                        </div>
                                        <div class="form-group">
                                          <label>{{$translations['gn_confirm_password'] ?? 'Confirm Password'}} *</label>
                                          <input type="password" name="confirm_password" id="confirm_password" class="form-control pass_control icon_control" placeholder="">
                                        </div>    
                                      </div>
                                      <div class="text-center">
                                          <button class="theme_btn">Submit</button>
                                          <a class="theme_btn red_btn ajax_request no_sidebar_active" data-slug="admin/dashboard" href="{{ url('admin/dashboard') }}">{{$translations['gn_cancel'] ?? 'Cancel'}}</a>
                                      </div>
                                    </div>
                                  <div class="col-lg-3"></div>
                              </div>
                              </form>
                          </div>
                      </div>
                  </div>

            </div>
        </div>
      </div>
</div>          
   @if ($errors->any())
        <div class="alert alert-danger">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (\Session::has('success'))
        <div class="alert alert-success">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <ul>
                <li>{!! \Session::get('success') !!}</li>
            </ul>
        </div>
    @endif

    @php                                     
      $_REQUEST['data'] = (isset($_REQUEST['data']) && !empty($_REQUEST['data']))?$_REQUEST['data']:'profile';
    @endphp

<!-- End Content Body -->
@endsection
@push('custom-styles')
<!-- Include this Page CSS -->
<link link rel="stylesheet" type="text/css" href="{{ url('public/css/jquery.datepick.css') }}">
<link link rel="stylesheet" type="text/css" href="{{ url('public/css/toastr.min.css') }}">
<style type="text/css">

div#upload-demo {
    height: 160px;
    width: 160px;
    display: inline-block;
    vertical-align: top;
    margin: 0 20px;
    border: 1px solid #59d6b9;
}

div#upload-demo .cr-viewport.cr-vp-circle {
    height: 160px !important;
    width: 160px !important;
    box-shadow: 0 0 2000px 2000px rgba(170, 234, 212, 0.4);
}

div#upload-demo .cr-boundary {
    width: 160px !important;
    height: 160px !important;
}

div#upload-demo img.cr-image {
    opacity: 0;
}

button.upload-result {
    margin-top: 30px;
}
.edit_icon input[type="file"]{
  top: 0;
}
</style>
@endpush
@push('datatable-scripts')
<!-- Include this Page JS -->
<script type="text/javascript" src="{{ url('public/js/dashboard/profile.js') }}"></script>
@endpush