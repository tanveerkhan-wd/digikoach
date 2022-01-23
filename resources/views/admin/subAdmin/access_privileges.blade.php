
@extends('layout.app_with_login')
@section('title','Add Access Privileges')
@section('script', url('public/js/dashboard/sub_admins.js'))
@section('content') 
<!-- Page Content  -->
<div class="section">
    <div class="container-fluid">
        <h2 class="title"><a class="ajax_request" data-slug="admin/subAdmin" href="{{url('/admin/subAdmin')}}"><span>Sub-Admins</span></a>  > <span> Add </span> > Access Privileges </h2>
        <div class="white_box">
            <div class="theme_tab">
                <form name="give-access-form">
                    <input type="hidden" name="pkCat" value="{{$user['user_id']}}">
                    <div class="row" id="access_priviledges">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-md-3 col-lg-3">
                                    <div class="profile_box">
                                        <div class="profile_box">
                                              <div class="profile_pic">
                                                  <img id="img_tmp" src="@if(!empty($user['user_photo'])) {{url('public/storage/'.Config::get('siteglobal.images_dirs.USERS') )}}/{{$user['user_photo']}} @else {{ url('public/images/user.png') }}@endif">
                                                  <input type="hidden" id="img_tmp" value="{{ url('public/images/user.png') }}">
                                              </div>
                                              <div class="edit_pencile">
                                                <img src="{{ url('public/images/ic_pen.png') }}">
                                                <input type="file" id="upload_profile" name="user_img" accept="image/jpeg,image/png">
                                              </div>
                                          </div>
                                    </div>
                                </div>
                                <div class="col-md-9 col-lg-9">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6">
                                            <h6 class="profile_inner_title">Name:</h6>
                                            <h6 class="acc_pri_user_data">{{$user['name'] ?? ''}}</h6>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <h6 class="profile_inner_title">Phone:</h6>
                                            <h6 class="acc_pri_user_data">{{$user['mobile_number'] ?? ''}}</h6>
                                        </div>
                                        <div class="col-md-6 col-lg-6 pt-4">
                                            <h6 class="profile_inner_title">Email:</h6>
                                            <h6 class="acc_pri_user_data">{{$user['email'] ?? ''}}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-grey">
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <strong>1. Question Bank Live Test</strong>
                                        <input type="hidden" name="module[0]" value="Question_Bank_Live_Test">
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="view[0]" value="0">
                                            <input type="checkbox" name="view[0]" value="1" class="form-check-input acc_pri_view" id="" @if(!empty($accessPriData) && $accessPriData['Question_Bank_Live_Test']->view==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>View</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="add[0]" value="0">
                                            <input type="checkbox" name="add[0]" value="1" class="form-check-input active_disabled acc_pri_other" id=""  @if(!empty($accessPriData) && $accessPriData['Question_Bank_Live_Test']->add==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Add</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="edit[0]" value="0">
                                            <input type="checkbox" name="edit[0]" value="1" class="form-check-input active_disabled acc_pri_other" id=""  @if(!empty($accessPriData) && $accessPriData['Question_Bank_Live_Test']->edit==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Edit</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="delete[0]" value="0">
                                            <input type="checkbox" name="delete[0]" value="1" class="form-check-input active_disabled acc_pri_other" id="" @if(!empty($accessPriData) && $accessPriData['Question_Bank_Live_Test']->delete==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Delete</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="status[0]" value="0">
                                            <input type="checkbox" name="status[0]" value="1" class="form-check-input active_disabled acc_pri_other" id="" @if(!empty($accessPriData) && $accessPriData['Question_Bank_Live_Test']->status==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Status</strong></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <h6>Select Category </h6>
                                    </div>
                                    @foreach($categories as $category)
                                        <div class="col-lg-2 col-md-6">
                                            <div class="form-group form-check">
                                                <input type="checkbox" name="categories[0][]" value="{{$category['category_id']}}" class="form-check-input " id=""  
                                                    @if(!empty($accessPriData) && $accessPriData['Question_Bank_Live_Test']->categories)
                                                    @foreach($accessPriData['Question_Bank_Live_Test']->categories as $cat)
                                                        @if($category['category_id']==$cat)
                                                            checked  
                                                        @endif
                                                    @endforeach
                                                    @endif>
                                                <label class="custom_checkbox"></label>
                                                <label class="form-check-label label-text" ><strong>{{ $category['desc']['name'] ?? '' }}</strong></label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="card-grey">
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <strong>2. Live Test</strong>
                                        <input type="hidden" name="module[1]" value="Live_Test">
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="view[1]" value="0">
                                            <input type="checkbox" name="view[1]" value="1" class="form-check-input acc_pri_view" id="" @if(!empty($accessPriData) && $accessPriData['Live_Test']->view==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>View</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="add[1]" value="0">
                                            <input type="checkbox" name="add[1]" value="1" class="form-check-input active_disabled acc_pri_other" id=""  @if(!empty($accessPriData) && $accessPriData['Live_Test']->add==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Add</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="edit[1]" value="0">
                                            <input type="checkbox" name="edit[1]" value="1" class="form-check-input active_disabled acc_pri_other" id=""  @if(!empty($accessPriData) && $accessPriData['Live_Test']->edit==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Edit</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="delete[1]" value="0">
                                            <input type="checkbox" name="delete[1]" value="1" class="form-check-input active_disabled acc_pri_other" id=""  @if(!empty($accessPriData) && $accessPriData['Live_Test']->delete==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Delete</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="status[1]" value="0">
                                            <input type="checkbox" name="status[1]" value="1" class="form-check-input active_disabled acc_pri_other" id=""  @if(!empty($accessPriData) && $accessPriData['Live_Test']->status==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Status</strong></label>
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="students[1]" value="0">
                                            <input type="checkbox" name="students[1]" value="1" class="form-check-input active_disabled acc_pri_other" id=""  @if(!empty($accessPriData) && $accessPriData['Live_Test']->students==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Students</strong></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <h6>Select Category </h6>
                                    </div>
                                    @foreach($categories as $category)
                                        <div class="col-lg-2 col-md-6">
                                            <div class="form-group form-check">
                                                <input type="checkbox" name="categories[1][]" value="{{$category['category_id']}}" class="form-check-input " id="" 
                                                    @if(!empty($accessPriData) && $accessPriData['Live_Test']->categories)
                                                    @foreach($accessPriData['Live_Test']->categories as $cat)
                                                        @if($category['category_id']==$cat)
                                                            checked  
                                                        @endif
                                                    @endforeach
                                                    @endif
                                                    >
                                                <label class="custom_checkbox"></label>
                                                <label class="form-check-label label-text" ><strong>{{ $category['desc']['name'] ?? '' }}</strong></label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="card-grey">
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <strong>3. Question Bank Quizz Test</strong>
                                        <input type="hidden" name="module[2]" value="Question_Bank_Quizz_Test">
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="view[2]" value="0">
                                            <input type="checkbox" name="view[2]" value="1" class="form-check-input acc_pri_view" id=""  @if(!empty($accessPriData) && $accessPriData['Question_Bank_Quizz_Test']->view==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>View</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="add[2]" value="0">
                                            <input type="checkbox" name="add[2]" value="1" class="form-check-input active_disabled acc_pri_other" id=""  @if(!empty($accessPriData) && $accessPriData['Question_Bank_Quizz_Test']->add==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Add</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="edit[2]" value="0">
                                            <input type="checkbox" name="edit[2]" value="1" class="form-check-input active_disabled acc_pri_other" id=""  @if(!empty($accessPriData) && $accessPriData['Question_Bank_Quizz_Test']->edit==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Edit</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="delete[2]" value="0">
                                            <input type="checkbox" name="delete[2]" value="1" class="form-check-input active_disabled acc_pri_other" id=""  @if(!empty($accessPriData) && $accessPriData['Question_Bank_Quizz_Test']->delete==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Delete</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="status[2]" value="0">
                                            <input type="checkbox" name="status[2]" value="1" class="form-check-input active_disabled acc_pri_other" id=""  @if(!empty($accessPriData) && $accessPriData['Question_Bank_Quizz_Test']->status==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Status</strong></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <h6>Select Category </h6>
                                    </div>
                                    @foreach($categories as $category)
                                        <div class="col-lg-2 col-md-6">
                                            <div class="form-group form-check">
                                                <input type="checkbox" name="categories[2][]" value="{{$category['category_id']}}" class="form-check-input " id="" 
                                                    @if(!empty($accessPriData) && $accessPriData['Question_Bank_Quizz_Test']->categories)
                                                    @foreach($accessPriData['Question_Bank_Quizz_Test']->categories as $cat)
                                                        @if($category['category_id']==$cat)
                                                            checked  
                                                        @endif
                                                    @endforeach
                                                    @endif>
                                                <label class="custom_checkbox"></label>
                                                <label class="form-check-label label-text" ><strong>{{ $category['desc']['name'] ?? '' }}</strong></label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="card-grey">
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <strong>4. Quizz Test</strong>
                                        <input type="hidden" name="module[3]" value="Quizz_Test">
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="view[3]" value="0">
                                            <input type="checkbox" name="view[3]" value="1" class="form-check-input acc_pri_view" id="" @if(!empty($accessPriData) && $accessPriData['Quizz_Test']->view==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>View</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="add[3]" value="0">
                                            <input type="checkbox" name="add[3]" value="1" class="form-check-input active_disabled acc_pri_other" id="" @if(!empty($accessPriData) && $accessPriData['Quizz_Test']->add==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Add</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="edit[3]" value="0">
                                            <input type="checkbox" name="edit[3]" value="1" class="form-check-input active_disabled acc_pri_other" id="" @if(!empty($accessPriData) && $accessPriData['Quizz_Test']->edit==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Edit</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="delete[3]" value="0">
                                            <input type="checkbox" name="delete[3]" value="1" class="form-check-input active_disabled acc_pri_other" id="" @if(!empty($accessPriData) && $accessPriData['Quizz_Test']->delete==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Delete</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="status[3]" value="0">
                                            <input type="checkbox" name="status[3]" value="1" class="form-check-input active_disabled acc_pri_other" id="" @if(!empty($accessPriData) && $accessPriData['Quizz_Test']->status==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Status</strong></label>
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="students[3]" value="0">
                                            <input type="checkbox" name="students[3]" value="1" class="form-check-inpu active_disabled acc_pri_other" id="" @if(!empty($accessPriData) && $accessPriData['Quizz_Test']->students==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Students</strong></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <h6>Select Category </h6>
                                    </div>
                                    @foreach($categories as $category)
                                        <div class="col-lg-2 col-md-6">
                                            <div class="form-group form-check">
                                                <input type="checkbox" name="categories[3][]" value="{{$category['category_id']}}" class="form-check-input " id="" 
                                                    @if(!empty($accessPriData) && $accessPriData['Quizz_Test']->categories)
                                                    @foreach($accessPriData['Quizz_Test']->categories as $cat)
                                                        @if($category['category_id']==$cat)
                                                            checked  
                                                        @endif
                                                    @endforeach
                                                    @endif
                                                    >
                                                <label class="custom_checkbox"></label>
                                                <label class="form-check-label label-text" ><strong>{{ $category['desc']['name'] ?? '' }}</strong></label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="card-grey">
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <strong>5. Question Bank Practice Test</strong>
                                        <input type="hidden" name="module[4]" value="Question_Bank_Practice_Test">
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="view[4]" value="0">
                                            <input type="checkbox" name="view[4]" value="1" class="form-check-input acc_pri_view" id="" @if(!empty($accessPriData) && $accessPriData['Question_Bank_Practice_Test']->view==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>View</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="add[4]" value="0">
                                            <input type="checkbox" name="add[4]" value="1" class="form-check-input active_disabled acc_pri_other" id=""  @if(!empty($accessPriData) && $accessPriData['Question_Bank_Practice_Test']->add==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Add</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="edit[4]" value="0">
                                            <input type="checkbox" name="edit[4]" value="1" class="form-check-input active_disabled acc_pri_other" id=""  @if(!empty($accessPriData) && $accessPriData['Question_Bank_Practice_Test']->edit==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Edit</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="delete[4]" value="0">
                                            <input type="checkbox" name="delete[4]" value="1" class="form-check-input active_disabled acc_pri_other" id=""  @if(!empty($accessPriData) && $accessPriData['Question_Bank_Practice_Test']->delete==true) checked @endif> 
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Delete</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="status[4]" value="0">
                                            <input type="checkbox" name="status[4]" value="1" class="form-check-input active_disabled acc_pri_other" id=""  @if(!empty($accessPriData) && $accessPriData['Question_Bank_Practice_Test']->status==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Status</strong></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <h6>Select Category </h6>
                                    </div>
                                    @foreach($categories as $category)
                                        <div class="col-lg-2 col-md-6">
                                            <div class="form-group form-check">
                                                <input type="checkbox" name="categories[4][]" value="{{$category['category_id']}}" class="form-check-input " id="" 
                                                    @if(!empty($accessPriData) && $accessPriData['Question_Bank_Practice_Test']->categories)
                                                    @foreach($accessPriData['Question_Bank_Practice_Test']->categories as $cat)
                                                        @if($category['category_id']==$cat)
                                                            checked  
                                                        @endif
                                                    @endforeach
                                                    @endif
                                                    >
                                                <label class="custom_checkbox"></label>
                                                <label class="form-check-label label-text" ><strong>{{ $category['desc']['name'] ?? '' }}</strong></label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="card-grey">
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <strong>6. Practice Test</strong>
                                        <input type="hidden" name="module[5]" value="Practice_Test">
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="view[5]" value="0">
                                            <input type="checkbox" name="view[5]" value="1" class="form-check-input acc_pri_view" id="" @if(!empty($accessPriData) && $accessPriData['Practice_Test']->view==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>View</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="add[5]" value="0">
                                            <input type="checkbox" name="add[5]" value="1" class="form-check-input active_disabled acc_pri_other" id=""  @if(!empty($accessPriData) && $accessPriData['Practice_Test']->add==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Add</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="edit[5]" value="0">
                                            <input type="checkbox" name="edit[5]" value="1" class="form-check-input active_disabled acc_pri_other" id="" @if(!empty($accessPriData) && $accessPriData['Practice_Test']->edit==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Edit</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="delete[5]" value="0">
                                            <input type="checkbox" name="delete[5]" value="1" class="form-check-input active_disabled acc_pri_other" id=""  @if(!empty($accessPriData) && $accessPriData['Practice_Test']->delete==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Delete</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="status[5]" value="0">
                                            <input type="checkbox" name="status[5]" value="1" class="form-check-input active_disabled acc_pri_other" id=""  @if(!empty($accessPriData) && $accessPriData['Practice_Test']->status==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Status</strong></label>
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="students[5]" value="0">
                                            <input type="checkbox" name="students[5]" value="1" class="form-check-inpu active_disabled acc_pri_other" id=""  @if(!empty($accessPriData) && $accessPriData['Practice_Test']->students==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Students</strong></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <h6>Select Category </h6>
                                    </div>
                                    @foreach($categories as $category)
                                        <div class="col-lg-2 col-md-6">
                                            <div class="form-group form-check">
                                                <input type="checkbox" name="categories[5][]" value="{{$category['category_id']}}" class="form-check-input " id="" 
                                                    @if(!empty($accessPriData) && $accessPriData['Practice_Test']->categories)
                                                    @foreach($accessPriData['Practice_Test']->categories as $cat)
                                                        @if($category['category_id']==$cat)
                                                            checked  
                                                        @endif
                                                    @endforeach
                                                    @endif>

                                                <label class="custom_checkbox"></label>
                                                <label class="form-check-label label-text" ><strong>{{ $category['desc']['name'] ?? '' }}</strong></label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="card-grey">
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <strong>7. Question Bank GK CA Test</strong>
                                        <input type="hidden" name="module[6]" value="Question_Bank_GK_CA_Test">
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="view[6]" value="0">
                                            <input type="checkbox" name="view[6]" value="1" class="form-check-input acc_pri_view" id="" @if(!empty($accessPriData) && $accessPriData['Question_Bank_GK_CA_Test']->view==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>View</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="add[6]" value="0">
                                            <input type="checkbox" name="add[6]" value="1" class="form-check-input active_disabled acc_pri_other" id="" @if(!empty($accessPriData) && $accessPriData['Question_Bank_GK_CA_Test']->add==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Add</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="edit[6]" value="0">
                                            <input type="checkbox" name="edit[6]" value="1" class="form-check-input active_disabled acc_pri_other" id=""  @if(!empty($accessPriData) && $accessPriData['Question_Bank_GK_CA_Test']->edit==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Edit</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="delete[6]" value="0">
                                            <input type="checkbox" name="delete[6]" value="1" class="form-check-input active_disabled acc_pri_other" id="" @if(!empty($accessPriData) && $accessPriData['Question_Bank_GK_CA_Test']->delete==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Delete</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="status[6]" value="0">
                                            <input type="checkbox" name="status[6]" value="1" class="form-check-input active_disabled acc_pri_other" id="" @if(!empty($accessPriData) && $accessPriData['Question_Bank_GK_CA_Test']->status==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Status</strong></label>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="card-grey">
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <strong>8. GK CA Quizz</strong>
                                        <input type="hidden" name="module[7]" value="GK_CA_Quizz">
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="view[7]" value="0">
                                            <input type="checkbox" name="view[7]" value="1" class="form-check-input acc_pri_view" id="" @if(!empty($accessPriData) && $accessPriData['GK_CA_Quizz']->view==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>View</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="add[7]" value="0">
                                            <input type="checkbox" name="add[7]" value="1" class="form-check-input active_disabled acc_pri_other" id="" @if(!empty($accessPriData) && $accessPriData['GK_CA_Quizz']->add==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Add</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="edit[7]" value="0">
                                            <input type="checkbox" name="edit[7]" value="1" class="form-check-input active_disabled acc_pri_other" id="" @if(!empty($accessPriData) && $accessPriData['GK_CA_Quizz']->edit==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Edit</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="delete[7]" value="0">
                                            <input type="checkbox" name="delete[7]" value="1" class="form-check-input active_disabled acc_pri_other" id="" @if(!empty($accessPriData) && $accessPriData['GK_CA_Quizz']->delete==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Delete</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="status[7]" value="0">
                                            <input type="checkbox" name="status[7]" value="1" class="form-check-input active_disabled acc_pri_other" id="" @if(!empty($accessPriData) && $accessPriData['GK_CA_Quizz']->status==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Status</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="students[7]" value="0">
                                            <input type="checkbox" name="students[7]" value="1" class="form-check-inpu active_disabled acc_pri_other" id="" @if(!empty($accessPriData) && $accessPriData['GK_CA_Quizz']->students==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Students</strong></label>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="card-grey">
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <strong>9. Article News</strong>
                                        <input type="hidden" name="module[8]" value="Article_News">
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="view[8]" value="0">
                                            <input type="checkbox" name="view[8]" value="1" class="form-check-input acc_pri_view" id="" @if(!empty($accessPriData) && $accessPriData['Article_News']->view==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>View</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="add[8]" value="0">
                                            <input type="checkbox" name="add[8]" value="1" class="form-check-input active_disabled acc_pri_other" id="" @if(!empty($accessPriData) && $accessPriData['Article_News']->add==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Add</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="edit[8]" value="0">
                                            <input type="checkbox" name="edit[8]" value="1" class="form-check-input active_disabled acc_pri_other" id="" @if(!empty($accessPriData) && $accessPriData['Article_News']->edit==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Edit</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="delete[8]" value="0">
                                            <input type="checkbox" name="delete[8]" value="1" class="form-check-input active_disabled acc_pri_other" id="" @if(!empty($accessPriData) && $accessPriData['Article_News']->delete==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Delete</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="status[8]" value="0">
                                            <input type="checkbox" name="status[8]" value="1" class="form-check-input active_disabled acc_pri_other" id="" @if(!empty($accessPriData) && $accessPriData['Article_News']->status==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Status</strong></label>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="card-grey">
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <strong>10. Blog Categories</strong>
                                        <input type="hidden" name="module[9]" value="Blog_Categories">
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="view[9]" value="0">
                                            <input type="checkbox" name="view[9]" value="1" class="form-check-input acc_pri_view" id="" @if(!empty($accessPriData) && $accessPriData['Blog_Categories']->view==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>View</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="add[9]" value="0">
                                            <input type="checkbox" name="add[9]" value="1" class="form-check-input active_disabled acc_pri_other" id="" @if(!empty($accessPriData) && $accessPriData['Blog_Categories']->add==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Add</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="edit[9]" value="0">
                                            <input type="checkbox" name="edit[9]" value="1" class="form-check-input active_disabled acc_pri_other" id="" @if(!empty($accessPriData) && $accessPriData['Blog_Categories']->edit==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Edit</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="delete[9]" value="0">
                                            <input type="checkbox" name="delete[9]" value="1" class="form-check-input active_disabled acc_pri_other" id="" @if(!empty($accessPriData) && $accessPriData['Blog_Categories']->delete==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Delete</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="status[9]" value="0">
                                            <input type="checkbox" name="status[9]" value="1" class="form-check-input active_disabled acc_pri_other" id=""  @if(!empty($accessPriData) && $accessPriData['Blog_Categories']->status==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Status</strong></label>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="card-grey">
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <strong>11. Blog_Post</strong>
                                        <input type="hidden" name="module[10]" value="Blog_Post">
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="view[10]" value="0">
                                            <input type="checkbox" name="view[10]" value="1" class="form-check-input acc_pri_view" id="" @if(!empty($accessPriData) && $accessPriData['Blog_Post']->view==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>View</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="add[10]" value="0">
                                            <input type="checkbox" name="add[10]" value="1" class="form-check-input active_disabled acc_pri_other" id="" @if(!empty($accessPriData) && $accessPriData['Blog_Post']->add==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Add</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="edit[10]" value="0">
                                            <input type="checkbox" name="edit[10]" value="1" class="form-check-input active_disabled acc_pri_other" id="" @if(!empty($accessPriData) && $accessPriData['Blog_Post']->edit==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Edit</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="delete[10]" value="0">
                                            <input type="checkbox" name="delete[10]" value="1" class="form-check-input active_disabled acc_pri_other" id="" @if(!empty($accessPriData) && $accessPriData['Blog_Post']->delete==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Delete</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="status[10]" value="0">
                                            <input type="checkbox" name="status[10]" value="1" class="form-check-input active_disabled acc_pri_other" id="" @if(!empty($accessPriData) && $accessPriData['Blog_Post']->status==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Status</strong></label>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="card-grey">
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <strong>12. Doubts</strong>
                                        <input type="hidden" name="module[11]" value="Doubts">
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="view[11]" value="0">
                                            <input type="checkbox" name="view[11]" value="1" class="form-check-input acc_pri_view" id="" @if(!empty($accessPriData) && $accessPriData['Doubts']->view==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>View</strong></label>
                                        </div>
                                    </div>
                                    <input type="hidden" name="add[11]" value="0">
                                    <input type="hidden" name="edit[11]" value="0">
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="answer[11]" value="0">
                                            <input type="checkbox" name="answer[11]" value="1" class="form-check-input active_disabled acc_pri_other" id=""  @if(!empty($accessPriData) && $accessPriData['Doubts']->answer==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Answer</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="delete[11]" value="0">
                                            <input type="checkbox" name="delete[11]" value="1" class="form-check-input active_disabled acc_pri_other" id="" @if(!empty($accessPriData) && $accessPriData['Doubts']->delete==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Delete</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group form-check">
                                            <input type="hidden" name="status[11]" value="0">
                                            <input type="checkbox" name="status[11]" value="1" class="form-check-input active_disabled acc_pri_other" id="" @if(!empty($accessPriData) && $accessPriData['Doubts']->status==true) checked @endif>
                                            <label class="custom_checkbox"></label>
                                            <label class="form-check-label label-text" ><strong>Status</strong></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="text-center modal_btn pt-3">
                                        <a class="theme_btn red_btn ajax_request no_sidebar_active" data-slug="admin/subAdmin" href="{{ url('admin/subAdmin') }}">{{'Cancel'}}</a>
                                        <button type="submit" class="theme_btn">{{'Give Access'}}</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('custom-styles')

@endpush
@push('custom-scripts')
<script type="text/javascript">
    $(function() {
      showLoader(false);
    });
</script>

<script type="text/javascript" src="{{ url('public/js/dashboard/sub_admins.js') }}"></script>
@endpush