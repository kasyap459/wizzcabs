@extends('web.layouts.app')

@section('styles')

<style>
    .book-ride-one .booking-form-one button[type=button] {
    cursor: pointer;
    border: none;
    outline: none;
    width: 100%;
    background-color: #fff;
    height: 67px;
    border-radius: 33.5px;
    text-align: center;
    font-size: 18px;
    font-weight: 600;
    color: #000;
    transition: all .4s ease;
}

.button--google {
  background-color: #3F85F4;
  border: 1px solid #0f66f1;
}
.button--google .icon {
  border-right: 1px solid #0f66f1;
}
.button--google .icon:after {
  border-right: 1px solid #6fa4f7;
}
.button--google:hover {
  background-color: #2776f3;
}

.button {
  width: auto;
  display: inline-block;
  padding: 0 18px 0 6px;
  border: 0 none;
  border-radius: 5px;
  text-decoration: none;
  transition: all 250ms linear;
}
.button:hover {
  text-decoration: none;
}

.button--social-login {
  margin-bottom: 12px;
  margin-right: 12px;
  color: white;
  height: 50px;
  line-height: 46px;
  position: relative;
  text-align: left;
}
.button--social-login .icon {
  margin-right: 12px;
  font-size: 24px;
  line-height: 24px;
  width: 42px;
  height: 24px;
  text-align: center;
  display: inline-block;
  position: relative;
  top: 4px;
}
.button--social-login .icon:before {
  display: inline-block;
  width: 40px;
}
.button--social-login .icon:after {
  content: "";
}
</style>

@endsection

@section('content')
<!-- Banner -->

<section class="inner-banner">
            <div class="container">
                <ul class="thm-breadcrumb">
                    <li><a href="{{url('/')}}">Home</a></li>
                    <li><span class="sep">.</span></li>
                    <li><span class="page-title">Login</span></li>
                </ul><!-- /.thm-breadcrumb -->
                <h2>Login Now</h2>
            </div><!-- /.container -->
        </section><!-- /.inner-banner -->

<section class="book-ride-one">
            <!-- <img src="web/images/background/footer-bg-1-1.png" class="footer-bg" alt="Awesome Image" /> -->
            <div class="container">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="content-block">
                            <div class="block-title mb-0">
                                <div class="dot-line"></div><!-- /.dot-line -->
                                <p class="light">Looking for taxi?</p>
                                <h2 class="light">Login to Your Account</h2>
                            </div><!-- /.block-title -->
                            <p>Our taxis commit to make your trips unique <br> by best answering your needs.</p>
                        </div><!-- /.content-block -->
                    </div><!-- /.col-lg-4 -->
                    <div class="col-lg-5 text-center">
                    <form class="booking-form-one" action="{{ url('/login') }}" method="POST">
        <h3 class="login100-form-title mb-4" style="color: #fff;">
            Login Now
        </h3>
        <span class="help-block text-center" id="custom_err" style="color: red; display: none;">
            <strong class="msg">Please fill Country Code and Phone number</strong>
        </span>
        <span class="help-block text-center" id="custom_success" style="color: green; display: none;">
            <strong class="msg">Please fill Country Code and Phone number</strong>
        </span>
             @if($errors->any())
                        <div id="alert_error" class="alert alert-danger" style="display: none;">
                            
                            <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                            </ul>
                        </div>
                    @endif
        {{ csrf_field() }} 

        <div id="first_step">
            <div class="row">
            <div class="col-md-3">
                <div class="input-holder">
                <input value="@if(Session::has('dial_code')) {{ Session::get('dial_code') }} @else +677 @endif" type="text" class="form-control" placeholder="+677" id="country_code" name="country_code" required="required" />
            </div>
            </div>
            <div class="col-md-8">
                <div class="input-holder">
                <input type="text" autofocus id="phone_number" class="form-control" placeholder="Enter Phone Number" name="phone_number" value="{{ old('phone_number') }}" required="required" onkeypress="return isNumberKey(event);" maxlength="10" pattern="[1-9]{1}[0-9]{9}"/>
            </div>
            </div>
            <div class="col-md-1"></div>
            <br><br>
            </div>
            <div class="row d-flex align-items-center justify-content-center h-100 mt-4">
            <div class="col-md-6">
                <div class="container-btn" id="mobile_verfication">
                    <button class="btn btn-verify btn-block" type="button" id="sendopt">
                        Send O T P
                    </button>
                </div>
            </div>
        </div>
        </div>

        <div id="second_step" style="display: none;">
            <div class="col-md-12 mt-2">
                <input type="text" autofocus id="otp" class="form-control" placeholder="Enter OTP" name="otp" value="{{ old('otp') }}" required="required" onkeypress="return isNumberKey(event);"/>
            </div>
            <br>
            <div class="row  d-flex align-items-center justify-content-center h-100 mt-2">
            <div class="col-md-6">
                <div class="container-login100-form-btn" id="mobile_verfication">
                    <button class="btn btn-verify btn-block" type="button" id="verifyotp">
                        Verify O T P
                    </button>
                </div>
            </div>
        </div>
            <div class="col-md-12">
                <div class="text-center p-b-20">
                    <span class="txt2" id="changemobile" style="cursor: pointer;">Change mobile number?
                    </span>
                </div>
            </div>
        </div>
        <!-- <div id="third_step" style="display: none;">
            <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <input type="text" placeholder="First Name" class="form-control" value="{{ old('first_name') }}" name="first_name" id="first_name" required>
                </div>
                </div>
            <div class="col-md-6">
            <div class="form-group">
                    <input type="text" placeholder="Last Name" class="form-control" value="{{ old('last_name') }}" name="last_name" id="last_name" required>
                </div>
                </div>

           
            <div class="col-md-12">
                <div class="form-group">
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control" placeholder="Email Address">
                </div>                     
            </div>
        </div>
            
                    <input type="hidden" name="password" class="form-control" value="123456" placeholder="Password" id="password">
             
                    <input type="hidden" placeholder="Re-type Password" value="123456" class="form-control" name="password_confirmation" id="password_confirmation">
               
           
            <div class="col-md-12">
                <div class="container-login100-form-btn" id="mobile_verfication">
                    <button class="btn btn-verify btn-block" id="create_account" type="button">
                        Login Now
                    </button>
                </div>
            </div>
        </div> -->
        <div class="col-md-12">
            <div class="text-center p-t-46 p-b-20">
                <span class="txt2">
                    Or <a href="{{route('register')}}">Register</a> with your account
                </span>
            </div>
        </div>

        <!-- <div class="col-md-12 mt-4">
            <a class="button button--social-login button--google" href="{{ url('auth/google') }}"><i class="icon fa fa-google"></i>Login With Google</a>
        </div> -->
    </form>
                    </div><!-- /.col-lg-8 -->
                </div><!-- /.row -->
            </div><!-- /.container -->
        </section><!-- /.book-ride-one -->
@endsection

@section('scripts')

<script>

$(document).ready(function(){

$('#changemobile').on('click', function(){
    $('#second_step').hide();
    $('#first_step').show();
    $("#phone_number").val('');
});
var error = "{{ $errors->any()}}";
if(error == 1)
{
    
    $('#alert_error').show();
    $('#second_step').hide();
    $('#first_step').hide();
    $('#third_step').show();
}

$('#sendopt').on('click',function(e){
    e.preventDefault();
    var csrf = $("input[name='_token']").val();
    var country_code = $("#country_code").val();
    var phone_number = $("#phone_number").val();
    if(country_code ==''){
        $("#custom_err").show();
        $("#custom_err .msg").text('Please enter Country Code');
        return false;
    }
    if(phone_number ==''){
        $("#custom_err").show();
        $("#custom_err .msg").text('Please enter Phone number');
        return false;
    }
    
    $.ajax({
        url: "{{url('/sendotplogin')}}",
        type:'POST',
        data:{ mobile :phone_number ,country_code :country_code ,'_token':csrf},
        success: function(result) {
            if(result.success ==1){
            $("#custom_err").hide();
            $("#custom_success").show();
            $("#custom_success .msg").text(result.data);
            $('#second_step').show();
            $('#first_step').hide();
            }else{
                $("#custom_err").show();
                $("#custom_err .msg").text(result.data);        
            }
        },
        error:function(jqXhr,status) { 
            if(jqXhr.status === 422) {
                $("#custom_err").show();
                var errors = jqXhr.responseJSON;
                $.each( errors , function( key, value ) { 
                    $("#custom_err .msg").html(value);
                }); 
            } 
        }

    });
   
});

$('#verifyotp').on('click',function(e){
    e.preventDefault();
    var csrf = $("input[name='_token']").val();
    var otp = $("#otp").val();
    if(otp==''){
        $("#custom_success").hide();
        $("#custom_err").show();
        $("#custom_err .msg").text('Please enter O T P');
        return false;
    }
    if(otp.length != 6){
        $("#custom_success").hide();
        $("#custom_err").show();
        $("#custom_err .msg").text('Please check O T P');
        return false;
    }
    $.ajax({
        url: "{{url('/verifyotplogin')}}",
        type:'POST',
        data:{ otp :otp ,'_token':csrf},
        success: function(result) {
            if(result.success ==1){
                $("#custom_err").hide();
                $("#custom_success").show();
                $("#custom_success .msg").text(result.data);
                location.reload(true);
                $('#second_step').hide();
            }else{
                $("#custom_success").hide();
                $("#custom_err").show();
                $("#custom_err .msg").text(result.data);
            }
        },
        error:function(jqXhr,status) { 
            if(jqXhr.status === 422) {
                $("#custom_err").show();
                var errors = jqXhr.responseJSON;
                $.each( errors , function( key, value ) { 
                    $("#custom_err .msg").html(value);
                }); 
            }
        }

    });
});
});
</script>
@endsection
