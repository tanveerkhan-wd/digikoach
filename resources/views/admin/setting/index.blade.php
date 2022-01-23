@extends('layout.app_with_login')
@section('title','Home Page Settings')
@section('script', url('public/js/dashboard/settings.js'))
@section('content')
 <!-- Page Content  -->
<div class="section">
  <div class="container-fluid">
    <h5 class="title"><span>{{'Master'}}</span> >  {{'Home Page Settings'}} > {{'Header'}}</h5>
        <div class="white_box">
            <div class="theme_tab">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                  <li class="nav-item">
                      <a class="nav-link active" id="header-tab" data-toggle="tab" href="#header" role="tab" aria-controls="header" aria-selected="true">Header</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" id="about-tab" data-toggle="tab" href="#about" role="tab" aria-controls="about" aria-selected="true">About</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" id="download-link-tab" data-toggle="tab" href="#download-link" role="tab" aria-controls="download-link" aria-selected="true">Downlod Links</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="true">Contact</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" id="seo-setting-tab" data-toggle="tab" href="#seo-setting" role="tab" aria-controls="seo-setting" aria-selected="true">SEO Settings</a>
                  </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                      <div class="tab-pane fade show active" id="header" role="tabpanel" aria-labelledby="header-tab">
                          <div class="inner_tab">
                            <form name="header-form">
                              <div class="row">
                                  <div class="col-lg-3"></div>
                                  <div class="col-lg-6">
                                    <div class="row">
                                      <div class="col-lg-6 col-md-6">
                                        <label>{{'Logo'}}</label>
                                          <div class="profile_box">
                                            <div class="square_pic">
                                                <img id="logo_img" src="@if(!empty($setting['head']['logo'])) {{ url('public/storage/'.Config::get('siteglobal.images_dirs.SETTING').'/'.$setting['head']['logo']) }} @else {{ url('public/images/user.png') }} @endif">
                                                <input type="hidden" id="logo_img_tmp" value="{{ url('public/images/user.png') }}">
                                            </div>
                                            <div  class="upload_pic_link">
                                                <a href="javascript:void(0)">
                                                {{'Upload Logo'}}<input type="file" id="logo_upload_profile" name="logo" accept="image/jpeg,image/png"></a>
                                            </div>
                                        </div>
                                      </div>
                                      <div class="col-lg-6 col-md-6">
                                        <label>{{'Banner'}}</label>
                                        <div class="profile_box">
                                            <div class="square_pic">
                                                <img id="banner_img" src="@if(!empty($setting['head']['banner'])) {{ url('public/storage/'.Config::get('siteglobal.images_dirs.SETTING').'/'.$setting['head']['banner']) }} @else {{ url('public/images/user.png') }} @endif">
                                                <input type="hidden" id="banner_img_tmp" value="{{ url('public/images/user.png') }}">
                                            </div>
                                            <div  class="upload_pic_link">
                                                <a href="javascript:void(0)">
                                                {{'Upload Banner Image'}}<input type="file" id="banner_upload_profile" name="banner" accept="image/jpeg,image/png,video/*"></a>
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="">
                                      <div class="form-group">
                                          <label>First Tag Line</label>
                                          <input type="text" name="first_tag_line" class="form-control" value="{{$setting['head']['line1'] ?? ''}}">
                                        </div>
                                        <div class="form-group">
                                          <label>Second Tag Line</label>
                                          <input type="text" name="second_tag_line" class="form-control" value="{{$setting['head']['line2'] ?? ''}}">
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button class="theme_btn">Save</button>
                                        <a class="theme_btn red_btn ajax_request no_sidebar_active" data-slug="admin/dashboard" href="{{ url('admin/dashboard') }}">{{'Cancel'}}</a>
                                    </div>
                                  </div>
                                  <div class="col-lg-3"></div>
                              </div>
                            </form>
                          </div>
                      </div>
                      
                      <div class="tab-pane fade show" id="about" role="tabpanel" aria-labelledby="about-tab">
                          <div class="inner_tab">
                              <form name="about-form">
                              <div class="row">
                                  <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                      <div class="">
                                        
                                        <label>{{'About Image'}}</label>
                                        <div class="text-center">
                                          <div class="profile_box">
                                              <div class="square_pic">
                                                  <img id="about_img" src="@if(!empty($setting['about']['image'])) {{ url('public/storage/'.Config::get('siteglobal.images_dirs.SETTING').'/'.$setting['about']['image']) }} @else {{ url('public/images/user.png') }} @endif">
                                                  <input type="hidden" id="about_img_tmp" value="{{ url('public/images/user.png') }}">
                                              </div>
                                              <div  class="upload_pic_link">
                                                  <a href="javascript:void(0)">
                                                  {{'Upload Image'}}<input type="file" id="ab_upload_profile" name="ab_image" accept="image/jpeg,image/png"></a>
                                              </div>
                                          </div>
                                        </div>

                                        <div class="form-group">
                                          <label>{{'Title'}} *</label>
                                          <input type="text" id="ab_title" name="ab_title" class="form-control icon_control" value="{{$setting['about']['title'] ?? ''}}">
                                        </div>
                                        <div class="form-group">
                                            <label>{{'Tag Line'}}</label>
                                            <textarea class="form-control icon_control" name="ab_second_tag_line" id="ab_second_tag_line" rows="3">{{$setting['about']['tag_line'] ?? ''}}</textarea>
                                        </div>

                                      </div>
                                      <div class="text-center">
                                          <button class="theme_btn">Save</button>
                                          <a class="theme_btn red_btn ajax_request no_sidebar_active" data-slug="admin/dashboard" href="{{ url('admin/dashboard') }}">{{'Cancel'}}</a>
                                      </div>
                                    </div>
                                  <div class="col-lg-3"></div>
                              </div>
                              </form>
                          </div>
                      </div>

                      <div class="tab-pane fade show" id="download-link" role="tabpanel" aria-labelledby="download-link-tab">
                          <div class="inner_tab">
                              <form name="downlod-link-form">
                              <div class="row">
                                  <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                      <div class="">
                                        <label>{{'Downlod Link Image'}}</label>
                                        <div class="text-center">
                                          <div class="profile_box">
                                            <div class="square_pic">
                                                <img id="dl_img" src="@if(!empty($setting['down_link']['image'])) {{ url('public/storage/'.Config::get('siteglobal.images_dirs.SETTING').'/'.$setting['down_link']['image']) }} @else {{ url('public/images/user.png') }} @endif ">
                                                <input type="hidden" id="dl_img_tmp" value="{{ url('public/images/user.png') }}">
                                            </div>
                                            <div  class="upload_pic_link">
                                                <a href="javascript:void(0)">
                                                {{'Upload Image'}}<input type="file" id="dl_upload_profile" name="image" accept="image/jpeg,image/png"></a>
                                            </div>
                                          </div>
                                        </div>

                                        <div class="form-group">
                                          <label>{{'Heading'}} *</label>
                                          <input type="text" id="dl_heading" name="dl_heading" class="form-control icon_control" value="{{ $setting['down_link']['head']  ?? ''}}">
                                        </div>
                                        <div class="form-group">
                                            <label>{{'Text'}}</label>
                                            <textarea class="form-control icon_control" name="dl_text" id="dl_text" rows="3">{{ $setting['down_link']['text'] ?? ''}}</textarea>
                                        </div>
                                        <label>{{'Google Play Store Icon'}}</label>
                                        <div class="text-center">
                                          <div class="profile_box">
                                            <div class="square_pic">
                                                <img id="gps_img" src="@if(!empty($setting['down_link']['ps_icon'])) {{ url('public/storage/'.Config::get('siteglobal.images_dirs.SETTING').'/'.$setting['down_link']['ps_icon']) }} @else {{ url('public/images/user.png') }} @endif ">
                                                <input type="hidden" id="gps_img_tmp" value="{{ url('public/images/user.png') }}">
                                            </div>
                                            <div  class="upload_pic_link">
                                                <a href="javascript:void(0)">
                                                {{'Upload Icon'}}<input type="file" id="gps_upload_profile" name="play_store_icon" accept="image/jpeg,image/png"></a>
                                            </div>
                                          </div>
                                        </div>

                                        <div class="form-group">
                                          <label>{{'Play Store Link'}} *</label>
                                          <input type="link" id="gps_link" name="gps_link" class="form-control icon_control" value="{{ $setting['down_link']['ps_link'] ?? '' }}">
                                        </div>
                                        <label>{{'App Store Icon'}}</label>
                                        <div class="text-center">
                                          <div class="profile_box">
                                            <div class="square_pic">
                                                <img id="app_img" src="@if(!empty($setting['down_link']['as_icon'])) {{ url('public/storage/'.Config::get('siteglobal.images_dirs.SETTING').'/'.$setting['down_link']['as_icon']) }} @else {{ url('public/images/user.png') }} @endif ">
                                                <input type="hidden" id="app_img_tmp" value="{{ url('public/images/user.png') }}">
                                            </div>
                                            <div  class="upload_pic_link">
                                                <a href="javascript:void(0)">
                                                {{'Upload Icon'}}<input type="file" id="app_upload_profile" name="app_store_icon" accept="image/jpeg,image/png"></a>
                                            </div>
                                          </div>
                                        </div>

                                        <div class="form-group">
                                          <label>{{'App Store Link'}} *</label>
                                          <input type="text" id="app_store_link" name="app_store_link" class="form-control icon_control" value="{{ $setting['down_link']['ps_link'] ?? '' }}">
                                        </div>


                                      </div>
                                      <div class="text-center">
                                          <button class="theme_btn">Save</button>
                                          <a class="theme_btn red_btn ajax_request no_sidebar_active" data-slug="admin/dashboard" href="{{ url('admin/dashboard') }}">{{'Cancel'}}</a>
                                      </div>
                                    </div>
                                  <div class="col-lg-3"></div>
                              </div>
                              </form>
                          </div>
                      </div>

                      <div class="tab-pane fade show" id="seo-setting" role="tabpanel" aria-labelledby="seo-setting-tab">
                          <div class="inner_tab">
                              <form name="seo-form">
                              <div class="row">
                                  <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                      <div class="">
                                        
                                        <div class="form-group">
                                          <label>{{'Title'}} *</label>
                                          <input type="text" id="seo_title" name="seo_title" class="form-control icon_control" value="{{$setting['seo']['title'] ?? ''}}">
                                        </div>
                                        <div class="form-group">
                                            <label>{{'Description'}}</label>
                                            <textarea class="form-control icon_control" name="seo_description" id="seo_description" rows="3">{{$setting['seo']['desc'] ?? ''}}</textarea>
                                        </div>

                                      </div>
                                      <div class="text-center">
                                          <button class="theme_btn">Save</button>
                                          <a class="theme_btn red_btn ajax_request no_sidebar_active" data-slug="admin/dashboard" href="{{ url('admin/dashboard') }}">{{'Cancel'}}</a>
                                      </div>
                                    </div>
                                  <div class="col-lg-3"></div>
                              </div>
                              </form>
                          </div>
                      </div>

                      <div class="tab-pane fade show" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                          <div class="inner_tab">
                              <form name="contact-form">
                              <div class="row">
                                  <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                      <div class="">
                                        <div class="form-group">
                                          <label>{{'Address'}} *</label>
                                          <input type="text" id="contact_address" name="contact_address" class="form-control icon_control" value="{{$setting['contact']['address']}}">
                                        </div>
                                        <div class="form-group">
                                          <label>{{'Mobile Number'}} *</label>
                                          <input type="number" id="contact_phone" name="contact_phone" class="form-control icon_control" value="{{$setting['contact']['phone']}}">
                                        </div>
                                        <div class="form-group">
                                          <label>{{'Email'}} *</label>
                                          <input type="email" id="contact_email" name="contact_email" class="form-control icon_control" value="{{$setting['contact']['email']}}">
                                        </div>
                                      </div>
                                      <div class="text-center">
                                          <button class="theme_btn">Save</button>
                                          <a class="theme_btn red_btn ajax_request no_sidebar_active" data-slug="admin/dashboard" href="{{ url('admin/dashboard') }}">{{'Cancel'}}</a>
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
@endpush
@push('datatable-scripts')
<!-- Include this Page JS -->
<script src="{{ url('public/bower_components/ckeditor/ckeditor.js') }}"></script>

<script type="text/javascript" src="{{ url('public/js/dashboard/settings.js') }}"></script>
@endpush