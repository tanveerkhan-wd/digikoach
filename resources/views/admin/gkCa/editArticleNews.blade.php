@extends('layout.app_with_login')
@section('title','Edit Article & News ')
@section('script', url('public/js/dashboard/article_news.js'))
@section('content')	
<!-- Page Content  -->
<div class="section">
    <div class="container-fluid">
        <h2 class="title"> <span>{{' Gk & Ca '}}</span> > <a  class="ajax_request" data-slug="admin/gkCa/articleNews" href="{{url('/admin/gkCa/articleNews')}}"><span>{{' Article & News '}}</span></a>  >  {{'Edit'}}
        </h2>
        <div class="white_box">
            <div class="theme_tab">
                    
                <form name="add-article-form">
                    <div class="row">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-6">
                            <input type="hidden" name="pkCat" value="{{$data->articles_news_id}}">
                            @foreach($data->article_desc as $sData)
                                <div class="form-group">
                                    <label>@if($sData->lang_code=='en') {{' Title In English'}} @else {{' Title In Hindi'}} @endif</label>
                                    <input type="text" value="{{ $sData->article_title }}" @if($sData->lang_code=='en') name="title_en" id="title_en" @else name="title_hi" id="title_hi"  @endif class="form-control icon_control">
                                </div>
                                

                            @endforeach

                            @foreach($data->article_desc as $sData)
                                <div class="form-group">
                                    <label>@if($sData->lang_code=='en') {{' Body In English'}} @else {{' Body In Hindi'}} @endif </label>
                                    <textarea @if($sData->lang_code=='en') name="body_en" id="body_en" @else  name="body_hi" id="body_hi" @endif class="form-control icon_control">{{ $sData->article_body }}</textarea>
                                </div>
                            @endforeach

                            <div class="form-group">
                                <label>{{'Select Status'}}</label>
                                <select class="form-control icon_control dropdown_control" name="status_type" id="status_type">
                                    <option value="">{{'Select'}}</option>
                                    <option value="1" @if($data->status==1) selected @endif>{{'Active'}}</option>
                                    <option value="0" @if($data->status==0) selected @endif>{{'Inactive'}}</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>{{'SEO Meta Title'}}</label>
                                <input type="text" name="seo_meta_title" id="seo_meta_title" class="form-control icon_control" value="{{$data->meta_title}}">
                            </div>
                            <div class="form-group">
                                <label>{{'SEO Meta Description'}}</label>
                                <textarea class="form-control icon_control" name="seo_meta_description" id="seo_meta_description" rows="3">{!! $data->meta_description !!}</textarea>
                            </div>

                            <div class="text-center modal_btn pt-3">
                                <a class="theme_btn red_btn ajax_request no_sidebar_active" data-slug="admin/gkCa/articleNews" href="{{ url('admin/gkCa/articleNews') }}">{{'Back'}}</a>
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