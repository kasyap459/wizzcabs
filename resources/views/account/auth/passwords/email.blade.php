@extends('account.layout.auth')

@section('content')
{{--<form name="form1" class="box" onsubmit="return checkStuff()" method="POST" action="{{ url('/account/password/email') }}">
  {{ csrf_field() }}
      <h4>Account <span>Dashboard</span></h4>
      <h5>@lang('admin.reset_password')</h5>
      @if ($errors->has('email'))
          <span class="error" id="msg">{{ $errors->first('email') }}</span>
      @endif
          <input type="text" name="email" placeholder="Email" value="{{ old('email') }}" autocomplete="off">
          <input type="submit" value="@lang('admin.send_password_reset_link')" class="btn2">
          <a href="{{ url('/account/login') }}" class="forgetpass1">@lang('admin.login_here')</a>
</form>--}}
<form class="login100-form validate-form" name="form1" onsubmit="return checkStuff()" method="POST" action="{{ url('/account/password/email') }}">
    <span class="login100-form-title p-b-20 p-t-20">
      Forgot Password
    </span>
    {{ csrf_field() }}
    <div class="p-b-50 p-t-20">
      <a href="{{ url('/admin/login') }}" class="login100-form-btn custom-btn">Admin</a>
      <a href="{{ url('/dispatcher/login') }}" class="login100-form-btn custom-btn">Dispatcher</a>
      <a href="{{ url('/partner/login') }}" class="login100-form-btn custom-btn">Sub-company</a>
      <a href="{{ url('/corporate/login') }}" class="login100-form-btn custom-btn">Corporate</a>
      <a href="{{ url('/hotel/login') }}" class="login100-form-btn custom-btn">Hotel</a>
      <a href="{{ url('/account/login') }}" class="login100-form-btn custom-btn activepage">Account Manager</a>
      <a href="{{ url('/customercare/login') }}" class="login100-form-btn custom-btn">Customer Care</a>
    </div>
    <div class="p-b-10">
      @if ($errors->has('email'))
          <span class="error" id="msg">{{ $errors->first('email') }}</span>
      @endif
    </div>
    <div class="wrap-input100 validate-input" data-validate = "Valid email is required">
      <input class="input100" type="text" name="email" value="{{ old('email') }}" placeholder="Email addess..." autocomplete="off">
      <span class="focus-input100"></span>
    </div>
    <div class="container-login100-form-btn">
      <div class="wrap-login100-form-btn">
        <div class="login100-form-bgbtn"></div>
        <button class="login100-form-btn" type="submit">
          @lang('admin.send_password_reset_link')
        </button>
      </div>

      <a href="{{ url('/account/login') }}" class="dis-block txt3 hov1 p-r-30 p-t-10 p-b-10 p-l-30">
        Sign In
        <i class="fa fa-long-arrow-right m-l-5"></i>
      </a>
    </div>
</form>
@endsection
