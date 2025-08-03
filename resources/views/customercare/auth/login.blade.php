@extends('customercare.layout.auth')

@section('content')
<form class="login100-form validate-form" method="POST" action="{{ url('/customercare/login') }}">
    <span class="login100-form-title p-b-20 p-t-20">
      Sign In
    </span>
    {{ csrf_field() }}
    <div class="p-b-50 p-t-20">
      <a href="{{ url('/admin/login') }}" class="login100-form-btn custom-btn">Admin</a>
      <a href="{{ url('/dispatcher/login') }}" class="login100-form-btn custom-btn">Dispatcher</a>
      <a href="{{ url('/partner/login') }}" class="login100-form-btn custom-btn">Sub Company</a>
      <a href="{{ url('/corporate/login') }}" class="login100-form-btn custom-btn">Corporate</a>
      <a href="{{ url('/hotel/login') }}" class="login100-form-btn custom-btn">Hotel</a>
      <a href="{{ url('/account/login') }}" class="login100-form-btn custom-btn">Account Manager</a>
      <a href="{{ url('/customercare/login') }}" class="login100-form-btn custom-btn activepage">Customer Care</a>
    </div>
    <div class="p-b-10">
      @if ($errors->has('email'))
          <span class="error" id="msg">{{ $errors->first('email') }}</span>
      @endif
      @if ($errors->has('password'))
         <span class="error" id="msg">{{ $errors->first('password') }}</span> 
      @endif
    </div>
    <div class="wrap-input100 validate-input" data-validate = "Valid email is required">
      <input class="input100" type="text" name="email" placeholder="Email address..." autocomplete="off">
      <span class="focus-input100"></span>
    </div>
    <div class="wrap-input100 validate-input" data-validate = "Password is required">
      <input class="input100" type="password" name="password" placeholder="Password">
      <span class="focus-input100"></span>
    </div>
    <div class="flex-m w-full p-b-33">
      <div class="contact100-form-checkbox">
        <input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
        <label class="label-checkbox100" for="ckb1">
          <span class="txt1">
            Remember me
          </span>
        </label>
      </div>
    </div>
    <div class="container-login100-form-btn">
      <div class="wrap-login100-form-btn">
        <div class="login100-form-bgbtn"></div>
        <button class="login100-form-btn" type="submit">
          Sign In
        </button>
      </div>

      <a href="{{ url('/customercare/password/reset') }}" class="dis-block txt3 hov1 p-r-30 p-t-10 p-b-10 p-l-30">
        Forgot Password
        <i class="fa fa-long-arrow-right m-l-5"></i>
      </a>
    </div>
</form>
@endsection
