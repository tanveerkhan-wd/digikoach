@extends('layout.app_without_login')
@section('title','Login')
@section('content')

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
      
      <form action="{{ route('adminLoginFormPost') }}" method="post" id="loginForm" name="loginForm">
        {{ csrf_field() }}
        <div class="row">
          <div class="col-lg-1"></div>
          <div class="col-lg-10">
            <h1>{{$translations['ln_login'] ?? 'Login'}}</h1>
            <div class="form-group">
              <label>{{$translations['ln_email'] ?? 'Email'}}</label>
              <input type="text" name="email" class="form-control" placeholder="{{$translations['ln_enter_email'] ?? 'Enter Email'}}">
            </div>
            <div class="form-group">
              <label>{{$translations['ln_password'] ?? 'Password'}}</label>
              <input type="password" name="password" class="form-control" placeholder="{{$translations['ln_enter_password'] ?? 'Enter Password'}}">
            </div>
            <div class="text-center">
              <button type="submit" class="theme_btn auth_btn">{{$translations['ln_login'] ?? 'Login'}}</button>
              <p><a href="{{ route('forgotPassword') }}" class="auth_link">{{$translations['ln_forgot_password'] ?? 'Forgot Password'}}?</a></p>
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

@endsection

@push('custom-scripts')
  {{-- <script type="text/javascript" src="{{ url('/js/login/login.js') }}"></script> --}}
@endpush