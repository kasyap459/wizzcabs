@extends('user.layout.auth')

@section('content')
    <form class="login100-form " action="{{ url('/register') }}" method="POST">
        <span class="login100-form-title p-b-43">
            Register Now
        </span>
        @if ($errors->has('phone_number'))
            <span class="help-block text-center" style="color: red;">
                <strong>{{ $errors->first('phone_number') }}</strong>
            </span>
        @endif
        @if ($errors->has('name'))
            <span class="help-block text-center" style="color: red;">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
        @endif
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
        @if ($errors->has('email'))
            <span class="help-block text-center" style="color: red;">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
        <span class="help-block text-center" id="custom_err" style="color: red; display: none;">
            <strong>Please fill Country Code and Phone number</strong>
        </span>
        
        {{ csrf_field() }} 
        <div id="first_step">
            <div class="col-md-4">
                <input value="+91" type="text" class="form-control" placeholder="+1" id="country_code" name="country_code" required="required" />
            </div>
            <div class="col-md-8">
                <input type="text" autofocus id="phone_number" class="form-control" placeholder="Enter Phone Number" name="phone_number" value="{{ old('phone_number') }}" required="required" onkeypress="return isNumberKey(event);"/>
            </div>
            <br><br>
            <div class="col-md-12">
                <div class="container-login100-form-btn" id="mobile_verfication">
                    <button class="login100-form-btn" type="button" onclick="smsLogin();">
                        Verify Phone Number
                    </button>
                </div>
            </div>
        </div>
        <div id="second_step" style="display: none;">
            <div class="col-md-12">
                <div class="form-group">
                    <input type="text" placeholder="Full Name" class="form-control" name="name" value="{{ old('name') }}">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Email Address" value="{{ old('email') }}">
                </div>                     
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <input type="password" placeholder="Re-type Password" class="form-control" name="password_confirmation">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <select class="form-control" name="gender">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <select name="country_id" id="country_id" class="form-control">
                        <option value="">Select Country</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->countryid }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="container-login100-form-btn" id="mobile_verfication">
                    <button class="login100-form-btn">
                        Register Now
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="text-center p-t-46 p-b-20">
                <span class="txt2">
                    Or <a href="{{route('login')}}">Login in</a> with your account
                </span>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
<script src="https://sdk.accountkit.com/en_US/sdk.js"></script>
<script>
  // initialize Account Kit with CSRF protection
  AccountKit_OnInteractive = function(){
    AccountKit.init(
      {
        appId: {{env('FB_APP_ID')}}, 
        state:"state", 
        version: "{{env('FB_APP_VERSION')}}",
        fbAppEventsEnabled:true
      }
    );
  };

  // login callback
  function loginCallback(response) {
    if (response.status === "PARTIALLY_AUTHENTICATED") {
      var code = response.code;
      var csrf = response.state;
      // Send code to server to exchange for access token
      $('#mobile_verfication').html("<p class='helper'> * Phone Number Verified </p>");
      $('#phone_number').attr('readonly',true);
      $('#country_code').attr('readonly',true);
      $('#second_step').fadeIn(400);

      $.post("{{route('account.kit')}}",{ code : code }, function(data){
        $('#phone_number').val(data.phone.national_number);
        $('#country_code').val('+'+data.phone.country_prefix);
      });

    }
    else if (response.status === "NOT_AUTHENTICATED") {
      // handle authentication failure
      $('#mobile_verfication').html("<p class='helper'> * Authentication Failed </p>");
    }
    else if (response.status === "BAD_PARAMS") {
      // handle bad parameters
    }
  }

  // phone form submission handler
  function smsLogin() {
    var countryCode = document.getElementById("country_code").value;
    var phoneNumber = document.getElementById("phone_number").value;
    if(countryCode !='' && phoneNumber!=''){
        $('#custom_err').hide();
        $('#mobile_verfication').html("<p class='helper'> Please Wait... </p>");
        $('#phone_number').attr('readonly',true);
        $('#country_code').attr('readonly',true);

        AccountKit.login(
          'PHONE', 
          {countryCode: countryCode, phoneNumber: phoneNumber}, // will use default values if not specified
          loginCallback
        );
    }else{
        $('#custom_err').show();
    }
   
  }

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