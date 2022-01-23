@extends('layout.app_with_login')
@section('title','CMS')
@section('script', url('public/js/dashboard/cms.js'))
@section('content')	
<!-- Page Content  -->
<div class="section">
	<div class="container-fluid">
        <div class="container-fluid">
            <h2 class="title"><span>{{'Master'}}</span> > <a  class="ajax_request" data-slug="admin/cms" href="{{url('/admin/cms')}}"><span>{{'CMS'}}</span></a>  >  {{'Edit'}}</h2>
            <div class="white_box pt-3">
                <div class="theme_tab">
                    <form name="add-cms-form">
                        <div class="row">
                            <div class="col-lg-3"></div>
                            <div class="col-lg-6">
                                <input type="hidden" name="pkCat" id="pkCat" value="{{$aTable->cms_id}}">
                                @foreach($aTable->cms_desc as $value)
                                <div class="form-group">
                                    <label>{{'Title In'}} @if($value->lang_code=='en') {{'English'}} @else {{'Hindi'}} @endif</label>
                                    <input type="text" name="name_{{$value->lang_code}}" id="name_{{$value->lang_code}}" class="form-control icon_control" value="{{$value->cms_title}}">
                                </div>
                                <div class="form-group">
                                    <label>{{'Description In '}}@if($value->lang_code=='en') {{'English'}} @else {{'Hindi'}} @endif</label>
                                    <textarea class="form-control icon_control" name="desc_{{$value->lang_code}}" id="desc_{{$value->lang_code}}" rows="3">{{$value->cms_description}}</textarea>
                                </div>
                                @endforeach
                                <div class="form-group">
                                    <label>{{'SEO Meta Title'}}</label>
                                    <input type="text" name="seo_meta_title" id="seo_meta_title" class="form-control icon_control" value="{{$aTable->seo_meta_title}}">
                                </div>
                                <div class="form-group">
                                    <label>{{'SEO Meta Description'}}</label>
                                    <textarea class="form-control icon_control" name="seo_meta_description" id="seo_meta_description" rows="3">{{$aTable->seo_meta_description}}</textarea>
                                </div>
                                <div class="text-center modal_btn ">
                                    <button type="submit" class="theme_btn">{{'Submit'}}</button>
                                    <a class="theme_btn red_btn ajax_request no_sidebar_active" data-slug="admin/cms" href="{{ url('admin/cms') }}">{{'Cancel'}}</a>
                                </div>
                            </div>
                            <div class="col-lg-3"></div>
                        </div>
                    </form>
                    
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

<script type="text/javascript" src="{{ url('public/js/dashboard/cms.js') }}"></script>
@endpush