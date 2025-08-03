@extends('hotel.layout.auth')

@section('content')
<form class="login100-form validate-form" method="POST" action="{{ url('/hotel/password/email') }}">
    <span class="login100-form-title p-b-20 p-t-20">
      Forgot Password
    </span>
    {{ csrf_field() }}
    <div class="p-b-50 p-t-20">
      <a href="{{ url('/admin/login') }}" class="login100-form-btn custom-btn">Admin</a>
      <a href="{{ url('/dispatcher/login') }}" class="login100-form-btn custom-btn">Dispatcher</a>
      <a href="{{ url('/partner/login') }}" class="login100-form-btn custom-btn">Sub Company</a>
      <a href="{{ url('/corporate/login') }}" class="login100-form-btn custom-btn">Corporate</a>
      <a href="{{ url('/hotel/login') }}" class="login100-form-btn custom-btn activepage">Hotel</a>
      <a href="{{ url('/account/login') }}" class="login100-form-btn custom-btn">Account Manager</a>
      <a href="{{ url('/customercare/login') }}" class="login100-form-btn custom-btn">Customer Care</a>
    </div>
    <div class="p-b-10">
      @if ($errors->has('email'))
          <span class="error" id="msg">{{ $errors->first('email') }}</span>
      @endif
    </div>
    <div class="wrap-input100 validate-input" data-validate = "Valid email is required">
      <input class="input100" type="text" name="email" placeholder="Email addess..." autocomplete="off">
      <span class="focus-input100"></span>
    </div>
    <div class="container-login100-form-btn">
      <div class="wrap-login100-form-btn">
        <div class="login100-form-bgbtn"></div>
        <button class="login100-form-btn" type="submit">
          Send Reset Password Link
        </button>
      </div>

      <a href="{{ url('/hotel/login') }}" class="dis-block txt3 hov1 p-r-30 p-t-10 p-b-10 p-l-30">
        Sign In
        <i class="fa fa-long-arrow-right m-l-5"></i>
      </a>
    </div>
</form>
@endsection
