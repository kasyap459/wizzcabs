@extends('user.layout.auth')

@section('content')
    <form class="login100-form validate-form" action="{{ url('/password/reset') }}" method="POST">
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
        <div class="wrap-input100 validate-input" >
        <span><i class="fa fa-user" aria-hidden="true" style="transform: translateX(-41px);"></i></span>
        <input class="label-input100" id="email" type="text" name="mobile" value="{{ $mobile }}" style="padding-top: 8px;transform: translateX(-31px);" autofocus placeholder="Mobile number" required="">
            
        </div>
        <div class="wrap-input100 validate-input" data-validate="Password is required">
            <span><i class="fa fa-lock" aria-hidden="true" style="transform: translateX(-41px);"></i></span>
           <input class="label-input100" id="password" type="password" style="padding-top: 8px;transform: translateX(-37px);" name="password" placeholder="Password"> 
        </div>
        <div class="wrap-input100 validate-input" data-validate="Re-type Password is required">
            <span><i class="fa fa-user" aria-hidden="true" style="transform: translateX(-41px);"></i></span>
         <input class="label-input100" id="password-confirm" type="password" style="padding-top: 8px;transform: translateX(-37px);" name="password_confirmation" placeholder="Re-enter Password"> 
        </div>
        <div class="container-login100-form-btn">
            <div>     
                <button class="btn btn-danger btn-block" type="submit">Reset Password<i class="fa fa-long-arrow-right" aria-hidden="true"></i></button >
            </div> 
        </div>
        <div class="text-center p-t-46 p-b-20">
            <a href="{{url('register')}}">
                <span class="txt2">
                    Or <a href="{{route('login')}}">Sign in</a> with your user account.
                </span>
            </a>
        </div>
    </form>
@endsection
