@extends('layout.app_with_login')
@section('title','Blogs')
@section('script', url('public/js/dashboard/blog.js'))
@section('content')	
<!-- Page Content  -->
<div class="section">
	<div class="container-fluid">
		<div class="row ">
            <div class="col-12 mb-3">
    			<h2 class="title"><span>{{'Blog'}}</span> > {{'Blogs'}}</h2>
            </div>
            <div class="col-md-4 mb-3">
                <input type="text" id="search_blog" class="form-control without_border icon_control search_control" placeholder="{{'Search'}}">
            </div>  
            <div class="col-md-3 text-md-right mb-3">
                <div class="row">
                    <div class="col-3 m-auto">
                        <label class="blue_label">Category</label>
                    </div>
                    <div class="col-9">
                        <select class="form-control without_border icon_control dropdown_control" id="category_type">
                            <option value="" selected>Select</option>
                            @foreach($category as $k =>$v)
                                <option value="{{$v->blog_category_id}}">{{$v->blog_category_title}}</option>
                            @endforeach
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
                @php
                $accessPriData = !empty(session()->get('accessPriData')) ? session()->get('accessPriData') :'' ;
                @endphp
                @if(!empty($accessPriData['Blog_Post']) && $accessPriData['Blog_Post']->add==true || Auth::user()->user_type==0)
                <a href="{{url('/admin/addBlog')}}"><button class="theme_btn show_modal full_width small_btn">{{'Add New'}}</button></a>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="theme_table">
                    <div class="table-responsive">
                        <table id="blog_listing" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>{{'Sr. No.'}}</th>
                                    <th>{{'Title Hi'}}</th>
                                    <th>{{'Title En'}}</th>
                                    <th>{{'Category'}}</th>
                                    <th>{{'Publish Date '}}</th>
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
{{-- <div class="theme_modal modal fade" id="add_new" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <img src="{{url('public/images/ic_close_bg.png')}}" class="modal_top_bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <img src="{{url('public/images/ic_close_circle_white.png')}}">
                </button>
                <form name="add-blog-form" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-1"></div>
                        <div class="col-lg-10">
                            <h5 class="modal-title" id="exampleModalCenterTitle">{{'Blog'}}</h5>
                            <input type="hidden" name="pkCat" id="pkCat">

                            <div class="text-center">
                                <div class="profile_box">
                                    <div class="profile_pic">
                                        <img id="cat_img" src="{{ url('public/images/user.png') }}">
                                        <input type="hidden" id="img_tmp" value="{{ url('public/images/user.png') }}">
                                    </div>
                                    <div  class="upload_pic_link">
                                        <a href="javascript:void(0)">
                                        {{'Upload Blog Image'}}<input type="file" id="upload_profile" name="blog_img" accept="image/jpeg,image/png"></a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>{{'Title In English'}}</label>
                                <input type="text" name="name_en" id="name_en" class="form-control icon_control">
                            </div>
                            <div class="form-group">
                                <label>{{'Title In Hindi'}}</label>
                                <input type="text" name="name_hi" id="name_hi" class="form-control icon_control">
                            </div>
                            <div class="form-group">
                                <label>{{'Select Category'}}</label>
                                <select class="form-control icon_control dropdown_control" name="category" id="category">
                                    <option value="">{{'Select'}}</option>
                                    @foreach($category as $k =>$v)
                                        <option value="{{$v->blog_category_id}}">{{$v->blog_category_title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>{{'Body In English'}}</label>
                                <textarea class="form-control icon_control" name="desc_en" id="desc_en" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label>{{'Body In Hindi'}}</label>
                                <textarea class="form-control icon_control" name="desc_hi" id="desc_hi" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label>{{'SEO Meta Title'}}</label>
                                <input type="text" name="seo_meta_title" id="seo_meta_title" class="form-control icon_control">
                            </div>
                            <div class="form-group">
                                <label>{{'SEO Meta Description'}}</label>
                                <textarea class="form-control icon_control" name="seo_meta_description" id="seo_meta_description" rows="3"></textarea>
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
 --}}

<!-- view  Popup -->
{{-- <div class="theme_modal modal fade" id="show_view_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                            <h5 class="modal-title" id="exampleModalCenterTitle">{{'Blog'}}</h5>
                            <input type="hidden" name="pkCat" id="pkCat">

                            <div class="text-center">
                                <div class="profile_box">
                                    <div class="profile_pic">
                                        <img id="view_cat_img" src="{{ url('public/images/user.png') }}">
                                        <input type="hidden" id="view_img_tmp" value="{{ url('public/images/user.png') }}">
                                    </div>
                                    <div  class="upload_pic_link">
                                        <a href="javascript:void(0)">
                                        {{'Blog Image'}}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>{{'Title In English'}}</label>
                                <input type="text" id="view_name_en" class="form-control icon_control" disabled>
                            </div>
                            <div class="form-group">
                                <label>{{'Title In Hindi'}}</label>
                                <input type="text" id="view_name_hi" class="form-control icon_control" disabled>
                            </div>
                            <div class="form-group">
                                <label>{{'Select Category'}}</label>
                                <select class="form-control icon_control dropdown_control" id="view_category" disabled>
                                    <option value="">{{'Select'}}</option>
                                    @foreach($category as $k =>$v)
                                        <option value="{{$v->blog_category_id}}">{{$v->blog_category_title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>{{'Body In English'}}</label>
                                <textarea class="form-control icon_control" id="view_desc_en" rows="3" disabled></textarea>
                            </div>
                            <div class="form-group">
                                <label>{{'Body In Hindi'}}</label>
                                <textarea class="form-control icon_control" id="view_desc_hi" rows="3" disabled></textarea>
                            </div>
                            <div class="form-group">
                                <label>{{'SEO Meta Title'}}</label>
                                <input type="text" id="view_seo_meta_title" class="form-control icon_control" disabled>
                            </div>
                            <div class="form-group">
                                <label>{{'SEO Meta Description'}}</label>
                                <textarea class="form-control icon_control" id="view_seo_meta_description" rows="3" disabled></textarea>
                            </div>
                            <div class="form-group">
                                <label>{{'Status'}}</label>
                                <input type="text" id="view_status" class="form-control icon_control" disabled>
                            </div>

                        </div>
                        <div class="col-lg-1"></div>
                    </div>
            </div>
        </div>
    </div>
</div>
 --}}
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
<script src="{{ url('public/bower_components/ckeditor/ckeditor.js') }}"></script>

<script type="text/javascript" src="{{ url('public/js/dashboard/blog.js') }}"></script>
@endpush