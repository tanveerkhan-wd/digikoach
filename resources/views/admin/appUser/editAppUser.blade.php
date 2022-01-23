@extends('layout.app_with_login')
@section('title','Edit App User')
@section('script', url('public/js/dashboard/app_users.js'))
@section('content')
 <!-- Page Content  -->
<div class="section">
  <div class="container-fluid">
    <h5 class="title"> <a class="ajax_request" data-slug="admin/appUsers" href="{{url('/admin/appUsers')}}"><span>{{' App Users '}}</span></a> > {{'Profile'}}</h5>
        <div class="white_box">
            <div class="theme_tab">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                  <li class="nav-item">
                      <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">Profile</a>
                  </li>
                  {{-- <li class="nav-item">
                      <a class="nav-link" id="live-test-tab" data-toggle="tab" href="#live-test" role="tab" aria-controls="live-test" aria-selected="true">Live Test</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" id="quizze-tab" data-toggle="tab" href="#quizze" role="tab" aria-controls="quizze" aria-selected="true">Quizze</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" id="practice-test-tab" data-toggle="tab" href="#practice-test" role="tab" aria-controls="practice-test" aria-selected="true">Practice Test</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" id="gk-quiz-tab" data-toggle="tab" href="#gk-quiz" role="tab" aria-controls="gk-quiz" aria-selected="true">GK Quiz</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" id="perfoemance-tab" data-toggle="tab" href="#perfoemance" role="tab" aria-controls="perfoemance" aria-selected="true">Performance</a>
                  </li> --}}
                </ul>
                <div class="tab-content" id="myTabContent">
                      <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                          <div class="inner_tab">
                            <form name="edit-app-user-profile-form">
                              <div class="row">
                                  <div class="col-lg-3"></div>
                                  <div class="col-lg-6">

                                    <input type="hidden" name="pkCat" value="{{$data->user_id ?? ''}}">
                                    <div class="">
                                      <label>{{'Profile Image'}}</label>
                                      <div class="text-center">
                                          <div class="profile_box">
                                              <div class="profile_pic">
                                                  <img id="cat1_img" src="@if(!empty($data->user_photo)) {{ url('public/storage/'.Config::get('siteglobal.images_dirs.USERS')) }}{{'/'.$data->user_photo}}  @else {{ url('public/images/user.png') }} @endif">
                                                  <input type="hidden" id="img_tmp1" value="{{ url('public/images/user.png') }}">
                                              </div>
                                              <div  class="upload_pic_link">
                                                  <a href="javascript:void(0)">
                                                  {{'Upload Image'}}<input type="file" id="upload_profile1" name="profile_img" accept="image/jpeg,image/png"></a>
                                              </div>
                                          </div>
                                      </div>

                                      <div class="form-group">
                                          <label>Name</label>
                                          <input type="text" name="name" class="form-control" value="{{$data->name ?? ''}}">
                                      </div>
                                        <div class="form-group">
                                          <label>Phone</label>
                                          <input type="number" name="mobile_number" class="form-control" value="{{$data->mobile_number ?? ''}}">
                                        </div>
                                        <div class="form-group">
                                          <label>Email</label>
                                          <input type="email" name="email" class="form-control" value="{{$data->email ?? ''}}">
                                        </div>
                                        <div class="form-group select2-container">
                                          <label>{{'Category'}}</label>
                                          <select class="form-control icon_control select2" name="parent_category" id="parent_category">
                                              <option value="">{{'Select'}}</option>
                                              @foreach ($category as $key => $value)
                                                  <option value="{{$value['category_id']}}"

                                                  @if(!empty($data->user_fav_category) &&  $data->user_fav_category==$value['category_id']) {{'selected'}} @endif
                                                   >
                                                   {{$value['category_desc'][0]['name']}}
                                                </option>
                                              @endforeach
                                          </select>
                                        </div>

                                        <div class="pt-3" id="get_sub_category">
                                          @foreach($subCate as $key=>$values)
                                            @foreach($values->category_desc as $key1=>$val)
                                              @if(!empty($val->lang_code) && $val->lang_code=='en')
                                                <div class="form-group">
                                                    <label class="form-control add_col_class 
                                                    @foreach($userCateId as $userVal)
                                                    @if($values->category_id==$userVal->category_id) active_category 
                                                    @endif
                                                    @endforeach
                                                    ">
                                                      <input type="checkbox" name="active_category[]" style="opacity: 0;" class="mybox" value="{{$val->category_id ?? ''}}" 
                                                      @foreach($userCateId as $userVal)
                                                        @if($values->category_id==$userVal->category_id)
                                                          checked 
                                                        @endif
                                                      @endforeach>{{$val->name ?? ''}}
                                                    </label>
                                                </div>
                                              @endif
                                            @endforeach
                                          @endforeach
                                      </div>

                                    </div>
                                    

                                    <div class="text-center">
                                        <button class="theme_btn">Save</button>
                                        <a class="theme_btn red_btn ajax_request no_sidebar_active" data-slug="admin/appUsers" href="{{ url('admin/appUsers') }}">{{$translations['gn_cancel'] ?? 'Cancel'}}</a>
                                    </div>
                                  </div>
                                  <div class="col-lg-3"></div>
                              </div>
                            </form>
                          </div>
                      </div>
                     {{--  
                      <div class="tab-pane fade show" id="live-test" role="tabpanel" aria-labelledby="live-test-tab">
                          <div class="inner_tab">
                              
                          </div>
                      </div>

                      <div class="tab-pane fade show" id="quizze" role="tabpanel" aria-labelledby="quizze-tab">
                          <div class="inner_tab">
                              
                          </div>
                      </div>

                      <div class="tab-pane fade show" id="practice-test" role="tabpanel" aria-labelledby="practice-test-tab">
                          <div class="inner_tab">
                              
                          </div>
                      </div> --}}
                  </div>

            </div>
        </div>
      </div>
  </div>
<input type="hidden" id="errors" value="{{$errors ?? ''}}">
          
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
<!-- End Content Body -->
@endsection
@push('custom-styles')
@endpush
@push('datatable-scripts')
<!-- Include this Page JS -->
<script src="{{ url('public/bower_components/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript" src="{{ url('public/js/dashboard/app_users.js') }}"></script>
@endpush