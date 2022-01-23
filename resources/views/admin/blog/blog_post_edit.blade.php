@extends('layout.app_with_login')
@section('title','Edit Blog')
@section('script', url('public/js/dashboard/blog.js'))
@section('content')	
<!-- Page Content  -->
<div class="section">
  <div class="container-fluid">
    <h2 class="title"><span>{{'Master'}}</span> > <a  class="ajax_request" data-slug="admin/blog" href="{{url('/admin/blog')}}"><span>{{'Blog'}}</span></a>  >  {{'Edit'}}</h2>
        <div class="white_box">
            <div class="theme_tab">
                    
                <form name="add-blog-form">
                    <input type="hidden" name="pkCat" id="pkCat" value="{{$data->blog_post_id}}">
                    <div class="row">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-6">
                            <div class="text-center">
                                <div class="profile_box">
                                    <div class="square_pic">
                                        <img id="cat_img" src="@if(!empty($data->blog_image)) {{ url('public/storage/'.Config::get('siteglobal.images_dirs.BLOG').'/'.$data->blog_image) }} @else{{ url('public/images/user.png') }} @endif">
                                        <input type="hidden" id="img_tmp" value="{{ url('public/images/user.png') }}">
                                    </div>
                                    <div  class="upload_pic_link">
                                        <a href="javascript:void(0)">
                                        {{'Upload Blog Image'}}<input type="file" id="upload_profile" name="blog_img" accept="image/jpeg,image/png"></a>
                                    </div>
                                </div>
                            </div>
                            @if(!empty($data->blog_post_desc) && $data->blog_post_desc!=null)
                                @foreach($data->blog_post_desc as $value)
                                <div class="form-group">
                                    <label>{{'Title In'}} @if($value->lang_code=='en') {{ 'English' }} @else {{ 'Hindi' }} @endif</label>
                                    <input type="text" name="name_{{$value->lang_code}}" id="name_{{$value->lang_code}}" class="form-control icon_control" value="{{$value->blog_post_title}}">
                                </div>
                                @endforeach
                            @endif
{{--                             <div class="form-group">
                                <label>{{'Title In Hindi'}}</label>
                                <input type="text" name="name_hi" id="name_hi" class="form-control icon_control" value="">
                            </div> --}}
                            <div class="form-group">
                                <label>{{'Select Category'}}</label>
                                <select class="form-control icon_control dropdown_control" name="category" id="category">
                                    <option value="">{{'Select'}}</option>
                                    @foreach($category as $k =>$v)
                                        <option value="{{$v->blog_category_id}}" @if(!empty($data->blog_category_id) && $data->blog_category_id ==$v->blog_category_id ) {{$v->blog_category_id}} {{'selected'}} @endif>{{$v->blog_category_title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            @if(!empty($data->blog_post_desc) && $data->blog_post_desc!=null)
                                @foreach($data->blog_post_desc as $value)
                                    <div class="form-group">
                                        <label>{{'Body In'}} @if($value->lang_code=='en') {{ 'English' }} @else {{ 'Hindi' }} @endif</label>
                                        <textarea class="form-control icon_control" name="desc_{{$value->lang_code}}" id="desc_{{$value->lang_code}}" rows="3">{{$value->description}}</textarea>
                                    </div>
                                @endforeach
                            @endif
                            {{-- <div class="form-group">
                                <label>{{'Body In Hindi'}}</label>
                                <textarea class="form-control icon_control" name="desc_hi" id="desc_hi" rows="3"></textarea>
                            </div> --}}
                            <div class="form-group">
                                <label>{{'SEO Meta Title'}}</label>
                                <input type="text" name="seo_meta_title" id="seo_meta_title" class="form-control icon_control" value="{{$data->seo_meta_title}}">
                            </div>
                            <div class="form-group">
                                <label>{{'SEO Meta Description'}}</label>
                                <textarea class="form-control icon_control" name="seo_meta_description" id="seo_meta_description" rows="3">{{$data->seo_meta_description}}</textarea>
                            </div>

                            <div class="text-center modal_btn ">
                                <button type="submit" class="theme_btn">{{$translations['gn_submit'] ?? 'Submit'}}</button>
                                <a class="theme_btn red_btn ajax_request no_sidebar_active" data-slug="admin/blog" href="{{ url('admin/blog') }}">{{$translations['gn_cancel'] ?? 'Cancel'}}</a>
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
<script src="{{ url('public/bower_components/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
    CKEDITOR.replace('desc_en',{
        filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });
    CKEDITOR.replace('desc_hi',{
        filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });

</script>
<script type="text/javascript" src="{{ url('public/js/dashboard/blog.js') }}"></script>
@endpush