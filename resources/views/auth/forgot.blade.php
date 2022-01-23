@extends('layout.app_without_login')
@section('title','Forgot Password')
@section('content')
  <!-- /.login-logo -->
  <div class="auth_box">
      @if (\Session::has('success'))
          <div class="alert alert-success">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              {{ session()->get('success') }}
          </div>
      @endif
      @if ($errors->any())
      @foreach ($errors->all() as $error)
        <div class="alert alert-danger">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          {{ $error }}
        </div>
      @endforeach
      @endif
    <form action="{{ route('forgotPasswordPost') }}" method="post" id="loginForm" name="loginForm">
      {{ csrf_field() }}
      <div class="row">
          <div class="col-lg-1"></div>
          <div class="col-lg-10">
            <h1>{{'Forgot Password'}}</h1>
            <p class="auth_text">{{'Please enter your registered Email address'}}</p>
            <div class="form-group">
              <label>{{'Email'}}</label>
              <input type="text" name="email" class="form-control" placeholder="{{'Email'}}">
            </div>
            <div class="text-center">
              <button class="theme_btn auth_btn">{{'Submit'}}</button>
              <p><a href="{{ route('adminLoginForm') }}" class="auth_link">{{'Login'}}</a></p>
            </div>
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
  <!-- /.login-box-body -->
@endsection
@push('custom-scripts')
  <script type="text/javascript" src="{{ url('public/js/login/forgot.js') }}"></script>
@endpush