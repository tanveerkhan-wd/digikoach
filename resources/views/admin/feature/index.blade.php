@extends('layout.app_with_login')
@section('title','Feature')
@section('script', url('public/js/dashboard/feature.js'))
@section('content')	
<!-- Page Content  -->
<div class="section">
  <div class="container-fluid">
    <h2 class="title"><span>{{'Master'}}</span> > {{'Feature'}}</h2>
        <div class="white_box">
            <div class="theme_tab">
                    
                <form name="add-feature-form">
                    <div class="row">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-6">
                          
                            <div class="form-group">
                                <label>{{'Feature Heading'}}</label>
                                <input type="text" name="feature_heading" id="feature_heading" class="form-control icon_control" value="{{$aData['feature_heading'] ?? ''}}">
                            </div>

                            <div class="form-group">
                                <label>{{'Add Video Link'}}</label>
                                <input type="text" name="video_link" id="video_link" class="form-control icon_control" value="{{$aData['feature_video_link'] ?? ''}}">
                            </div>

                            <div class="form-group">
                                <label><h5>{{'Feature 1 : '}}</h5></label>
                            </div>

                            <div class="text-center">
                                <div class="profile_box">
                                    <div class="profile_pic">
                                        <img id="feature_img" src="@if(!empty($aData['feature_1_image'])) {{url('public/storage/'.Config::get('siteglobal.images_dirs.FEATURE'))}}/{{$aData['feature_1_image']}} @else {{ url('public/images/user.png') }}@endif">
                                        <input type="hidden" id="img_tmp" value="{{ url('public/images/user.png') }}">
                                    </div>
                                    <div  class="upload_pic_link text-center">
                                        <a href="javascript:void(0)">
                                        {{'Upload feature Image'}}<input type="file" id="upload_profile" name="image" accept="image/jpeg,image/png"></a>
                                    </div>
                                </div>
                            </div>

                            <div class="">
                                <div class="form-group">
                                    <label>{{'Title'}}</label>
                                    <input type="text" name="title" id="title" class="form-control icon_control" value="{{ $aData['feature_1_title'] ?? ''}}">
                                </div>
                                <div class="form-group">
                                    <label>{{'Description'}}</label>
                                    <textarea name="description" rows="4" id="description" class="form-control icon_control">{{ $aData['feature_1_description'] ?? ''}}</textarea>
                                </div>    
                            </div>


                            <div class="form-group">
                                <label><h5>{{'Feature 2 : '}}</h5></label>
                            </div>

                            <div class="text-center">
                                <div class="profile_box">
                                    <div class="profile_pic">
                                        <img id="feature_img1" src="@if(!empty($aData['feature_2_image'])) {{url('public/storage/'.Config::get('siteglobal.images_dirs.FEATURE'))}}/{{$aData['feature_2_image']}} @else {{ url('public/images/user.png') }}@endif">
                                        <input type="hidden" id="img_tmp1" value="{{ url('public/images/user.png') }}">
                                    </div>
                                    <div  class="upload_pic_link">
                                        <a href="javascript:void(0)">
                                        {{'Upload feature Image'}}<input type="file" id="upload_profile1" name="image1" accept="image/jpeg,image/png"></a>
                                    </div>
                                </div>
                            </div>

                            <div class="">
                                <div class="form-group">
                                    <label>{{'Title'}}</label>
                                    <input type="text" name="title1" id="title1" class="form-control icon_control" value="{{ $aData['feature_2_title'] ?? ''}}">
                                </div>
                                <div class="form-group">
                                    <label>{{'Description'}}</label>
                                    <textarea name="description1" rows="4" id="description1" class="form-control icon_control">{{ $aData['feature_2_description'] ?? ''}}</textarea>
                                </div>    
                            </div>

                            <div class="form-group">
                                <label><h5>{{'Feature 3 : '}}</h5></label>
                            </div>

                            <div class="text-center">
                                <div class="profile_box">
                                    <div class="profile_pic">
                                        <img id="feature_img2" src="@if(!empty( $aData['feature_3_image'] )) {{url('public/storage/'.Config::get('siteglobal.images_dirs.FEATURE'))}}/{{$aData['feature_3_image']}} @else {{ url('public/images/user.png') }}@endif">
                                        <input type="hidden" id="img_tmp2" value="{{ url('public/images/user.png') }}">
                                    </div>
                                    <div  class="upload_pic_link">
                                        <a href="javascript:void(0)">
                                        {{'Upload feature Image'}}<input type="file" id="upload_profile2" name="image2" accept="image/jpeg,image/png"></a>
                                    </div>
                                </div>
                            </div>

                            <div class="">
                                <div class="form-group">
                                    <label>{{'Title'}}</label>
                                    <input type="text" name="title2" id="title2" class="form-control icon_control" value="{{ $aData['feature_3_title'] ?? ''}}">
                                </div>
                                <div class="form-group">
                                    <label>{{'Description'}}</label>
                                    <textarea name="description2" rows="4" id="description2" class="form-control icon_control">{{ $aData['feature_3_description'] ?? ''}}</textarea>
                                </div>    
                            </div>


                            <div class="form-group">
                                <label><h5>{{'Feature 4 : '}}</h5></label>
                            </div>

                            <div class="text-center">
                                <div class="profile_box">
                                    <div class="profile_pic">
                                        <img id="feature_img3" src="@if(!empty($aData['feature_4_image'])) {{url('public/storage/'.Config::get('siteglobal.images_dirs.FEATURE'))}}/{{$aData['feature_4_image']}} @else {{ url('public/images/user.png') }}@endif">
                                        <input type="hidden" id="img_tmp3" value="{{ url('public/images/user.png') }}">
                                    </div>
                                    <div  class="upload_pic_link text-center">
                                        <a href="javascript:void(0)">
                                        {{'Upload feature Image'}}<input type="file" id="upload_profile3" name="image3" accept="image/jpeg,image/png"></a>
                                    </div>
                                </div>
                            </div>

                            <div class="">
                                <div class="form-group">
                                    <label>{{'Title'}}</label>
                                    <input type="text" name="title3" id="title3" class="form-control icon_control" value="{{ $aData['feature_4_title'] ?? ''}}">
                                </div>
                                <div class="form-group">
                                    <label>{{'Description'}}</label>
                                    <textarea name="description3" rows="4" id="description3" class="form-control icon_control">{{ $aData['feature_4_description'] ?? ''}}</textarea>
                                </div>    
                            </div>


                            <div class="form-group">
                                <label><h5>{{'Feature 5 : '}}</h5></label>
                            </div>

                            <div class="text-center">
                                <div class="profile_box">
                                    <div class="profile_pic">
                                        <img id="feature_img4" src="@if(!empty($aData['feature_5_image'])) {{url('public/storage/'.Config::get('siteglobal.images_dirs.FEATURE'))}}/{{$aData['feature_5_image']}} @else {{ url('public/images/user.png') }}@endif">
                                        <input type="hidden" id="img_tmp4" value="{{ url('public/images/user.png') }}">
                                    </div>
                                    <div  class="upload_pic_link text-center">
                                        <a href="javascript:void(0)">
                                        {{'Upload feature Image'}}<input type="file" id="upload_profile4" name="image4" accept="image/jpeg,image/png"></a>
                                    </div>
                                </div>
                            </div>

                            <div class="">
                                <div class="form-group">
                                    <label>{{'Title'}}</label>
                                    <input type="text" name="title4" id="title4" class="form-control icon_control" value="{{ $aData['feature_5_title'] ?? ''}}">
                                </div>
                                <div class="form-group">
                                    <label>{{'Description'}}</label>
                                    <textarea name="description4" rows="4" id="description4" class="form-control icon_control">{{ $aData['feature_5_description'] ?? ''}}</textarea>
                                </div>    
                            </div>



                            <div class="form-group">
                                <label><h5>{{'Feature 6 : '}}</h5></label>
                            </div>

                            <div class="text-center">
                                <div class="profile_box">
                                    <div class="profile_pic">
                                        <img id="feature_img5" src="@if(!empty($aData['feature_6_image'])) {{url('public/storage/'.Config::get('siteglobal.images_dirs.FEATURE'))}}/{{$aData['feature_6_image']}} @else {{ url('public/images/user.png') }}@endif">
                                        <input type="hidden" id="img_tmp5" value="{{ url('public/images/user.png') }}">
                                    </div>
                                    <div  class="upload_pic_link text-center">
                                        <a href="javascript:void(0)">
                                        {{'Upload feature Image'}}<input type="file" id="upload_profile5" name="image5" accept="image/jpeg,image/png"></a>
                                    </div>
                                </div>
                            </div>

                            <div class="">
                                <div class="form-group">
                                    <label>{{'Title'}}</label>
                                    <input type="text" name="title5" id="title5" class="form-control icon_control" value="{{ $aData['feature_6_title'] ?? ''}}">
                                </div>
                                <div class="form-group">
                                    <label>{{'Description'}}</label>
                                    <textarea name="description5" rows="4" id="description5" class="form-control icon_control">{{ $aData['feature_6_description'] ?? '' }}</textarea>
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
@endsection

@push('custom-scripts')
<script type="text/javascript">
    $(function() {
      showLoader(false);
    });
</script>
<script type="text/javascript" src="{{ url('public/js/dashboard/feature.js') }}"></script>
@endpush