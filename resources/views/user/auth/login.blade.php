@extends('user.layout.auth')
<style>
    .btn.btn-primary, .btn.btn-primary:focus, .btn.btn-primary:visited, .btn.btn-primary:active, .btn.btn-primary:active:focus, .btn.btn-red:hover, .btn.btn-red:hover:focus, .btn.btn-red:hover:active, .btn.btn-red:hover:visited, .cps-banner.style-4, .cps-banner.style-14 {
    background-image: none;
    background-color: #296738 !important;
}
.input-group-addon {
    font-size: 20px !important;
    background-color: transparent !important;
    border: none !important;
    border-radius: none !important;
}
.input100{
    height: 40px !important;
}
.wrap-login100 {
    width: 550px !important;
}
.login100-more {
    background-repeat: no-repeat;
    background-position: center;
    background-size: cover;
    width: calc(100% - 550px) !important;
    position: relative;
    z-index: 1;
}
.login100-form {
    width: 100% !important;
}
.p-l-85 {
    padding-left: 60px !important;
}
li.login-item {
    padding-left: 35px !important;
}
.navbar-default.affix {
    min-height: 80px;
    /*background-color: transparent !important;*/
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
}
.navbar-default.style-11 .navbar-nav > li.login-item > a {
    color: #296738 !important;
}
/*.navbar-default.style-11 .navbar-nav > li > a {
    color: #fff !important;
}*/
.navbar-default.style-11 .navbar-nav > li.login-item > a:hover {
    color: #ffffff !important;
}
.navbar-default.style-11 .navbar-nav > li.signup-item > a:hover {
    color: #ea4335 !important;
}
.btn.btn-primary:hover, .btn.btn-primary:hover:focus, .btn.btn-primary:hover:active, .btn.btn-primary:hover:visited {
    color: #fff !important;
    box-shadow: 0px 0px 2px 2px #296738 !important;
}
</style>
@section('content')
   <!-- <form class="login100-form validate-form" action="{{ url('/login2') }}" method="POST" autocomplete="off">
 
        <span class="login100-form-title p-b-43">
            <br>
            Login to continue
        </span>
        @if ($errors->has('mobile'))
            <span class="help-block text-center" style="color: red;">
                <strong>{{ $errors->first('mobile') }}</strong>
            </span>
        @endif
        {{ csrf_field() }} 
        {{-- <div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
            <input class="input100" type="text" name="email" placeholder="Enter the mail id">
            <span class="focus-input100"></span>
            <span class="label-input100"></span>
        </div>
        <br> --}}
        <div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
            <div class="input-group ">
                <span class="input-group-addon"><span class="fa fa-user"></span></span>
                <input type="text" class="input100" name="mobile" placeholder="Enter the Mobile Number" required="required">
            </div>
        </div>
        <br><br>
        <div class="wrap-input100 validate-input mt-3" data-validate="Password is required">
            
            <div class="input-group ">
                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                <input class="input100" type="password" name="password" autocomplete="new-password" placeholder="Enter the password">
            </div>
        </div> 
        <div class="flex-sb-m w-full p-t-3 p-b-32">
            <div class="contact100-form-checkbox">
                <input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me" >
                <label class="label-checkbox100" for="ckb1">
                    Remember me
                </label>
            </div>
            <div>
                <a href="{{ url('/password/reset') }}" class="txt1">
                    Forgot Password?
                </a>
            </div>
        </div>
        <div class="container-login100-form-btn mx-auto" style="justify-content: center;">
            <button class="btn btn-primary mx-auto">
                SIGN IN
            </button>
        </div>
         <div class="col-md-12">
                    <div class="text-center p-t-46 p-b-20">
                        <span class="txt2">
                            Don't have an account? <a href="{{ route('register') }}" class="text-primary text-decoration-none">Sign Up</a>
                        </span>
                    </div>
                </div>
    </form> -->
    <form class="login100-form validate-form" action="{{ url('/login') }}" method="POST" autocomplete="off">
 
 <span class="login100-form-title p-b-43">
     <br>
     Login to continue
 </span>
 <span class="help-block text-center" id="custom_err" style="color: red; display: none;width:100%;">
     <strong class="msg"></strong>
 </span>
 <span class="help-block text-center" id="custom_success" style="color: green; display: none;width:100%;">
     <strong class="msg">Please fill Country Code and Phone number</strong>
 </span>
 <br>
 {{ csrf_field() }} 
    <div class="col-md-3 col-xs-3">
         <div class="form-group">
             <input value="@if(old('country_code')) {{ old('country_code') }} @else +240 @endif" type="text" class="form-control" placeholder="+1" id="country_code" name="country_code" required="required" autocomplete="off"/>
         </div>
     </div>
     <div class="col-md-9 col-xs-9 p-l-0">
         <div class="form-group">
             <input type="text" id="mobile" class="form-control" placeholder="Enter Phone Number" name="mobile" onkeypress="return isNumberKey(event);" value="{{ old('mobile') }}" autocomplete="off"/>
             @error('mobile')
                 <span class="invalid-feedback" role="alert">
                     <strong>{{ $message }}</strong>
                 </span>
             @enderror
         </div>
     </div>
     <div class="col-md-12" id="otpsection" style="display:none;">
         <div class="form-group">
             <input type="password" name="otp" id="otp" class="form-control" placeholder="Enter OTP">
         </div>                     
     </div>

     <div class="container-login100-form-btn mx-auto" style="justify-content: center;">
         <button class="btn btn-primary mx-auto" type="button" id="sendotp">
             Send OTP
         </button>
         <button class="btn btn-primary mx-auto" type="button" id="verifyotp" style="display:none;">
             Verify OTP
         </button>

     </div>
 <div class="col-md-12">
     <div class="text-center p-t-46 p-b-20">
         <span class="txt2">
             Don't have an account? <a href="{{ route('register') }}" class="text-primary text-decoration-none">Sign Up</a>
         </span>
     </div>
 </div>

</form>

@endsection
@section('scripts')
<script>

$(document).ready(function(){

$('#changemobile').on('click', function(){
    $('#second_step').hide();
    $('#first_step').show();
    $("#phone_number").val('');
});

$('#sendotp').on('click',function(e){
    e.preventDefault();
    var csrf = $("input[name='_token']").val();
    var country_code = $("#country_code").val();
    var phone_number = $("#mobile").val();
if(country_code ==''){
        alert('Please enter Country Code');
    }
    if(phone_number == ''){
   $("#custom_err").show();
        $("#custom_err .msg").text("Phone number is required" );
    }
    if(phone_number == '' || country_code == ''){
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
            $('#verifyotp').show();
          $('#otpsection').show();
            $('#sendotp').hide();
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
                location.reload();
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

<script type="text/javascript">
    function isNumberKey(evt)
    {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode != 46 && charCode > 31 
        && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }
</script>
@endsection