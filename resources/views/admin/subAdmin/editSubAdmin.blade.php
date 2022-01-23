@extends('layout.app_with_login')
@section('title','Edit Sub-Admin')
@section('script', url('public/js/dashboard/sub_admins.js'))
@section('content')	
<!-- Page Content  -->
<div class="section">
    <div class="container-fluid">
        <h2 class="title"><a  class="ajax_request" data-slug="admin/subAdmin" href="{{url('/admin/subAdmin')}}"><span>{{'Sub-Admins '}}</span></a>  >  {{'Edit'}}
        </h2>
        <div class="white_box">
            <div class="theme_tab">
                    
                <form name="add-sub-admin-form">
                    <div class="row">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-6">
                            <input type="hidden" name="pkCat" value="{{$data['user_id'] ?? ''}}">
                            <div class="form-group">
                                <label>{{'Name'}}</label>
                                <input type="text" name="name" id="name" class="form-control icon_control"  value="{{ $data['name'] ?? '' }}">
                            </div>

                            <div class="form-group">
                                <label>{{'Phone'}}</label>
                                <input type="number" name="mobile_number" id="mobile_number" class="form-control icon_control" value="{{ $data['mobile_number'] ?? '' }}">
                            </div>

                            <div class="form-group">
                                <label>{{'Email'}}</label>
                                <input type="email" name="email" id="email" class="form-control icon_control"  value="{{ $data['email'] ?? '' }}">
                            </div>

                            <div class="text-center modal_btn pt-3">
                                <a class="theme_btn red_btn ajax_request no_sidebar_active" data-slug="admin/subAdmin" href="{{ url('admin/subAdmin') }}">{{'Cancel'}}</a>
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

<script type="text/javascript" src="{{ url('public/js/dashboard/sub_admins.js') }}"></script>
@endpush