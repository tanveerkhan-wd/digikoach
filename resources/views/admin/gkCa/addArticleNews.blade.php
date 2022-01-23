@extends('layout.app_with_login')
@section('title','Add New Article & News ')
@section('script', url('public/js/dashboard/article_news.js'))
@section('content')	
<!-- Page Content  -->
<div class="section">
    <div class="container-fluid">
        <h2 class="title"> <span>{{' Gk & Ca '}}</span> > <a  class="ajax_request" data-slug="admin/gkCa/articleNews" href="{{url('/admin/gkCa/articleNews')}}"><span>{{' Article & News '}}</span></a>  >  {{'Add New'}}
        </h2>
        <div class="white_box">
            <div class="theme_tab">
                    
                <form name="add-article-form">
                    <div class="row">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-6">

                            <div class="form-group">
                                <label>{{' Title In English'}}</label>
                                <input type="text" name="title_en" id="title_en" class="form-control icon_control">
                            </div>

                            <div class="form-group">
                                <label>{{' Title In Hindi'}}</label>
                                <input type="text" name="title_hi" id="title_hi" class="form-control icon_control">
                            </div>

                            <div class="form-group">
                                <label>{{' Body In English'}}</label>
                                <textarea name="body_en" id="body_en" class="form-control icon_control"></textarea>
                            </div>

                            <div class="form-group">
                                <label>{{' Body In Hindi'}}</label>
                                <textarea name="body_hi" id="body_hi" class="form-control icon_control"></textarea>
                            </div>

                            <div class="form-group">
                                    <label>{{'SEO Meta Title'}}</label>
                                    <input type="text" name="seo_meta_title" id="seo_meta_title" class="form-control icon_control" value="">
                                </div>
                            <div class="form-group">
                                <label>{{'SEO Meta Description'}}</label>
                                <textarea class="form-control icon_control" name="seo_meta_description" id="seo_meta_description" rows="3"></textarea>
                            </div>
                            
                            <div class="text-center modal_btn pt-3">
                                <a class="theme_btn red_btn ajax_request no_sidebar_active" data-slug="admin/gkCa/articleNews" href="{{ url('admin/gkCa/articleNews') }}">{{'Cancel'}}</a>
                                <button type="submit" class="theme_btn">{{'Save'}}</button>
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

@push('custom-styles')

@endpush
@push('custom-scripts')
<script type="text/javascript">
    $(function() {
      showLoader(false);
    });
</script>
<script src="{{ url('public/bower_components/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
    CKEDITOR.replace('body_en', {
        filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });
    CKEDITOR.replace('body_hi', {
        filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });
</script>
<script type="text/javascript" src="{{ url('public/js/dashboard/article_news.js') }}"></script>
@endpush