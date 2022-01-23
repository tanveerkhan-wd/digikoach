@extends('layout.app_with_login')
@section('title','View Article & News ')
@section('script', url('public/js/dashboard/article_news.js'))
@section('content')	
<!-- Page Content  -->
<div class="section">
    <div class="container-fluid">
        <div class="row ">
            <div class="col-md-4 mb-3">
                <h2 class="title"> <span>{{' Gk & Ca '}}</span> > <a  class="ajax_request" data-slug="admin/gkCa/articleNews" href="{{url('/admin/gkCa/articleNews')}}"><span>{{' Article & News '}}</span></a>  >  {{'View'}}</h2>
            </div>  
            <div class="col-md-4 text-md-right mb-3">

            </div>
            <div class="col-md-4 text-md-right mb-3">
                <a class="theme_btn red_btn ajax_request no_sidebar_active" data-slug="admin/gkCa/articleNews" href="{{ url('admin/gkCa/articleNews') }}">{{'Back'}}</a>
            </div>
        </div>
        <div class="white_box">
            <div class="theme_tab">
                    
                <form name="add-article-form">
                    <div class="row">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-6">
                            @foreach($data->article_desc as $sData)
                                <div class="form-group">
                                    <label>@if($sData->lang_code=='en') {{' Title In English'}} @else {{' Title In Hindi'}} @endif</label>
                                    <input type="text" value="{!! $sData->article_title !!}" @if($sData->lang_code=='en') name="title_en" @else name="title_hi" @endif class="form-control icon_control" disabled>
                                </div>
                                

                            @endforeach

                            @foreach($data->article_desc as $sData)
                                <div class="form-group">
                                    <label>@if($sData->lang_code=='en') {{' Body In English'}} @else {{' Body In Hindi'}} @endif </label>
                                    <div class="border-live-test-sol"> {!! $sData->article_body !!} </div>
                                </div>
                            @endforeach

                            <div class="form-group">
                                <label>{{'SEO Meta Title'}}</label>
                                <input type="text" name="seo_meta_title" id="seo_meta_title" class="form-control icon_control" value="{{$data->meta_title}}" disabled>
                            </div>
                            <div class="form-group">
                                <label>{{'SEO Meta Description'}}</label>
                                <textarea class="form-control icon_control" name="seo_meta_description" id="seo_meta_description" rows="3" disabled>{!! $data->meta_description !!}</textarea>
                            </div>

                            <div class="text-center modal_btn pt-3">
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

<script type="text/javascript" src="{{ url('public/js/dashboard/article_news.js') }}"></script>
@endpush