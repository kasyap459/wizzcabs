<!DOCTYPE html>
<html lang="en">
<head>
  <title>{{ Setting::get('site_title', 'Unicotaxi') }}</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="{{ Setting::get('site_icon') }}"/>
  <link rel="stylesheet" type="text/css" href="{{asset('main/logins2/vendor/bootstrap/css/bootstrap.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('main/logins2/fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('main/logins2/fonts/Linearicons-Free-v1.0.0/icon-font.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('main/logins2/fonts/iconic/css/material-design-iconic-font.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('main/logins2/vendor/animate/animate.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('main/logins2/css/util.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('main/logins2/css/main.css')}}">
</head>
<body style="background-color: #999999;">
  
  <div class="limiter">
    <div class="container-login100">
      <div class="login100-more" style="background-image: url('{{asset('main/logins2/images/bg-01.jpg')}}');">
        <div class="p-l-50 p-r-20 p-t-250 p-b-20 left-sec">
          <h3 class="p-b-10">Kick-off Your Business <span class="green">With</span></h3>
          <h2>{{ Setting::get('site_title', 'Unicotaxi') }}</h2>
          <h3 class="p-t-50"><span class="green besmart">Be Smart</span> & Complete <br>Entrepreneur!!!</h3>
        </div>    
      </div>
      
      <div class="wrap-login100 p-l-50 p-r-20 p-t-20 p-b-20">
        <div>
        <img src="{{asset('asset/img/unico.png')}}" style="height:170px;" alt="Logo">
        </div>
        @yield('content')
      </div>
    </div>
  </div>
  
  <script src="{{asset('main/logins2/vendor/jquery/jquery-3.2.1.min.js')}}"></script>
  <script src="{{asset('main/logins2/vendor/bootstrap/js/popper.js')}}"></script>
  <script src="{{asset('main/logins2/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
  <script src="{{asset('main/logins2/js/main.js')}}"></script>

</body>
</html>