<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ @csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/x-icon" href="{{url('/public/images/ic_fevicon.png')}}">
  <title>@yield('title') - Digikoach</title>
  <link rel="stylesheet" href="{{ url('public/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ url('public/css/animate.css') }}">
  <link rel="stylesheet" href="{{ url('public/css/custom.css') }}">
  <link rel="stylesheet" href="{{ url('public/css/custom-rt.css') }}">

  <style type="text/css">
   .alert a{
    text-decoration: none;
   }
   .alert{
        margin: 25px;
    }
    .alert.alert-danger {    
      /*top: 2pc !important;*/
    }
    .alert.alert-success {    
        /*top: 2pc !important;*/
    }
  </style>
  @stack('custom-styles')
</head>

<body class="hold-transition login-page">
    
    <div id="preloader"><div id="status"><div class="spinner"></div></div></div>
    <div class="auth_container" id="contents" style="opacity: 0;">
      <div class="auth_logo text-center">
          <a href="{{url('/')}}"><img src="{{ url('public/images/digikoach_logo.png') }}" alt="Digikoach Logo" class="login_logo"></a>
      </div>
      @yield('content')
    </div>
    
    <input type="hidden" id="get_base_url" value="{{ URL::to('/') }}">
    <input type="hidden" id="field_required_txt" value="{{'This field is required'}}">
    <input type="hidden" id="email_validate_txt" value="{{'Please enter a valid email address'}}">
    <input type="hidden" id="minlength_validate_txt" value="{{'Please enter at least {0} characters.'}}">
    <input type="hidden" id="maxlength_validate_txt" value="{{'Please enter no more than {0} characters.'}}">
    <input type="hidden" id="validate_password_txt" value="{{'The password must be a combination of characters, numbers, one uppercase letter and special characters'}}">
    <input type="hidden" id="validate_password_equalto_txt" value="{{'New password and Confirm password does not match'}}">
    <input type="hidden" id="validate_equalto_txt" value="{{'Please enter the same value again'}}">

    <script type="text/javascript" src="{{ url('public/js/all.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('public/js/jquery-3.4.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('public/js/wow.min.js') }}"></script>
    {{-- <script type="text/javascript" src="{{ url('public/js/owl.carousel.js') }}"></script>   --}}
    <script type="text/javascript" src="{{ url('public/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('public/js/popper.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('public/js/custom.js') }}"></script>  
    {{-- <script src="{{ url('public/js/msdropdown/jquery.dd.min.js') }}"></script> --}}
    <script src="{{ url('public/js/jquery.validate.js') }}"></script>
    <script src="{{ url('public/js/custom_validation_msg.js') }}"></script>
    <script src="{{ url('public/js/promise.min.js') }}"></script>
    {{-- <script src="{{ url('public/js/additional-methods.js') }}"></script> --}}
    <script type="text/javascript">
      var base_url = $('#get_base_url').val();
    </script> 
    @stack('custom-scripts')
  </body>
</html>