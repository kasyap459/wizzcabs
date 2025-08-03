@extends('hotel.layout.auth')

@section('content')
    <form class="login100-form validate-form" action="{{ url('/hotel/password/reset') }}" method="POST">
        <span class="login100-form-title p-b-43">
            Reset Password
        </span>
        @if ($errors->has('email'))
            <span class="help-block text-center" style="color: red;">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
        @if ($errors->has('password'))
            <span class="help-block text-center" style="color: red;">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
        @if ($errors->has('password_confirmation'))
            <span class="help-block text-center" style="color: red;">
                <strong>{{ $errors->first('password_confirmation') }}</strong>
            </span>
        @endif
        {{ csrf_field() }}
        <input type="hidden" name="token" value="{{ $token }}"> 
        <div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
            <input class="input100" type="text" name="email">
            <span class="focus-input100"></span>
            <span class="label-input100">Email</span>
        </div>
        <div class="wrap-input100 validate-input" data-validate="Password is required">
            <input class="input100" type="password" name="password">
            <span class="focus-input100"></span>
            <span class="label-input100">Password</span>
        </div>
        <div class="wrap-input100 validate-input" data-validate="Re-type Password is required">
            <input class="input100" type="password" name="password_confirmation">
            <span class="focus-input100"></span>
            <span class="label-input100">Re-type Password</span>
        </div>
        <div class="container-login100-form-btn">
            <button class="login100-form-btn">
                Reset password
            </button>
        </div>
    </form>
@endsection
