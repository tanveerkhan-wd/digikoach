@extends('layout.app_without_login')
@section('title','Reset Password')
@section('content')
  <div class="auth_box">
  
    <form action="{{ route('resetPasswordPost') }}" method="post" id="loginForm" name="loginForm">
      {{ csrf_field() }}
      <div class="row">
          <div class="col-lg-1"></div>
          <div class="col-lg-10">
            <h1>{{'Reset Password'}}</h1>
            <input type="hidden" name="token" id="token" value="{{$token}}">
            <div class="form-group">
              <label>{{'Password'}}</label>
              <input type="password" placeholder="{{'New Password'}}" class="form-control" name="new_password" id="new_password">
            </div>

            <div class="form-group">
              <label>{{'Confirm Password'}}</label>
              <input type="password" placeholder="{{'Confirm Password'}}" class="form-control" name="confirm_password" id="confirm_password">
            </div>
            <div class="text-center">
              <button type="submit" class="theme_btn auth_btn">{{'Confirm'}}</button>
              <p><a href="{{ route('adminLoginForm') }}" class="auth_link">{{'Login'}}</a></p>
            </div>
            <div class="text-center process_msg"></div>
            <div class="row">
              <div class="col-lg-12">
                <div class="text-center">© Copyright {{ now()->year }} Digikoach | All Rights Reserved</div>
              </div>
              
            </div>
          </div>
          <div class="col-lg-1"></div>
      </div>
    </form>
  </div>
@endsection
@push('custom-styles')
<!-- Include this Page CSS -->
<link link rel="stylesheet" type="text/css" href="{{ url('public/css/toastr.min.css') }}">
@endpush
@push('custom-scripts')
  <script type="text/javascript" src="{{ url('public/js/login/reset_password.js') }}"></script>
  <script type="text/javascript" src="{{ url('public/js/toastr.min.js') }}"></script>
@endpush