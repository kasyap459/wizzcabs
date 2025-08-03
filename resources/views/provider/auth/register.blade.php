@extends('provider.layout.auth')

@section('style')
<style>
.cps-banner.style-16 .cps-banner-form .account-form {
    background-color: #ffffff;
    border-radius: 3px;
    padding: 4px 42px 20px;
}
.account-form input:not([type=submit]):not([type=radio]):not([type=checkbox]), .account-form select, .account-form textarea {
    border-radius: 3px;
    margin-bottom: 8px;
}
.account-form select{
  padding: 13px 16px !important;
}
.helper{
    color: #008000;
}
.helper1{
    color: #f31616;
}
.termlink{
    color: #0554b5 !important;
}

/*------------------------------------------------------*/

input[type="text"], select {
    font-size: 15px !important;
    font-weight: 500 !important;
    text-align: left;
    text-transform: capitalize;
    letter-spacing: 1px;
    padding: 12px 10px 12px 10px;
    width:90% ;
    display:inline-block ;
    box-sizing: border-box !important;
    border: none;
    outline: none;
    background: transparent !important;
    font-family: 'Open Sans', sans-serif;
}
input[type="email"] {
    font-size: 15px !important;
    font-weight: 500  !important;
    text-align: left;
    letter-spacing: 1px;
    padding: 12px 10px 12px 10px;
    width:90% !important;
    display:inline-block !important;
    box-sizing: border-box !important;
    border: none !important;
    outline: none;
    background: transparent !important;
    font-family: 'Open Sans', sans-serif;
}
input[type="Password"] {
    font-size: 15px !important;
    font-weight: 500 !important;
    text-align: left;
    text-transform: capitalize;
    letter-spacing: 1px;
    padding: 12px 10px 12px 10px;
    width:90%;
    display:inline-block !important;
    box-sizing: border-box;
    border: none !important;
    outline: none;
    background: transparent !important;
    font-family: 'Open Sans', sans-serif;
}
select{
    font-size: 15px !important;
    font-weight: 500 !important;
    text-align: left;
    text-transform: capitalize;
    letter-spacing: 1px;
    padding: 6px 49px 6px 36px !important;
    width: 94% !important;
    display: inline-block;
    box-sizing: border-box !important;
    border: none !important;
    outline: none;
    background: transparent !important;
    font-family: 'Open Sans', sans-serif;
    background-color: white;
}
.input-group {
    padding: 0px 0px;
    border-bottom: 1px solid #ddd;
    margin-bottom: 3px;
    margin-top: 7px;
    display: block !important;
}
.btn-block {
    display: block;
    width: 100%;
}
.btn:active {
    outline: none;
}
.btn-danger {
    color: #f9f9f9 !important;
    background-color: #231f20 !important;
    margin-top: 30px !important;
    width: 100% !important;
    outline:none;
    padding:15px 12px !important;
    cursor:pointer !important;
    letter-spacing: 2px !important;
    font-size: 15px !important;
    font-weight: 600 !important;
    border: 1px solid #fff !important;
    text-transform: uppercase !important;
    transition: 0.5s all !important;
    -webkit-transition: 0.5s all !important;
    -moz-transition: 0.5s all !important;
    -o-transition: 0.5s all !important;
    -ms-transition: 0.5s all !important;
    font-family: 'Open Sans', sans-serif !important;
    background-image: none;
}
.btn-verify {
    color: #fff !important;
    background-color: #231f20 !important;
    margin-top: 23px !important;
    width: 100% !important;
    outline: none;
    padding: 15px 12px !important;
    cursor: pointer !important;
    letter-spacing: 2px !important;
    font-size: 11px !important;
    font-weight: 600 !important;
    border: 1px solid #353434 !important;
    text-transform: uppercase !important;
    transition: 0.5s all !important;
    -webkit-transition: 0.5s all !important;
    -moz-transition: 0.5s all !important;
    -o-transition: 0.5s all !important;
    -ms-transition: 0.5s all !important;
    font-family: 'Open Sans', sans-serif !important;
    background-image: none;
}
.input-verify{
    padding: 0px 0px;
    border-bottom: none;
    margin-bottom: 28px;
    margin-top: 7px;
}
.btn-danger:hover{
    background: transparent !important;
    border: 1px solid #121312 !important;
    color: #121312 !important;
}
.btn-verify:hover{
    background: transparent !important;
    border: 1px solid #121312 !important;
    color: #121312 !important;
}
.w3_info h2 {
  display: inline-block;
    font-size: 26px;
    margin-bottom: 40px;
    color: #353434;
    letter-spacing: 2px;    
}
.w3_info h4 {
    display: inline-block;
    font-size: 15px;
    color: #444;
    text-transform: capitalize;
}
span.fa.fa-facebook {
    vertical-align: middle;
    font-size: 20px;
    padding-left: 20px;
}
 ::-webkit-input-placeholder {
color:#999 !important;
}
:-moz-placeholder { /* Firefox 18- */
color:#999 !important;
}
::-moz-placeholder {  /* Firefox 19+ */
color:#999 !important;
letter-spacing:5px;
}
:-ms-input-placeholder {  
color:#999 !important;
}

a.btn.btn-block.btn-social.btn-facebook {
    display: block;
    width: 100%;
    padding: 10px 0px;
    text-align: center;
    font-size: 16px;
    font-weight: 600;
    letter-spacing: 1px;
}
h1 {
      text-align: center;
    font-size: 45px;
    margin: 30px 0px;
    letter-spacing: 3px;
    color: #fff;
    text-transform: uppercase;
    font-weight: 700;
}
.w3_info {
    flex-basis: 65%;
    -webkit-flex-basis: 65%;
    box-sizing: border-box;
    padding: 4em;
    background-color: #fff;
}
.left_grid_info {
    padding: 7em 4em;
}
.left_grid_info h3 {
    color: #353434;
    font-size: 2.2em;
}
.left_grid_info p {
    color: #383636;
    font-size: 14px;
    margin: 2em 0;
    line-height: 28px;
}
.btn{
    line-height: 1.42857143 !important;
    border-radius: 4px !important;
}
a.btn {
    color: #565555 !important;
    font-weight: 600 !important;
    font-size: 15px !important;
    padding: 12px 40px !important;
    border: 1px solid #565555 !important;
    display: inline-block !important;
    margin-top: 1em !important;
    text-transform: uppercase !important;
    letter-spacing: 2px !important;
}
a.btn:hover {
    background: #fff !important;
    color: #565555 !important;
}
.agile_info {
    display: -webkit-box;      /* OLD - iOS 6-, Safari 3.1-6 */
    display: -moz-box;         /* OLD - Firefox 19- (buggy but mostly works) */
    display: -ms-flexbox;      /* TWEENER - IE 10 */
    display: -webkit-flex;     /* NEW - Chrome */
    display: flex;             /* NEW, Spec - Opera 12.1, Firefox 20+ */
}

.w3l_form {
    padding: 0px;
    flex-basis: 35%;
    -webkit-flex-basis: 35%;
    background: #f7d9d7;
}
.left {
    width: 49%;
    float: left;
    margin-bottom: 20px;
}
.margin {
    margin-right: 2%;
}
label {
    margin: 0;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: #777;
}
h3.w3ls {
    margin: 10px 0px;
    padding-left: 60px;
}
h3.agileits {
    padding-left: 10px;
}
.container1 {
    width: 75%;
    margin: 0 auto;
}
.input-group i.fa {
    font-size: 16px;
    vertical-align: middle;
    color: #999;
    box-sizing:border-box;
    float:left;
    width: 6%;
    text-align: center;
    margin-top: 12px;
}
h5 {
    text-align: center;
    margin: 10px 0px;
    font-size: 15px;
    font-weight: 600;
        color: #000;
}

@media screen and (max-width: 1080px){
    .left_grid_info {
        padding: 7em 3em;
    }
    .w3_info {
        padding: 3em 3em;
    }
    .left_grid_info h4 {
        font-size: 1.1em;
    }
}
@media screen and (max-width: 1024px){
    .left_grid_info h3 {
        font-size: 2em;
    }
    .left_grid_info {
        padding: 5em 3em;
    }
}
@media screen and (max-width: 991px){
    .w3_info h2 {
        font-size: 24px;
    }
}
@media screen and (max-width: 900px){
    
    .left_grid_info h4 {
        font-size: 1em;
    }
    .agile_info {
        flex-direction: column;
    }
    .left_grid_info {
        padding: 3em 3em;
    }
}
@media screen and (max-width: 800px){
    input[type="text"],input[type="email"],input[type="password"] {
        font-size: 14px;
    }
    i.fa.fa-user,i.fa.fa-envelope,i.fa.fa-lock {
        margin-top: 10px;
    }
}
@media screen and (max-width: 768px){
    
    .left_grid_info h3 {
        font-size: 1.6em;
    }
    .left {
        width: 100%;
        float: none;
        margin: 0;
    }
}
@media screen and (max-width: 736px){
    
    .left_grid_info h3 {
        font-size: 1.7em;
    }
    .w3_info h2 {
        font-size: 22px;
        margin-bottom: 20px;
    }
    .w3_info {
        padding: 3em 2em;
    }
    .footer p {
        font-size: 14px;
    }
    .w3_info h4 {
        font-size: 14px;
    }
    .btn-danger {
        padding: 13px 12px;
        font-size: 14px;
    }
}
@media screen and (max-width: 640px){
    .w3l_form {
        padding: 3em 4em;
        float: none;
        margin: 0 auto;
    }
    .left_grid_info {
        padding: 0;
    }
    .w3_info {
        padding: 3em 4em;
        margin: 0 auto;
    }
    label {
        font-size: 14px;
        letter-spacing: .5px;
    }
}
@media screen and (max-width: 568px){
    .btn-danger {
        font-size: 13px;
        width: 55%;
    }
    a.btn {
        font-size: 13px;
        padding: 10px 40px;
        letter-spacing: 1px;
    }
}
@media screen and (max-width: 480px){
    .w3l_form {
        padding: 3em;
    }
    .w3_info {
        padding: 3em;
    }
    .btn-danger {
        width: 63%;
    }
}
@media screen and (max-width: 414px){
    .w3l_form {
        padding: 3em 2em;
    }
    .w3_info {
        padding: 2em;
    }
    .left_grid_info p {
        font-size: 13px;
    }
    
    .left_grid_info h3 {
        font-size: 1.4em;
    }
}
@media screen and (max-width: 384px){
    .left_grid_info h4 {
        font-size: .9em;
    }
    .left_grid_info p {
        letter-spacing: .5px;
    }
    .btn-danger {
        width: 70%;
    }
    .input-group {
        margin-bottom: 25px;
        margin-top: 0px;
    }
}
@media screen and (max-width: 375px){
    .left_grid_info h3 {
        font-size: 1.5em;
    }
    .w3_info h4 ,label,input[type="text"], input[type="email"], input[type="password"]{
        font-size: 13px;
    }
}
@media screen and (max-width: 320px){
    .btn-danger {
        padding: 13px 12px;
        font-size: 13px;
    }
    input[type="text"], input[type="email"], input[type="password"] {
        font-size: 13px;
    }
    .w3_info h2 {
        font-size: 20px;
        letter-spacing: 1px;
    }
}
.code{
    width: 20% !important;
    display: inline-block !important;
    padding: 0px 10px !important;
    margin-right: 18px !important;
    border: none !important;
    border-bottom: 1px solid gray !important;
    border-radius: 0 !important;
}
.phone{
    display: inline-block !important;
    width: 60% !important;
    border: none !important;
    border-bottom: 1px solid gray !important;
    border-radius: 0 !important;
}
.width{
    width: 90% !important;
    border: none !important;
}
.no-border{
    border: none;
}
.helper{
    margin-top: 36px;
}
.btn, .btn:focus, .btn:visited, .btn:active, .btn:active:focus{
    background-image: none;
}
</style>
@endsection

@section('content')
<div class="page-header">
        <div class="container">
            <h2 class="page-title">Register</h2>
        </div>
    </div>
<!-- Page Header End -->
<div class="cps-section cps-section-padding custom-padding">
            <div class="container1">
            <div class="row">
                <div class="agile_info">
                    <div class="w3_info">
                        <h2>Register Here</h2>
                                            @if($errors->any())
                                <div id="alert_error" class="alert alert-danger" style="display: none;">
                                    
                                    <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form id="provider_registration" action="{{ url('/provider/register') }}" method="POST">
                            {{ csrf_field() }}
                        <span class="help-block text-center" id="custom_err" style="color: red; display: none;">
                            <strong class="msg">Please fill Country Code and Phone number</strong>
                        </span>
                        <span class="help-block text-center" id="custom_success" style="color: green; display: none;">
                            <strong class="msg">Please fill Country Code and Phone number</strong>
                        </span>
                        <div id="first_step">
                            <div class="left margin" style="width: 65%;">
                                <label>PHONE NUMBER</label>
                                <div class="input-group no-border">
                                    <span><i class="fa fa-phone" aria-hidden="true"></i></span>
                                    <input class="code" type="text" value="@if(Session::has('dial_code')) {{ Session::get('dial_code') }} @else +240 @endif" width="100%" placeholder="+240" id="country_code" name="country_code" required="">
                                    <input class="phone" type="text" autofocus id="phone_number" placeholder="Phone Number" name="phone_number" value="{{ old('phone_number') }}" required="" onkeypress="return isNumberKey(event);">
                                </div>
                            </div>
                            <div class="left" style="width: 33%;">
                        <div class="input-verify" id="mobile_verfication">
                            <button class="btn btn-verify btn-block"  id="sendopt" type="button">Send O T P</button>
                        </div> 
                    </div>
                        </div>
                        <div id="second_step" style="display: none;">
                            <div class="left margin" style="width: 65%;">
                                <label>Enter OTP</label>
                                <div class="input-group no-border">
                                    <span><i class="fa fa-phone" aria-hidden="true"></i></span>
                                    <input class="phone" type="text" autofocus id="otp" placeholder="Enter OTP" name="otp" value="{{ old('otp') }}" required="" onkeypress="return isNumberKey(event);">
                                </div>
                            </div>
                            <div class="left" style="width: 33%;">
                                <div class="input-verify" id="mobile_verfication">
                                    <button class="btn btn-verify btn-block"  id="verifyotp" type="button">Verify O T P</button>
                                </div> 
                            </div>
                        </div>
                        <div id="third_step" style="display: none;">
                           
                            <div>
                                <label>Full Name</label>
                                <div class="input-group">
                                    <span><i class="fa fa-user" aria-hidden="true"></i></span>
                                    <input class="width" type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Full Name" autofocus required=""> 
                                </div>
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div>
                                <label>Email Address</label>
                                <div class="input-group">
                                    <span><i class="fa fa-envelope" aria-hidden="true"></i></span>
                                    <input class="width" id="email" type="email" name="email" value="{{ old('email') }}" placeholder="E-Mail Address" required=""> 
                                </div>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                                    <input class="width" id="password" type="hidden" name="password" value="123456" placeholder="Password" required="">
                                    <input class="width" id="password-confirm" type="hidden" name="password_confirmation" value="123456" placeholder="Confirm Password" required="">
                               
                            <div>
                                <label>Select Gender</label>
                                <div class="input-group">
                                    <span><i class="fa fa-user" aria-hidden="true"></i></span>
                                    <select name="gender" id="gender" required="">
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                @if ($errors->has('gender'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('gender') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div>
                                <label>License Number</label>
                                <div class="input-group">
                                    <span><i class="fa fa-car" aria-hidden="true"></i></span>
                                    <input class="width" id="license_no" type="text" name="license_no" value="{{ old('license_no') }}" placeholder="License Number" required="">
                                </div>
                                @if ($errors->has('license_no'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('license_no') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div>
                                <label>License Expired Date</label>
                                <div class="input-group">
                                    <span><i class="fa fa-car" aria-hidden="true"></i></span>
                                    <input class="width" id="license_expire" type="date" name="license_expire" value="{{ old('license_expire') }}" data-date="" data-date-format="YYYY-MM-DD" placeholder="License Expired Date" required="">
                                </div>
                                @if ($errors->has('license_expire'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('license_expire') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div>
                                <label>Select Country</label>
                                <div class="input-group">
                                    <span><i class="fa fa-user" aria-hidden="true"></i></span>
                                    <select name="country_id" id="country_id">
                                        <option value="">Select Country</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->countryid }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('gender'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('gender') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div>
                                <label>Address</label>
                                <div class="input-group">
                                    <span><i class="fa fa-globe" aria-hidden="true"></i></span>
                                    <input class="width" id="address" type="text" name="address" value="{{ old('address') }}" placeholder="Address" required="">
                                </div>
                                @if ($errors->has('address'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="clear"></div>
                    <div style="display: inline-block;">       
                        <button class="btn btn-danger btn-block" type="submit">Register Now <i class="fa fa-long-arrow-right" aria-hidden="true"></i></button >
                    </div>  
                    </div>               
                </form>
        </div>
        <div class="w3l_form">
            <div class="left_grid_info">
                <h3>{{ Setting::get('site_title','Unicotaxi') }} needs Partner Like You</h3>
                <p>Drive with {{ Setting::get('site_title','Unicotaxi') }} and earn great money as an independent contractor. Get paid weekly just for helping our community of riders get rides around town. Be your own boss and get paid in fares for driving on your own schedule.</p>
                <a href="{{ url('/provider/login') }}" class="btn">Login <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
            </div>
        </div>
        <div class="clear"></div>
        </div>
     </div>
    </div>
</div>

@endsection



@section('scripts')
<script type="text/javascript">
            $("#provider_registration").submit(function(e){
                var checkBox1 = document.getElementById("privacy");
                var errotext = document.getElementById("errortext");
                if (checkBox1.checked == true){
                    checkBox1.disabled= true;
                    errotext.style.display = "none";
                    return true;
                }else{
                    errotext.style.display = "block";
                    return false;
                }
            });
        </script>
        
        <script>
        $(document).ready(function(){
            $('#changemobile').on('click', function(){
                $('#second_step').hide();
                $('#first_step').show();
                $('#alert_error').hide();
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
                    url: "{{url('/provider/sendotp')}}",
                    type:'POST',
                    data:{ mobile :phone_number ,country_code :country_code ,'_token':csrf},
                    success: function(result) {
                        if(result.success ==1){
                        $("#custom_err").hide();
                        $('#alert_error').hide();
                        $("#custom_success").show();
                        $("#custom_success .msg").text(result.data);
                        $('#second_step').show();
                        $('#first_step').hide();
                        }else{
                            $('#alert_error').hide();
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
                    $('#alert_error').hide();
                    $("#custom_success").hide();
                    $("#custom_err").show();
                    $("#custom_err .msg").text('Please enter O T P');
                    return false;
                }
                if(otp.length != 6){
                    $('#alert_error').hide();
                    $("#custom_success").hide();
                    $("#custom_err").show();
                    $("#custom_err .msg").text('Please check O T P');
                    return false;
                }
                $.ajax({
                    url: "{{url('/provider/verifyotp')}}",
                    type:'POST',
                    data:{ otp :otp ,'_token':csrf},
                    success: function(result) {
                        if(result.success ==1){
                            $("#custom_err").hide();
                            $("#custom_success").show();
                            $("#custom_success .msg").text(result.data);
                            $('#third_step').show();
                            $('#second_step').hide();
                            $('#alert_error').hide();
                        }else{
                            $('#alert_error').hide();
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
        
            $('#create_account').on('click',function(e){
                e.preventDefault();
                var csrf = $("input[name='_token']").val();
                var name = $("#name").val();
                var email = $("#email").val();
                var password = $("#password").val();
                var password_confirmation = $("#password-confirm").val();
                var gender = $("#gender").val();
                var country_id = $("#country_id").val();
                var license_no = $("#license_no").val();
                var license_expire = $("#license_expire").val();
                var address = $("#address").val();
                if(password != password_confirmation){
                    $("#custom_success").hide();
                    $("#custom_err").show();
                    $('#alert_error').hide();
                    $("#custom_err .msg").text('The password confirmation does not match.');
                    return false;
                }
               
                $.ajax({
                    url: "{{url('/register')}}",
                    type:'POST',
                    data:{ name :name, email :email, password :password, password_confirmation :password_confirmation , gender:gender, country_id:country_id, license_no:license_no, license_expire:license_expire, address:address,'_token':csrf},
                    success: function(result) {
                        location.reload();
                    },
                    error:function(jqXhr,status) {
                        console.log(jqXhr); 
                        console.log(status);
                        if(jqXhr.status === 422) {
                            $("#custom_err").show();
                            $("#custom_success").hide();
                            var errors = jqXhr.responseJSON;
                            $.each( errors.errors , function( key, value ) { 
                                $("#custom_err .msg").html(value[0]);
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