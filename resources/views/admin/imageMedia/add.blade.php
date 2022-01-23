@extends('layout.app_with_login')
@section('title','Add Image Media')
@section('script', url('public/js/dashboard/image_media.js'))
@section('content')	
<!-- Page Content  -->
<div class="section">
    <div class="container-fluid">
        <h2 class="title"><a  class="ajax_request" data-slug="admin/imageMedia" href="{{url('/admin/imageMedia')}}"><span>{{'Image Media '}}</span></a>  >  {{'Add New'}}
        </h2>
        <div class="white_box">
            <div class="theme_tab">
                <form name="add-image-form">
                    <div class="row">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-6">

                            <div class="form-group text-center">
                                   <div class="imagemediaGallery"></div>
                                    <label class="la la-edit" style="cursor: pointer;">
                                        <input  type="file" name="imagemedia[]" id="imagemedia" accept="image/*" multiple required="" class="form-control">
                                        <div class="errorImage"></div>
                                        <div id="imagemedia_validate"></div>
                                    </label>                                   
                                </div>
                            
                        </div>
                        <div class="col-lg-3"></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-center modal_btn pt-3">
                                <a class="theme_btn red_btn ajax_request no_sidebar_active" data-slug="admin/subAdmin" href="{{ url('admin/subAdmin') }}">{{'Cancel'}}</a>
                                <button type="submit" class="theme_btn">{{'Save'}}</button>
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

<script type="text/javascript" src="{{ url('public/js/dashboard/image_media.js') }}"></script>
@endpush