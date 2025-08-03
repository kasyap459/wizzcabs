@extends('user.layout.auth')
@section('styles')
<style type="text/css">
    .alert-validate::after {
    	bottom: calc((100% - 0px) / 2) !important;
    }
    .alert-validate::before {
    	bottom: calc((100% - 0px) / 2) !important;
    }
    .input100 {
    	width: 100%;
    	height: inherit;
    	padding-left: 10px !important;
    }
    .login100-form-btn {
    	font-size: 16px;
    }
</style>
@endsection
@section('content')
    <form class="login100-form validate-form" action="{{ url('/password/email2') }}" method="POST">
        <span class="login100-form-title p-b-43">
            Reset Password
        </span>
        @if ($errors->has('mobile'))
            <span class="help-block text-center" style="color: red;">
                <strong>{{ $errors->first('mobile') }}</strong>
            </span>
        @endif
         @if (session('message'))
            <span class="help-block text-center" style="color: red;">
                <strong>{{ session('message') }}</strong>
            </span>
        @endif
        {{ csrf_field() }} 
        <div class="wrap-input100 validate-input" >
            <input class="input100" type="text" name="mobile" placeholder="Mobile Number" autocomplete="off" required="">
            <span class="focus-input100"></span>
        </div>
        <div class="container-login100-form-btn">
      		<div class="wrap-login100-form-btn">
        		<div class="login100-form-bgbtn"></div>
        		<button class="login100-form-btn" type="submit">
          			Send Reset Password Link
        		</button>
      		</div>
		<a href="{{route('login')}}" class="dis-block txt3 hov1 p-r-30 p-t-10 p-b-10 p-l-30">
        		Login
        		<i class="fa fa-long-arrow-right m-l-5"></i>
      		</a>
      	</div>
        {{--<div class="text-center p-t-46 p-b-20">
            <a href="{{url('register')}}">
                <span class="txt2">
                    Or <a href="{{route('login')}}">Login in</a> with your user account.
                </span>
            </a>
        </div>--}}
    </form>
@endsection
