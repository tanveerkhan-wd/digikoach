@extends('layout.app_with_login')
@section('title','View App User')
@section('script', url('public/js/dashboard/app_users.js'))
@section('content')
 <!-- Page Content  -->
<div class="section">
  <div class="container-fluid">
      <h5 class="title"> <a class="ajax_request" data-slug="admin/appUsers" href="{{url('/admin/appUsers')}}"><span>{{' App Users '}}</span></a> > {{'Profile'}}</h5>
        <div class="white_box">
                @php
                  $active = [];
                  if (Request::get('type')=='LIVE_TEST') {
                    $active['LIVE_TEST'] = 'active';
                  }elseif(Request::get('type')=='QUIZZES') {
                    $active['QUIZZES'] = 'active';
                  }elseif(Request::get('type')=='PRACTICE_TEST') {
                    $active['PRACTICE_TEST'] = 'active';
                  }elseif(Request::get('type')=='GK_CA') {
                    $active['GK_CA'] = 'active';
                  }else{
                    $active['PROFILE'] = 'active';
                  }
                  
                @endphp
            <div class="theme_tab">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                  <li class="nav-item">
                      <a class="nav-link @if(isset($active['PROFILE'])){{$active['PROFILE']}}@endif" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">Profile</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link @if(isset($active['LIVE_TEST'])){{$active['LIVE_TEST']}}@endif" id="live-test-tab" data-toggle="tab" href="#live-test" role="tab" aria-controls="live-test" aria-selected="true">Live Test</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link @if(isset($active['QUIZZES'])){{$active['QUIZZES']}}@endif" id="quizze-tab" data-toggle="tab" href="#quizze" role="tab" aria-controls="quizze" aria-selected="true">Quizzes</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link @if(isset($active['PRACTICE_TEST'])){{$active['PRACTICE_TEST']}}@endif" id="practice-test-tab" data-toggle="tab" href="#practice-test" role="tab" aria-controls="practice-test" aria-selected="true">Practice Test</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link @if(isset($active['GK_CA'])){{$active['GK_CA']}}@endif" id="gk-quiz-tab" data-toggle="tab" href="#gk-quiz" role="tab" aria-controls="gk-quiz" aria-selected="true">GK Quiz</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" id="perfoemance-tab" data-toggle="tab" href="#perfoemance" role="tab" aria-controls="perfoemance" aria-selected="true">Performance</a>
                  </li>
                </ul>
                
                <div class="tab-content" id="myTabContent">
                      <div class="tab-pane fade show @if(isset($active['PROFILE'])){{$active['PROFILE']}}@endif" id="profile" role="tabpanel" aria-labelledby="profile-tab">
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
                                          </div>
                                      </div>

                                      <div class="form-group">
                                          <label>Name</label>
                                          <input type="text" name="name" class="form-control" value="{{$data->name ?? ''}}" disabled>
                                        </div>
                                        <div class="form-group">
                                          <label>Phone</label>
                                          <input type="number" name="mobile_number" class="form-control" value="{{$data->mobile_number ?? ''}}" disabled>
                                        </div>
                                        <div class="form-group">
                                          <label>Email</label>
                                          <input type="email" name="email" class="form-control" value="{{$data->email ?? ''}}" disabled>
                                        </div>
                                        

                                        <div class="form-group select2-container">
                                          <label>{{'Category'}}</label>
                                          <select class="form-control icon_control select2" name="parent_category" id="parent_category" disabled>
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
                                                      <input type="radio" style="opacity: 0;" value="{{$val->category_id ?? ''}}" 
                                                      @foreach($userCateId as $userVal)
                                                        @if($values->category_id==$userVal->category_id)
                                                          checked 
                                                        @endif
                                                      @endforeach readonly>{{$val->name ?? ''}}
                                                    </label>
                                                </div>
                                              @endif
                                            @endforeach
                                          @endforeach
                                      </div>
                                        

                                    </div>
                                    <div class="text-center">
                                        <a class="theme_btn red_btn ajax_request no_sidebar_active" data-slug="admin/appUsers" href="{{ url('admin/appUsers') }}">{{$translations['gn_cancel'] ?? 'Cancel'}}</a>
                                    </div>
                                  </div>
                                  <div class="col-lg-3"></div>
                              </div>
                            </form>
                          </div>
                      </div>
                      
                      <div class="tab-pane fade show @if(isset($active['LIVE_TEST'])){{$active['LIVE_TEST']}}@endif" id="live-test" role="tabpanel" aria-labelledby="live-test-tab">
                          <div class="inner_tab">
                            <div class="section">
                              <div class="container-fluid">
                                <input type="hidden" id="get_user_id" value="{{$data->user_id ?? ''}}">
                                <div class="row ">
                                    <div class="col-md-3 text-md-right mb-3">
                                        <div class="row">
                                            <div class="col-2 m-auto">
                                                <label class="blue_label">Date</label>
                                            </div>
                                            <div class="col-10">
                                                <input type="text" class="form-control icon_control datepicker1" autocomplete="off" id="search_date_from">
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-md-3 text-md-right mb-3">
                                        <div class="row">
                                            <div class="col-2 m-auto">
                                                <label class="blue_label text-center">To</label>
                                            </div>
                                            <div class="col-10">
                                                <input type="text" class="form-control icon_control datepicker1" autocomplete="off"  id="search_date_to">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        
                                    </div>  
                                    <div class="col-md-2 col-6 mb-3">
                                        
                                    </div>
                                        
                                </div>

                                <div class="row ">
                                    
                                    <div class="col-md-4 mb-3">
                                        <input type="text" id="search_live_test" class="form-control icon_control search_control" placeholder="{{'Search'}}">
                                    </div> 
                                    <div class="col-md-3 text-md-right mb-3">
                                        
                                    </div> 
                                    <div class="col-md-5 text-md-right mb-3">
                                        <div class="row">
                                            <div class="col-3 m-auto">
                                                <label class="blue_label">Category</label>
                                            </div>
                                            <div class="col-9">
                                                <select class="form-control icon_control dropdown_control " id="search_category">
                                                    <option value="" selected>Select</option>
                                                    
                                                    @foreach ($category as $key => $value)
                                                                <option value="{{$value['category_id']}}">{{$value['category_desc'][0]['name']}}</option>
                                                                
                                                                @if(!empty($value['children']))
                                                                @foreach ($value['children'] as $key1 => $value1) 
                                                                    <option value="{{$value1['category_id']}}">{{$value['category_desc'][0]['name']}} > {{$value1['category_desc'][0]['name']}}</option>

                                                                    @if(!empty($value1['children']))
                                                                    @foreach ($value1['children'] as $key2 => $value2) 
                                                                        <option value="{{$value2['category_id']}}">{{$value['category_desc'][0]['name']}} > {{$value1['category_desc'][0]['name']}} > {{$value2['category_desc'][0]['name']}}</option>

                                                                        @if(!empty($value2['children']))
                                                                        @foreach ($value2['children'] as $key3 => $value3)
                                                                            <option value="{{$value3['category_id']}}">{{$value['category_desc'][0]['name']}} > {{$value1['category_desc'][0]['name']}} > {{$value2['category_desc'][0]['name']}} > {{$value3['category_desc'][0]['name']}}</option>
                                                                            
                                                                            @if(!empty($value3['children']))
                                                                            @foreach ($value3['children'] as $key4 => $value4)
                                                                                <option value="{{$value4['category_id']}}">{{$value['category_desc'][0]['name']}} > {{$value1['category_desc'][0]['name']}} > {{$value2['category_desc'][0]['name']}} > {{$value4['category_desc'][0]['name']}}</option>
                                                                            @endforeach
                                                                            @endif

                                                                        @endforeach
                                                                        @endif

                                                                    @endforeach
                                                                    @endif

                                                                @endforeach
                                                                @endif

                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
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
                          </div>
                      </div>

                      <div class="tab-pane fade show @if(isset($active['QUIZZES'])){{$active['QUIZZES']}}@endif" id="quizze" role="tabpanel" aria-labelledby="quizze-tab">
                          <div class="inner_tab">
                            <div class="section">
                                <div class="container-fluid">
                                  <input type="hidden" id="get_user_id" value="{{$data->user_id ?? ''}}">
                                  <div class="row ">
                                      <div class="col-md-4 mb-3">
                                          <input type="text" id="search_quiz_test" class="form-control icon_control search_control" placeholder="{{'Search'}}">
                                      </div> 
                                      <div class="col-md-3 text-md-right mb-3">
                                          
                                      </div> 
                                      <div class="col-md-5 text-md-right mb-3">
                                          <div class="row">
                                              <div class="col-3 m-auto">
                                                  <label class="blue_label">Category</label>
                                              </div>
                                              <div class="col-9">
                                                  <select class="form-control icon_control dropdown_control " id="search_category2">
                                                      <option value="" selected>Select</option>
                                                      
                                                      @foreach ($category as $key => $value)
                                                                  <option value="{{$value['category_id']}}">{{$value['category_desc'][0]['name']}}</option>
                                                                  
                                                                  @if(!empty($value['children']))
                                                                  @foreach ($value['children'] as $key1 => $value1) 
                                                                      <option value="{{$value1['category_id']}}">{{$value['category_desc'][0]['name']}} > {{$value1['category_desc'][0]['name']}}</option>

                                                                      @if(!empty($value1['children']))
                                                                      @foreach ($value1['children'] as $key2 => $value2) 
                                                                          <option value="{{$value2['category_id']}}">{{$value['category_desc'][0]['name']}} > {{$value1['category_desc'][0]['name']}} > {{$value2['category_desc'][0]['name']}}</option>

                                                                          @if(!empty($value2['children']))
                                                                          @foreach ($value2['children'] as $key3 => $value3)
                                                                              <option value="{{$value3['category_id']}}">{{$value['category_desc'][0]['name']}} > {{$value1['category_desc'][0]['name']}} > {{$value2['category_desc'][0]['name']}} > {{$value3['category_desc'][0]['name']}}</option>
                                                                              
                                                                              @if(!empty($value3['children']))
                                                                              @foreach ($value3['children'] as $key4 => $value4)
                                                                                  <option value="{{$value4['category_id']}}">{{$value['category_desc'][0]['name']}} > {{$value1['category_desc'][0]['name']}} > {{$value2['category_desc'][0]['name']}} > {{$value4['category_desc'][0]['name']}}</option>
                                                                              @endforeach
                                                                              @endif

                                                                          @endforeach
                                                                          @endif

                                                                      @endforeach
                                                                      @endif

                                                                  @endforeach
                                                                  @endif

                                                      @endforeach
                                                  </select>
                                              </div>
                                          </div>
                                      </div>
                                  </div>

                                  <div class="row">
                                      <div class="col-12">
                                          <div class="theme_table">
                                              <div class="table-responsive">
                                                  <table id="quiz_test_listing" class="display" style="width:100%">
                                                      <thead>
                                                          <tr>
                                                              <th>{{'Sr. No.'}}</th>
                                                              <th>{{'Test Name'}}</th>
                                                              <th>{{'Category'}}</th>
                                                              <th>{{'Total Question'}}</th>
                                                              <th>{{'Total Marks'}}</th>
                                                              <th>{{'Test Duration'}}</th>
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
                          </div>
                      </div>

                      <div class="tab-pane fade show @if(isset($active['PRACTICE_TEST'])){{$active['PRACTICE_TEST']}}@endif" id="practice-test" role="tabpanel" aria-labelledby="practice-test-tab">
                          <div class="inner_tab">
                            <div class="section">
                                <div class="container-fluid">
                                  <input type="hidden" id="get_user_id" value="{{$data->user_id ?? ''}}">
                                  <div class="row ">
                                      <div class="col-md-4 mb-3">
                                          <input type="text" id="search_practice_test" class="form-control icon_control search_control" placeholder="{{'Search'}}">
                                      </div> 
                                      <div class="col-md-3 text-md-right mb-3">
                                          
                                      </div> 
                                      <div class="col-md-5 text-md-right mb-3">
                                          <div class="row">
                                              <div class="col-3 m-auto">
                                                  <label class="blue_label">Category</label>
                                              </div>
                                              <div class="col-9">
                                                  <select class="form-control icon_control dropdown_control " id="search_category1">
                                                      <option value="" selected>Select</option>
                                                      
                                                      @foreach ($category as $key => $value)
                                                                  <option value="{{$value['category_id']}}">{{$value['category_desc'][0]['name']}}</option>
                                                                  
                                                                  @if(!empty($value['children']))
                                                                  @foreach ($value['children'] as $key1 => $value1) 
                                                                      <option value="{{$value1['category_id']}}">{{$value['category_desc'][0]['name']}} > {{$value1['category_desc'][0]['name']}}</option>

                                                                      @if(!empty($value1['children']))
                                                                      @foreach ($value1['children'] as $key2 => $value2) 
                                                                          <option value="{{$value2['category_id']}}">{{$value['category_desc'][0]['name']}} > {{$value1['category_desc'][0]['name']}} > {{$value2['category_desc'][0]['name']}}</option>

                                                                          @if(!empty($value2['children']))
                                                                          @foreach ($value2['children'] as $key3 => $value3)
                                                                              <option value="{{$value3['category_id']}}">{{$value['category_desc'][0]['name']}} > {{$value1['category_desc'][0]['name']}} > {{$value2['category_desc'][0]['name']}} > {{$value3['category_desc'][0]['name']}}</option>
                                                                              
                                                                              @if(!empty($value3['children']))
                                                                              @foreach ($value3['children'] as $key4 => $value4)
                                                                                  <option value="{{$value4['category_id']}}">{{$value['category_desc'][0]['name']}} > {{$value1['category_desc'][0]['name']}} > {{$value2['category_desc'][0]['name']}} > {{$value4['category_desc'][0]['name']}}</option>
                                                                              @endforeach
                                                                              @endif

                                                                          @endforeach
                                                                          @endif

                                                                      @endforeach
                                                                      @endif

                                                                  @endforeach
                                                                  @endif

                                                      @endforeach
                                                  </select>
                                              </div>
                                          </div>
                                      </div>
                                  </div>

                                  <div class="row">
                                      <div class="col-12">
                                          <div class="theme_table">
                                              <div class="table-responsive">
                                                  <table id="practice_test_listing" class="display" style="width:100%">
                                                      <thead>
                                                          <tr>
                                                              <th>{{'Sr. No.'}}</th>
                                                              <th>{{'Category'}}</th>
                                                              <th class="text-center">{{'Actions'}}</th>
                                                          </tr>
                                                      </thead>
                                                  </table>
                                              </div>
                                          </div>
                                      </div>
                                  </div>

                                </div>
                            </div>  
                          </div>
                      </div>

                      <div class="tab-pane fade show @if(isset($active['GK_CA'])){{$active['GK_CA']}}@endif" id="gk-quiz" role="tabpanel" aria-labelledby="gk-quiz-tab">
                          <div class="inner_tab">
                            <div class="section">
                                <div class="container-fluid">
                                  <input type="hidden" id="get_user_id" value="{{$data->user_id ?? ''}}">
                                  <div class="row ">
                                      <div class="col-md-4 mb-3">
                                          <input type="text" id="search_gk_quiz_test" class="form-control icon_control search_control" placeholder="{{'Search'}}">
                                      </div> 
                                      <div class="col-md-3 text-md-right mb-3">
                                          
                                      </div> 
                                      <div class="col-md-5 text-md-right mb-3">
                                          <div class="row">
                                              <div class="col-3 m-auto">
                                                  <label class="blue_label">Category</label>
                                              </div>
                                              <div class="col-9">
                                                  <select class="form-control icon_control dropdown_control " id="search_category1">
                                                      <option value="" selected>Select</option>
                                                      
                                                      @foreach ($category as $key => $value)
                                                                  <option value="{{$value['category_id']}}">{{$value['category_desc'][0]['name']}}</option>
                                                                  
                                                                  @if(!empty($value['children']))
                                                                  @foreach ($value['children'] as $key1 => $value1) 
                                                                      <option value="{{$value1['category_id']}}">{{$value['category_desc'][0]['name']}} > {{$value1['category_desc'][0]['name']}}</option>

                                                                      @if(!empty($value1['children']))
                                                                      @foreach ($value1['children'] as $key2 => $value2) 
                                                                          <option value="{{$value2['category_id']}}">{{$value['category_desc'][0]['name']}} > {{$value1['category_desc'][0]['name']}} > {{$value2['category_desc'][0]['name']}}</option>

                                                                          @if(!empty($value2['children']))
                                                                          @foreach ($value2['children'] as $key3 => $value3)
                                                                              <option value="{{$value3['category_id']}}">{{$value['category_desc'][0]['name']}} > {{$value1['category_desc'][0]['name']}} > {{$value2['category_desc'][0]['name']}} > {{$value3['category_desc'][0]['name']}}</option>
                                                                              
                                                                              @if(!empty($value3['children']))
                                                                              @foreach ($value3['children'] as $key4 => $value4)
                                                                                  <option value="{{$value4['category_id']}}">{{$value['category_desc'][0]['name']}} > {{$value1['category_desc'][0]['name']}} > {{$value2['category_desc'][0]['name']}} > {{$value4['category_desc'][0]['name']}}</option>
                                                                              @endforeach
                                                                              @endif

                                                                          @endforeach
                                                                          @endif

                                                                      @endforeach
                                                                      @endif

                                                                  @endforeach
                                                                  @endif

                                                      @endforeach
                                                  </select>
                                              </div>
                                          </div>
                                      </div>
                                  </div>

                                  <div class="row">
                                      <div class="col-12">
                                          <div class="theme_table">
                                              <div class="table-responsive">
                                                  <table id="gk_quiz_test_listing" class="display" style="width:100%">
                                                      <thead>
                                                          <tr>
                                                              <th>{{'Sr. No.'}}</th>
                                                              <th>{{'Test Name'}}</th>
                                                              <th>{{'Total Question'}}</th>
                                                              <th>{{'Total Marks'}}</th>
                                                              <th>{{'Test Duration'}}</th>
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
                          </div>
                      </div>

                      <div class="tab-pane fade show" id="perfoemance" role="tabpanel" aria-labelledby="perfoemance-tab">
                          <div class="inner_tab">

                              <ul class="nav nav-tabs" id="myTab1" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="live-test-graph-tab" data-toggle="tab" href="#live-test-graph" role="tab" aria-controls="live-test-graph" aria-selected="true">Live Test Graph</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="quiz-test-graph-tab" data-toggle="tab" href="#quiz-test-graph" role="tab" aria-controls="quiz-test-graph" aria-selected="true">Quiz Test Graph</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="practice-test-graph-tab" data-toggle="tab" href="#practice-test-graph" role="tab" aria-controls="practice-test-graph" aria-selected="true">Practice Test Graph</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="gkCa-test-graph-tab" data-toggle="tab" href="#gkCa-test-graph" role="tab" aria-controls="gkCa-test-graph" aria-selected="true">Gk & Ca Quiz Test Graph</a>
                                </li>
                              </ul>

                              <div class="tab-content" id="myTabContent1">
                                
                                <div class="tab-pane fade show active" id="live-test-graph" role="tabpanel" aria-labelledby="live-test-graph-tab">
                                  <div class="inner_tab">
                                    
                                    <div class="container-fluid">
                                      <div class="row">
                                          <div class="col-12">
                                              <div class="chart_section">
                                                  <form id="search-form">
                                                      <div class="row">
                                                          <div class="col-md-6 col-sm-6"></div>
                                                          <div class="col-md-3 col-sm-6">
                                                              <div class="form-group "> 
                                                                  <input type="text" name="start_date" id="start_date" class="datepicker1 date_control form-control icon_control pl-1" placeholder="Date From" autocomplete="off">
                                                              </div>
                                                          </div>
                                                          <div class="col-md-3 col-sm-6">
                                                              <div class="form-group "> 
                                                                  <div class="form-group "> 
                                                                  <input type="text" name="end_date" id="end_date" class="datepicker1 date_control form-control icon_control pl-1" placeholder="Date To" autocomplete="off">
                                                              </div>
                                                              </div>
                                                          </div>
                                                      </div>
                                                  </form>
                                                  <div id="liveTestGraph"></div>
                                              </div>
                                          </div>
                                      </div>
                                    </div>

                                  </div>
                                </div>

                                <div class="tab-pane fade show" id="quiz-test-graph" role="tabpanel" aria-labelledby="quiz-test-graph-tab">
                                  <div class="inner_tab">
                                  
                                    <div class="container-fluid">
                                      <div class="row">
                                          <div class="col-12">
                                              <div class="chart_section">
                                                  <form id="search-form">
                                                      <div class="row">
                                                          <div class="col-md-6 col-sm-6"></div>
                                                          <div class="col-md-3 col-sm-6">
                                                              <div class="form-group "> 
                                                                  <input type="text" name="start_date" id="start_date1" class="datepicker1 date_control form-control icon_control pl-1" placeholder="Date From" autocomplete="off">
                                                              </div>
                                                          </div>
                                                          <div class="col-md-3 col-sm-6">
                                                              <div class="form-group "> 
                                                                  <div class="form-group "> 
                                                                  <input type="text" name="end_date" id="end_date1" class="datepicker1 date_control form-control icon_control pl-1" placeholder="Date To" autocomplete="off">
                                                              </div>
                                                              </div>
                                                          </div>
                                                      </div>
                                                  </form>
                                                  <div id="quizTestGraph"></div>
                                              </div>
                                          </div>
                                      </div>
                                    </div>
                                    
                                  </div>
                                </div>

                                <div class="tab-pane fade show" id="practice-test-graph" role="tabpanel" aria-labelledby="practice-test-graph-tab">
                                  <div class="inner_tab">
                                    
                                    <div class="container-fluid">
                                      <div class="row">
                                          <div class="col-12">
                                              <div class="chart_section">
                                                  <form id="search-form">
                                                      <div class="row">
                                                          <div class="col-md-6 col-sm-6"></div>
                                                          <div class="col-md-3 col-sm-6">
                                                              <div class="form-group "> 
                                                                  <input type="text" name="start_date" id="start_date2" class="datepicker1 date_control form-control icon_control pl-1" placeholder="Date From" autocomplete="off">
                                                              </div>
                                                          </div>
                                                          <div class="col-md-3 col-sm-6">
                                                              <div class="form-group "> 
                                                                  <div class="form-group "> 
                                                                  <input type="text" name="end_date" id="end_date2" class="datepicker1 date_control form-control icon_control pl-1" placeholder="Date To" autocomplete="off">
                                                              </div>
                                                              </div>
                                                          </div>
                                                      </div>
                                                  </form>
                                                  <div id="practiceTestGraph"></div>
                                              </div>
                                          </div>
                                      </div>
                                    </div>
                                    
                                  </div>
                                </div>

                                <div class="tab-pane fade show" id="gkCa-test-graph" role="tabpanel" aria-labelledby="gkCa-test-graph-tab">
                                  <div class="inner_tab">
                                    
                                    <div class="container-fluid">
                                      <div class="row">
                                          <div class="col-12">
                                              <div class="chart_section">
                                                  <form id="search-form">
                                                      <div class="row">
                                                          <div class="col-md-6 col-sm-6"></div>
                                                          <div class="col-md-3 col-sm-6">
                                                              <div class="form-group "> 
                                                                  <input type="text" name="start_date" id="start_date3" class="datepicker1 date_control form-control icon_control pl-1" placeholder="Date From" autocomplete="off">
                                                              </div>
                                                          </div>
                                                          <div class="col-md-3 col-sm-6">
                                                              <div class="form-group "> 
                                                                  <div class="form-group "> 
                                                                  <input type="text" name="end_date" id="end_date3" class="datepicker1 date_control form-control icon_control pl-1" placeholder="Date To" autocomplete="off">
                                                              </div>
                                                              </div>
                                                          </div>
                                                      </div>
                                                  </form>
                                                  <div id="gkCaTestGraph"></div>
                                              </div>
                                          </div>
                                      </div>
                                    </div>
                                    
                                  </div>
                                </div>

                              </div>
                                
                          </div>
                      </div>

                  </div>

            </div>
        </div>
      </div>
</div>
<input type="hidden" id="user_id_data" value="{{$data->user_id ?? ''}}">          
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
{{-- <script src="{{ url('public/bower_components/ckeditor/ckeditor.js') }}"></script> --}}
<script src="{{ url('public/js/common/highchart/highcharts.js') }}"></script>
<script src="{{ url('public/js/common/highchart/exporting.js') }}"></script>
<script src="{{ url('public/js/common/highchart/export-data.js') }}"></script>
<script src="{{ url('public/js/common/highchart/accessibility.js') }}"></script>
<script type="text/javascript" src="{{ url('public/js/dashboard/app_users.js') }}"></script>
<script>


  //_____liveTestGraph_____//

  var liveTestData = <?php  echo isset($testData['LIVE_TEST']) ? json_encode($testData['LIVE_TEST']['user_percentage']):"[]" ?>;
  var liveTestName = <?php  echo isset($testData['LIVE_TEST']) ? json_encode($testData['LIVE_TEST']['exam_name']):"[]" ?>;

  var chartAgency = Highcharts.chart('liveTestGraph', {
      chart: {
          type: 'column'
      },
      title: {
          text: ''
      },
      xAxis: {
          categories: liveTestName,
          crosshair: true
      },
      yAxis: {
          min: 0,
          title: {
              text: 'Percentage %'
          }
      },
      tooltip: {
          headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
          pointFormat: '<tr><td style="color:{series.color};padding:0">{categories.name}</td>' +
                  '<td style="padding:0"><b> Percentage {point.y}%</b></td></tr>',
          footerFormat: '</table>',
          shared: true,
          useHTML: true
      },
      plotOptions: {
          column: {
              pointPadding: 0.2,
              borderWidth: 0
          }
      },
      series: [{
              name: 'Live Test',
              data: liveTestData,
              color: '#2682df'
          }
      ],
      credits: {
          enabled: false
      },
      exporting: {enabled: false}
  },function(chartAgency) { // on complete
    if (chartAgency.series[0].data.length <1) {
      chartAgency.renderer.text('No Data Found', 200, 220).css({color: '#4572A7',fontSize: '16px'}).add()
    }
  });

  
  $(document).on('change', '#start_date, #end_date', function() {
      var name = $(this).attr("name");
      var end_date = '';
      var start_date = '';
      var user_id = $("#user_id_data").val();
      if (name=='start_date') {
        start_date = $(this).val();
        end_date = $(this).parent().parent().parent().find('#end_date').val();
      }else{
        start_date = $(this).parent().parent().parent().parent().find('#start_date').val();
        end_date = $(this).val();
      }
      showLoader(true);
      $.ajax({
          type: "POST",
          dataType: 'json',
          url: base_url+'/admin/liveTestPerformmanceChart',
          data: {user_id:user_id,start_date: start_date,end_date:end_date,type:'live'},
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(data) {
              chartAgency.series[0].setData(data.percentage);
              chartAgency.xAxis[0].setCategories(data.name);
              if (data.percentage.length == 0) {
                chartAgency.renderer.text('No Data Found', 200, 220).attr({textaatr: 1}).css({color: '#4572A7',fontSize: '16px'}).add();
              }else{
                $('[textaatr="1"]').hide(); 
              }
              showLoader(false);
          }
      });
  });


  //_____ quizTestGraph _____//

  var quizTestData = <?php  echo isset($testData['QUIZZES']) ? json_encode($testData['QUIZZES']['user_percentage']):"[]" ?>;
  var quizTestName = <?php  echo isset($testData['QUIZZES']) ? json_encode($testData['QUIZZES']['exam_name']):"[]" ?>;

  var chartAgency1 = Highcharts.chart('quizTestGraph', {
      chart: {
          type: 'column'
      },
      title: {
          text: ''
      },
      xAxis: {
          categories: quizTestName,
          crosshair: true
      },
      yAxis: {
          min: 0,
          title: {
              text: 'Percentage %'
          }
      },
      tooltip: {
          headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
          pointFormat: '<tr><td style="color:{series.color};padding:0">{categories.name}</td>' +
                  '<td style="padding:0"><b> Percentage {point.y}%</b></td></tr>',
          footerFormat: '</table>',
          shared: true,
          useHTML: true
      },
      plotOptions: {
          column: {
              pointPadding: 0.2,
              borderWidth: 0
          }
      },
      series: [{
              name: 'Quiz Test',
              data: quizTestData,
              color: '#2682df'
          }
      ],
      credits: {
          enabled: false
      },
      exporting: {enabled: false}
  },function(chartAgency1) { // on complete
    if (chartAgency1.series[0].data.length <1) {
      chartAgency1.renderer.text('No Data Found', 200, 220).css({color: '#4572A7',fontSize: '16px'}).add()
    }
  });

  $(document).on('change', '#start_date1, #end_date1', function() {
      showLoader(true);
      var name = $(this).attr("name");
      var end_date = '';
      var start_date = '';
      var user_id = $("#user_id_data").val();
      if (name=='start_date') {
        start_date = $(this).val();
        end_date = $(this).parent().parent().parent().find('#end_date1').val();
      }else{
        start_date = $(this).parent().parent().parent().parent().find('#start_date1').val();
        end_date = $(this).val();
      }
      $.ajax({
          type: "POST",
          dataType: 'json',
          url: base_url+'/admin/liveTestPerformmanceChart',
          data: {user_id:user_id,start_date: start_date,end_date:end_date,type:'quiz'},
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(data) {
              chartAgency1.series[0].setData(data.percentage);
              chartAgency1.xAxis[0].setCategories(data.name);
              if (data.percentage.length == 0) {
                chartAgency1.renderer.text('No Data Found', 200, 220).attr({textaatr: 1}).css({color: '#4572A7',fontSize: '16px'}).add();
              }else{
                $('[textaatr="1"]').hide(); 
              }
              showLoader(false);
          }
      });
  });



  //_____ practiceTestGraph _____//

  var practiceTestData = <?php  echo isset($testData['PRACTICE_TEST']) ? json_encode($testData['PRACTICE_TEST']['user_percentage']):"[]" ?>;
  var practiceTestName = <?php  echo isset($testData['PRACTICE_TEST']) ? json_encode($testData['PRACTICE_TEST']['exam_name']):"[]" ?>;

  var chartAgency2 = Highcharts.chart('practiceTestGraph', {
      chart: {
          type: 'column'
      },
      title: {
          text: ''
      },
      xAxis: {
          categories: practiceTestName,
          crosshair: true
      },
      yAxis: {
          min: 0,
          title: {
              text: 'Percentage %'
          }
      },
      tooltip: {
          headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
          pointFormat: '<tr><td style="color:{series.color};padding:0">{categories.name}</td>' +
                  '<td style="padding:0"><b> Percentage {point.y}%</b></td></tr>',
          footerFormat: '</table>',
          shared: true,
          useHTML: true
      },
      plotOptions: {
          column: {
              pointPadding: 0.2,
              borderWidth: 0
          }
      },
      series: [{
              name: 'Practice Test',
              data: practiceTestData,
              color: '#2682df'
          }
      ],
      credits: {
          enabled: false
      },
      exporting: {enabled: false}
  },function(chartAgency2) { // on complete
    if (chartAgency2.series[0].data.length <1) {
      chartAgency2.renderer.text('No Data Found', 200, 220).css({color: '#4572A7',fontSize: '16px'}).add()
    }
  });

  $(document).on('change', '#start_date2, #end_date2', function() {
      showLoader(true);
      var name = $(this).attr("name");
      var end_date = '';
      var start_date = '';
      var user_id = $("#user_id_data").val();
      if (name=='start_date') {
        start_date = $(this).val();
        end_date = $(this).parent().parent().parent().find('#end_date2').val();
      }else{
        start_date = $(this).parent().parent().parent().parent().find('#start_date2').val();
        end_date = $(this).val();
      }
      $.ajax({
          type: "POST",
          dataType: 'json',
          url: base_url+'/admin/liveTestPerformmanceChart',
          data: {user_id:user_id,start_date: start_date,end_date:end_date,type:'practice'},
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(data) {
              chartAgency2.series[0].setData(data.percentage);
              chartAgency2.xAxis[0].setCategories(data.name);
              if (data.percentage.length == 0) {
                chartAgency2.renderer.text('No Data Found', 200, 220).attr({textaatr: 1}).css({color: '#4572A7',fontSize: '16px'}).add();
              }else{
                $('[textaatr="1"]').hide(); 
              }
              showLoader(false);
          }
      });
  });




  // //_____ gkCaTestGraph _____//

  var gkCaTestData = <?php  echo isset($testData['GK_CA']) ? json_encode($testData['GK_CA']['user_percentage']):"[]" ?>;
  var gkCaTestName = <?php  echo isset($testData['GK_CA']) ? json_encode($testData['GK_CA']['exam_name']):"[]" ?>;

  var chartAgency3 = Highcharts.chart('gkCaTestGraph', {
      chart: {
          type: 'column'
      },
      title: {
          text: ''
      },
      xAxis: {
          categories: gkCaTestName,
          crosshair: true
      },
      yAxis: {
          min: 0,
          title: {
              text: 'Percentage %'
          }
      },
      tooltip: {
          headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
          pointFormat: '<tr><td style="color:{series.color};padding:0">{categories.name}</td>' +
                  '<td style="padding:0"><b> Percentage {point.y}%</b></td></tr>',
          footerFormat: '</table>',
          shared: true,
          useHTML: true
      },
      plotOptions: {
          column: {
              pointPadding: 0.2,
              borderWidth: 0
          }
      },
      series: [{
              name: 'Gk & Ca Test',
              data: gkCaTestData,
              color: '#2682df'
          }
      ],
      credits: {
          enabled: false
      },
      exporting: {enabled: false}
  },function(chartAgency3) { // on complete
    if (chartAgency3.series[0].data.length <1) {
      chartAgency3.renderer.text('No Data Found', 200, 220).css({color: '#4572A7',fontSize: '16px'}).add()
    }
  });

  $(document).on('change', '#start_date3, #end_date3', function() {
      showLoader(true);
      var name = $(this).attr("name");
      var end_date = '';
      var start_date = '';
      var user_id = $("#user_id_data").val();
      if (name=='start_date') {
        start_date = $(this).val();
        end_date = $(this).parent().parent().parent().find('#end_date3').val();
      }else{
        start_date = $(this).parent().parent().parent().parent().find('#start_date3').val();
        end_date = $(this).val();
      }
      $.ajax({
          type: "POST",
          dataType: 'json',
          url: base_url+'/admin/liveTestPerformmanceChart',
          data: {user_id:user_id,start_date: start_date,end_date:end_date,type:'gk_ca'},
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(data) {
              chartAgency3.series[0].setData(data.percentage);
              chartAgency3.xAxis[0].setCategories(data.name);
              if (data.percentage.length == 0) {
                chartAgency3.renderer.text('No Data Found', 200, 220).attr({textaatr: 1}).css({color: '#4572A7',fontSize: '16px'}).add();
              }else{
                $('[textaatr="1"]').hide(); 
              }
              showLoader(false);
          }
      });
  });
  

</script>
@endpush