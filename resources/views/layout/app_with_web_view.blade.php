<!DOCTYPE html>
<html>
<head>
  	<title>Digikoach| @yield('title')</title>
  	<meta charset="utf-8">
  	<meta name="csrf-token" content="{{ @csrf_token() }}">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="icon" type="image/x-icon" href="{{url('/public/images/ic_fevicon.png')}}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Karla&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{url('/public/css/web-view-css.css')}}">
  @stack('custom-styles')
</head>
<body style="font-family: Karla;">
    <div id="preloader">
        <div id="status">
          <div class="spinner"></div>
        </div>
    </div>
    <div id="preloader_new" style="opacity: 0; display: none;">
        <div id="status_new">
          <div class="spinner"></div>
        </div>
    </div>  	
  	@yield('content') 

  	<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<script type="text/javascript">
  $('#preloader').css('display','');
      $(window).on('load', function(){
      $('#preloader').css('display','none');
      $('#preloader').css('opacity','0');
      $('#contents').css('opacity','1');
  });
  function showLoader($show){
      if($show){
        $('#preloader_new').show();
        $('#preloader_new').css('opacity',1);
      }else{
        $('#preloader_new').hide();
        $('#preloader_new').css('opacity',0);
      }
  }

  $(function() {
    showLoader(false);
  });
</script>
  @stack('custom-scripts')
</body>
</html>
