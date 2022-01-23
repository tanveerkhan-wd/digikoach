@extends('layout.app_with_login')
@section('title','Category')
@section('script', url('public/js/dashboard/category.js'))
@section('content')	
<!-- Page Content  -->
<div class="section">
    <input type="hidden" id="img_base_name" value="{{Config::get('siteglobal.images_dirs.CATEGORY_ICON')}}">
	<div class="container-fluid">
		<div class="row ">
            <div class="col-12 mb-3">
    			<h2 class="title">{{'Category'}}</h2>
            </div>
            <div class="col-md-4 mb-3">
                <input type="text" id="search_email_template" class="form-control without_border icon_control search_control" placeholder="{{'Search'}}">
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
                <a><button class="theme_btn show_modal full_width small_btn" data-toggle="modal" data-target="#add_new">{{'Add New'}}</button></a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="theme_table">
                    <div class="table-responsive">
                        <table id="email_template_listing" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>{{'Sr. No.'}}</th>
                                    <th>{{'Icon'}}</th>
                                    <th>{{'Name Hi'}}</th>
                                    <th>{{'Name En'}}</th>
                                    <th>{{'Parent Category Hi'}}</th>
                                    <th>{{'Parent Category En'}}</th>
                                    <th>{{'GK & CA'}}</th>
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
                            <h5 class="modal-title" id="exampleModalCenterTitle">{{'Category'}}</h5>
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
                                <label>{{'Is It a Parent Category'}}</label>
                                <div class="form-group">
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="radio" class="form-check-input is_parent_yes" name="is_parent" value="1">Yes
                                      </label>
                                    </div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="radio" class="form-check-input is_parent_no" value="0" name="is_parent">No
                                      </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group select2-container hide_content" id="show_parent_category">
                                <label>{{'Select Parent Category'}}</label>
                                <select class="form-control icon_control select2" name="parent_category" id="parent_category">
                                    <option value="">{{'Select'}}</option>
                                    @foreach ($category as $key => $value)
                                        <option value="{{$value['category_id']}}">{{$value['category_desc'][0]['name']}}</option>
                                        
                                        @if(!empty($value['children']))
                                        @foreach ($value['children'] as $key1 => $value1) 
                                            <option value="{{$value1['category_id']}}">&emsp;&emsp;{{$value1['category_desc'][0]['name']}}</option>

                                            @if(!empty($value1['children']))
                                            @foreach ($value1['children'] as $key2 => $value2) 
                                                <option value="{{$value2['category_id']}}">&emsp;&emsp;&emsp;&emsp;{{$value2['category_desc'][0]['name']}}</option>

                                                @if(!empty($value2['children']))
                                                @foreach ($value2['children'] as $key3 => $value3)
                                                    <option value="{{$value3['category_id']}}">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;{{$value3['category_desc'][0]['name']}}</option>
                                                    
                                                    @if(!empty($value3['children']))
                                                    @foreach ($value3['children'] as $key4 => $value4)
                                                        <option value="{{$value4['category_id']}}">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;{{$value4['category_desc'][0]['name']}}</option>
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
                                <br><br>
                            </div>
                            
                            <div class="form-group gk_ca_box">
                                <label>{{'GK & CA'}}</label>
                                <label class="switch">
                                  <input type="checkbox" name="gk_ca" id="gk_ca">
                                  <span class="slider round"></span>
                                </label>
                            </div>

                            <div class="text-center">
                                <div class="profile_box">
                                    <div class="profile_pic">
                                        <img id="cat_img" src="{{ url('public/images/user.png') }}">
                                        <input type="hidden" id="img_tmp" value="{{ url('public/images/user.png') }}">
                                    </div>
                                    <div  class="upload_pic_link">
                                        <a href="javascript:void(0)">
                                        {{'Upload Icon'}}<input type="file" id="upload_profile" name="icon" accept="image/jpeg,image/png"></a>
                                    </div>
                                </div>
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
<script type="text/javascript" src="{{ url('public/js/dashboard/category.js') }}"></script>
@endpush